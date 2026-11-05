import os
from typing import List, Literal, Optional

import httpx
from langchain.tools import tool
from pydantic import BaseModel, Field

TipoServizio = Literal[
    "Private Transfer",
    "Private Tour with Vehicle",
    "Private Guide Service",
    "Private Transportation Service",
    "Group Guide Service",
    "Accommodation",
    "Assistance",
]


def _base_url() -> str:
    return os.getenv("LARAVEL_API_URL", "http://127.0.0.1:8002/api/internal")


def _headers() -> dict:
    return {"X-Internal-Token": os.getenv("INTERNAL_API_TOKEN", ""), "Accept": "application/json"}


class CercaItinerariInput(BaseModel):
    regione: Optional[str] = Field(None, description="Regione italiana (es. Toscana, Piemonte). Un itinerario puo' toccare piu' regioni.")
    fornitore: Optional[str] = Field(None, description="Nome (anche parziale) di un fornitore usato nell'itinerario, es. un hotel.")
    citta: Optional[str] = Field(None, description="Citta' toccata dall'itinerario, es. Rome, Florence.")
    tipo: Optional[Literal["trip", "wedding"]] = Field(None, description="trip = viaggio, wedding = matrimonio.")
    durata_min: Optional[int] = Field(None, description="Durata minima in giorni.")


@tool("cerca_itinerari", args_schema=CercaItinerariInput)
def cerca_itinerari(
    regione: str = None, fornitore: str = None, citta: str = None, tipo: str = None, durata_min: int = None
) -> str:
    """Cerca gli itinerari reali gia' presenti nel gestionale (esempi e bozze), filtrando per regione, fornitore, citta', tipo o durata.
    Restituisce un elenco sintetico con id: usa `leggi_itinerario` per il dettaglio completo."""
    params = {k: v for k, v in dict(regione=regione, fornitore=fornitore, citta=citta, tipo=tipo, durata_min=durata_min).items() if v}
    try:
        r = httpx.get(f"{_base_url()}/itinerari", headers=_headers(), params=params, timeout=10.0)
        if r.status_code != 200:
            return f"Errore dal server Laravel: {r.status_code} - {r.text[:200]}"
        data = r.json()
        return str(data) if data else "Nessun itinerario trovato con questi criteri."
    except Exception as e:
        return f"Errore di connessione all'API interna: {e}"


class LeggiItinerarioInput(BaseModel):
    id: int = Field(..., description="Id dell'itinerario restituito da cerca_itinerari.")


@tool("leggi_itinerario", args_schema=LeggiItinerarioInput)
def leggi_itinerario(id: int) -> str:
    """Legge il dettaglio completo di un itinerario (giorni, servizi, hotel, inclusioni, prezzi se presenti).
    Usalo come modello di struttura e di stile quando devi creare un nuovo itinerario."""
    try:
        r = httpx.get(f"{_base_url()}/itinerari/{id}", headers=_headers(), timeout=10.0)
        if r.status_code != 200:
            return f"Errore dal server Laravel: {r.status_code} - {r.text[:200]}"
        return str(r.json())
    except Exception as e:
        return f"Errore di connessione all'API interna: {e}"


class Servizio(BaseModel):
    tipo: TipoServizio
    titolo: str = Field(..., description="Titolo breve in inglese, es. 'Private transfer from Rome FCO Airport to hotel with Assistant'. Per Accommodation: nome dell'hotel.")
    opzione: Optional[int] = Field(None, description="Numero opzione se il servizio e' un'alternativa (Option 1, Option 2).")
    opzionale: bool = Field(False, description="True se il servizio e' opzionale (Optional).")
    descrizione: Optional[str] = Field(None, description="Descrizione in inglese, tono descrittivo come negli esempi.")
    partecipanti: Optional[int] = None
    durata: Optional[str] = Field(None, description="Es. '4 hours'.")
    notti: Optional[int] = Field(None, description="Solo per Accommodation.")
    inclusioni: Optional[List[str]] = None
    esclusioni: Optional[List[str]] = None
    prezzo_a_persona: Optional[float] = Field(None, description="Solo se noto: non inventare prezzi.")
    valuta: Optional[str] = Field(None, description="Codice a 3 lettere, es. USD o EUR.")
    fornitore: Optional[str] = Field(None, description="Nome ESATTO del fornitore trovato con cerca_fornitori; vuoto se non presente nel database.")


class Giorno(BaseModel):
    numero: int
    data: Optional[str] = Field(None, description="YYYY-MM-DD")
    citta: Optional[str] = Field(None, description="Citta' del giorno, in inglese (Rome, Florence, ...).")
    intro: Optional[str] = Field(None, description="2-3 frasi di benvenuto sulla citta', in inglese.")
    servizi: List[Servizio] = Field(default_factory=list)


class SalvaBozzaInput(BaseModel):
    titolo: str = Field(..., description="Titolo breve, es. 'Rome pre-cruise stay (24 pax)'.")
    type: Literal["trip", "wedding"]
    data_inizio: Optional[str] = Field(None, description="YYYY-MM-DD")
    data_fine: Optional[str] = Field(None, description="YYYY-MM-DD")
    persone: Optional[int] = None
    valuta: Optional[str] = Field("USD", description="Valuta dei prezzi, 3 lettere.")
    giorni: List[Giorno]


@tool("salva_bozza_itinerario", args_schema=SalvaBozzaInput)
def salva_bozza_itinerario(
    titolo: str,
    type: str,
    giorni: list,
    data_inizio: str = None,
    data_fine: str = None,
    persone: int = None,
    valuta: str = "USD",
) -> str:
    """Salva come BOZZA un nuovo itinerario nel gestionale. Chiamalo UNA sola volta, solo dopo che l'utente
    ha confermato esplicitamente la proposta. Il contenuto va scritto in inglese, con la stessa struttura degli esempi."""
    payload = {
        "titolo": titolo,
        "type": type,
        "data_inizio": data_inizio,
        "data_fine": data_fine,
        "persone": persone,
        "valuta": valuta,
        "giorni": [g if isinstance(g, dict) else g.model_dump() for g in giorni],
    }
    payload = _senza_none(payload)
    try:
        r = httpx.post(f"{_base_url()}/itinerari", headers=_headers(), json=payload, timeout=20.0)
        if r.status_code == 201:
            d = r.json()
            return f"Bozza salvata (id {d['id']}, stato {d['stato']}): '{d['titolo']}'. L'utente la trova nella sezione Itinerari."
        return f"Salvataggio non riuscito: {r.status_code} - {r.text[:400]}"
    except Exception as e:
        return f"Errore di connessione all'API interna: {e}"


def _senza_none(v):
    if isinstance(v, dict):
        return {k: _senza_none(x) for k, x in v.items() if x is not None}
    if isinstance(v, list):
        return [_senza_none(x) for x in v]
    return v
