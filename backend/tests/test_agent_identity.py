import hashlib
import hmac
import os
import time
import unittest
from concurrent.futures import ThreadPoolExecutor
from unittest.mock import patch

from fastapi import HTTPException
from fastapi.testclient import TestClient
from langchain_core.tools import tool

from travel_agent_api.agent_identity import internal_headers, verify_identity
from travel_agent_api.main import app
from travel_agent_api.services.agent_service import Agent
from travel_agent_api.tools.fornitori_tool import cerca_fornitori
from travel_agent_api.tools.itinerari_tool import cerca_itinerari, leggi_itinerario, salva_bozza_itinerario


def signed(user_id: int, expiry: int, secret: str = "test-token") -> str:
    payload = f"{user_id}.{expiry}"
    signature = hmac.new(secret.encode(), payload.encode(), hashlib.sha256).hexdigest()
    return f"{payload}.{signature}"


class AgentIdentityTests(unittest.TestCase):
    def setUp(self):
        self.environment = patch.dict(os.environ, {"INTERNAL_API_TOKEN": "test-token"})
        self.environment.start()
        self.addCleanup(self.environment.stop)

    def test_identity_validation_and_chat_endpoint_reject_invalid_requests(self):
        identity = signed(1, int(time.time()) + 60)
        self.assertEqual(identity, verify_identity(identity))
        for invalid in ("", "1.9999999999.invalid", signed(1, int(time.time()) - 1)):
            with self.assertRaises(HTTPException):
                verify_identity(invalid)
        client = TestClient(app)
        for path in ("/chat/travel-agent", "/chat/travel-agent/stream"):
            self.assertEqual(401, client.post(path, json={"messages": []}).status_code)
            self.assertEqual(401, client.post(path, json={"messages": []}, headers={"X-Agent-Identity": "bad"}).status_code)
        with patch("travel_agent_api.routes.chat_router.Agent") as agent:
            agent.return_value.stream.return_value = iter(["ok"])
            response = client.post("/chat/travel-agent/stream", json={"messages": []}, headers={"X-Agent-Identity": identity})
            self.assertEqual(200, response.status_code)
            self.assertEqual("ok", response.text)
            agent.assert_called_once_with(identity)

    def test_bound_tools_cannot_mix_identities_between_threads(self):
        @tool
        def capture() -> str:
            """Return the identity for the active request."""
            return internal_headers()["X-Agent-Identity"]

        one = Agent._bind_identity(capture, "identity-one")
        two = Agent._bind_identity(capture, "identity-two")
        with ThreadPoolExecutor(max_workers=2) as executor:
            results = list(executor.map(lambda bound: bound.invoke({}), [one, two] * 20))
        self.assertEqual(["identity-one", "identity-two"] * 20, results)
        with self.assertRaises(LookupError):
            internal_headers()

    def test_real_tools_send_the_bound_identity_to_laravel(self):
        identity = signed(1, int(time.time()) + 60)
        with patch("httpx.get") as get:
            get.return_value.status_code = 200
            get.return_value.json.return_value = []
            Agent._bind_identity(cerca_fornitori, identity).invoke({"ambito": "Alloggi"})
            self.assertEqual(identity, get.call_args.kwargs["headers"]["X-Agent-Identity"])
            Agent._bind_identity(cerca_itinerari, identity).invoke({"regione": "Lazio"})
            self.assertEqual(identity, get.call_args.kwargs["headers"]["X-Agent-Identity"])
            get.return_value.json.return_value = {"id": 1}
            Agent._bind_identity(leggi_itinerario, identity).invoke({"id": 1})
            self.assertEqual(identity, get.call_args.kwargs["headers"]["X-Agent-Identity"])
        with patch("httpx.post") as post:
            post.return_value.status_code = 201
            post.return_value.json.return_value = {"id": 2, "stato": "bozza", "titolo": "Rome"}
            result = Agent._bind_identity(salva_bozza_itinerario, identity).invoke({
                "titolo": "Rome", "type": "trip", "giorni": [{"numero": 1, "citta": "Rome"}],
            })
            self.assertIn("Bozza salvata", result)
            self.assertEqual(identity, post.call_args.kwargs["headers"]["X-Agent-Identity"])
            self.assertEqual("Rome", post.call_args.kwargs["json"]["giorni"][0]["citta"])


if __name__ == "__main__":
    unittest.main()
