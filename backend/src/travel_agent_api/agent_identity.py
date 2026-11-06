import hashlib
import hmac
import os
import time
from contextvars import ContextVar

from fastapi import Header, HTTPException

identity_context: ContextVar[str] = ContextVar("agent_identity")


def verify_identity(x_agent_identity: str = Header(default="")) -> str:
    secret = os.getenv("INTERNAL_API_TOKEN", "")
    parts = x_agent_identity.split(".")
    if not secret or len(parts) != 3:
        raise HTTPException(status_code=401, detail="Unauthorized")
    user_id, expiry, signature = parts
    if not user_id.isascii() or not user_id.isdigit() or not expiry.isascii() or not expiry.isdigit():
        raise HTTPException(status_code=401, detail="Unauthorized")
    now = int(time.time())
    if int(user_id) < 1 or int(expiry) < now or int(expiry) > now + 1800:
        raise HTTPException(status_code=401, detail="Unauthorized")
    expected = hmac.new(secret.encode(), f"{user_id}.{expiry}".encode(), hashlib.sha256).hexdigest()
    if not hmac.compare_digest(expected, signature):
        raise HTTPException(status_code=401, detail="Unauthorized")
    return x_agent_identity


def internal_headers() -> dict[str, str]:
    return {
        "X-Internal-Token": os.environ["INTERNAL_API_TOKEN"],
        "X-Agent-Identity": identity_context.get(),
        "Accept": "application/json",
    }
