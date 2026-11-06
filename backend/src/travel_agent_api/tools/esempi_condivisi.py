from typing import Literal, Optional

from langchain.tools import tool
from pydantic import BaseModel, Field


ESEMPI = {
    "trip": """Example: short city stay
Type: trip | 3 days / 2 nights
Day 1 - Arrival
  - Private Transfer: airport to hotel with assistance.
  - Accommodation: hotel to be selected (2 nights).
  - Private Tour with Vehicle [optional]: evening city tour.
Day 2 - City
  - Private Tour with Vehicle [Option 1]: full-day tour.
  - Private Tour with Vehicle [Option 2]: half-day tour.
Day 3 - Departure
  - Private Transfer: hotel to departure point.""",
    "trip_multitappa": """Example: multi-stop trip
Type: trip | multiple destinations
Day 1 - First destination
  - Accommodation: hotel to be selected.
  - Private Transfer: airport to hotel.
Day 2 - Second destination
  - Private Transfer: hotel to next destination.
  - Private Transportation Service: vehicle at disposal.
  - Accommodation: hotel to be selected.
  - Assistance: driver assistance.
Day 3 - Second destination
  - Private Transportation Service: vehicle at disposal for local visits.""",
    "wedding": """Example: destination wedding
Type: wedding | 3 days / 2 nights
Day 1 - Arrival
  - Private Transfer: airport to wedding destination.
  - Accommodation: venue or hotel to be selected.
Day 2 - Wedding day
  - Assistance: support for the wedding celebration.
Day 3 - Departure
  - Private Transfer: accommodation to departure point.""",
}


class CercaEsempiInput(BaseModel):
    tipo: Optional[Literal["trip", "wedding"]] = Field(
        None, description="Tipo di esempio: trip o wedding; ometti per vedere tutti gli esempi."
    )


@tool("cerca_esempi_condivisi", args_schema=CercaEsempiInput)
def cerca_esempi_condivisi(tipo: Optional[Literal["trip", "wedding"]] = None) -> str:
    """Consulta esempi anonimi condivisi per la struttura di viaggi e matrimoni.

    Usali come modello di giorni, servizi e alternative, anche quando l'archivio
    personale e' vuoto. Non sono itinerari dell'utente, prenotazioni, fornitori
    disponibili o preventivi; i dettagli devono essere definiti per la richiesta.
    """
    keys = ("trip", "trip_multitappa", "wedding")
    if tipo == "trip":
        keys = ("trip", "trip_multitappa")
    elif tipo == "wedding":
        keys = ("wedding",)
    return "\n\n---\n\n".join(ESEMPI[key] for key in keys)
