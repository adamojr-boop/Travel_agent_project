import os
import httpx
from langchain.tools import tool
from pydantic import BaseModel, Field

class CercaFornitoriInput(BaseModel):
    ambito: str = Field(None, description="Filtra per ambito del fornitore: Alloggi (hotel, B&B), Mezzi (trasporti, transfer, noleggio) o Servizi (guide, altri servizi)")

@tool("cerca_fornitori", args_schema=CercaFornitoriInput)
def cerca_fornitori(ambito: str = None) -> str:
    """Cerca i fornitori reali disponibili nel database del gestionale Laravel, filtrando per ambito (Alloggi, Mezzi, Servizi)."""
    url = f"{os.getenv('LARAVEL_API_URL', 'http://127.0.0.1:8002/api/internal')}/fornitori"
    token = os.getenv('INTERNAL_API_TOKEN')
    
    headers = {
        "X-Internal-Token": token,
        "Accept": "application/json"
    }
    
    params = {}
    if ambito:
        params["ambito"] = ambito
        
    try:
        response = httpx.get(url, headers=headers, params=params, timeout=10.0)
        if response.status_code == 200:
            fornitori = response.json()
            if not fornitori:
                return "Nessun fornitore trovato con questi criteri."
            return str(fornitori)
        else:
            return f"Errore dal server Laravel: {response.status_code} - {response.text[:200]}"
    except Exception as e:
        return f"Errore di connessione all'API interna ({url}): {str(e)}"