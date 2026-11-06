import unittest
from datetime import datetime
from unittest.mock import patch

from travel_agent_api.services.agent_service import Agent, ITINERARY_EXAMPLE
from travel_agent_api.tools.esempi_condivisi import cerca_esempi_condivisi


class EsempiCondivisiTest(unittest.TestCase):
    def test_examples_are_filterable_and_contain_only_structural_placeholders(self):
        trips = cerca_esempi_condivisi.invoke({"tipo": "trip"})
        wedding = cerca_esempi_condivisi.invoke({"tipo": "wedding"})
        all_examples = cerca_esempi_condivisi.invoke({})
        self.assertIn("short city stay", trips)
        self.assertIn("multi-stop trip", trips)
        self.assertNotIn("destination wedding", trips)
        self.assertIn("destination wedding", wedding)
        self.assertNotIn("short city stay", wedding)
        self.assertIn(trips, all_examples)
        self.assertIn(wedding, all_examples)
        for forbidden in ("$", "EUR", "USD", "UNA Hotels", "Borgo Vescine", "2027"):
            self.assertNotIn(forbidden, all_examples)
        self.assertIn("to be selected", all_examples)

    def test_agent_has_library_without_replacing_existing_prompt_or_private_tools(self):
        with patch("travel_agent_api.services.agent_service.ChatOpenAI"), \
             patch("travel_agent_api.services.agent_service.create_react_agent"):
            agent = Agent("identity")
        names = {tool.name for tool in agent.tools}
        self.assertTrue({"cerca_esempi_condivisi", "cerca_itinerari", "cerca_fornitori", "salva_bozza_itinerario"} <= names)
        agent.current_datetime = datetime(2026, 1, 1)
        history = agent._build_history([{"role": "user", "content": "Ciao"}])
        self.assertIn(ITINERARY_EXAMPLE, history[0]["content"])
        self.assertEqual({"role": "user", "content": "Ciao"}, history[-1])


if __name__ == "__main__":
    unittest.main()
