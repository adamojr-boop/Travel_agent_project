# Travel Agent API

Backend Python del progetto Travel Agent. Espone un'API REST con FastAPI e usa
LangChain/LangGraph e OpenAI per gestire le richieste di pianificazione di
viaggio. Il client Laravel è opzionale e descritto nel
[README generale](../README.md).

## Funzionalità

- Endpoint `POST /chat/travel-agent` per inviare una conversazione e ricevere
  i messaggi aggiornati con la risposta dell'agente.
- Agente creato con LangGraph e modello OpenAI `gpt-4o`.
- Strumenti LangChain per:
  - generare un itinerario strutturato;
  - cercare voli con Google Flights tramite SerpApi;
  - cercare hotel tramite SerpApi;
  - rispondere a domande di carattere storico.
- Endpoint di documentazione generati da FastAPI: `/docs` e `/redoc`.

## Requisiti

- Python 3.10 o superiore.
- Poetry.
- Una chiave API OpenAI.
- Una chiave API SerpApi per le ricerche di voli e hotel.

Le dipendenze Python sono dichiarate in `pyproject.toml` e bloccate in
`poetry.lock`.

## Installazione su Windows

Aprire PowerShell nella cartella `backend` ed eseguire:

```powershell
poetry install
Copy-Item .env.example .env
```

Modificare `backend\.env` e inserire le proprie chiavi:

```dotenv
OPENAI_API_KEY=la-tua-chiave-openai
SERPAPI_API_KEY=la-tua-chiave-serpapi
```

Non inserire chiavi reali nel codice o nei file condivisi e non pubblicare il
file `.env`.

## Avvio

Dalla cartella `backend`, avviare il server di sviluppo:

```powershell
poetry run uvicorn travel_agent_api.main:app --reload --port 8001
```

Indirizzi locali:

- API: `http://127.0.0.1:8001`
- Documentazione Swagger: `http://127.0.0.1:8001/docs`
- Documentazione ReDoc: `http://127.0.0.1:8001/redoc`

Il client Laravel è configurato per chiamare l'API tramite `FASTAPI_URL`;
impostare nel file `frontend\.env`:

```dotenv
FASTAPI_URL=http://127.0.0.1:8001
```

Per usare il client locale con la configurazione CORS attuale, servirlo sulla
porta `8000` (`http://127.0.0.1:8000` o `http://localhost:8000`).

## Endpoint chat

`POST /chat/travel-agent`

Esempio di richiesta:

```json
{
  "messages": [
    {
      "type": "human",
      "content": "Vorrei organizzare un viaggio a Roma."
    }
  ]
}
```

La risposta è una lista di messaggi: i messaggi inviati nella richiesta e la
risposta generata dall'agente. Per provare l'endpoint, è possibile usare la
pagina `/docs` dopo aver avviato il server.

## Struttura del codice

```text
src/travel_agent_api/
├── main.py                  # App FastAPI, CORS e registrazione del router
├── routes/chat_router.py    # Endpoint della chat e modello della richiesta
├── services/agent_service.py # Configurazione ed esecuzione dell'agente
└── tools/
    ├── chain_travel_plan.py
    ├── chain_historical_expert.py
    ├── flights_finder.py
    └── hotels_finder.py
```

Le funzioni degli strumenti sono registrate come tool LangChain e vengono
selezionate dall'agente in base alla richiesta dell'utente.

## Tracing e verifiche

Sono presenti istruzioni `print` nei principali passaggi del router, dell'agente
e degli strumenti, utili per seguire l'esecuzione durante lo sviluppo. I log
appaiono nel terminale in cui è avviato Uvicorn. Evitare di stampare o
condividere chiavi API e altri dati sensibili.

La cartella `tests` al momento non contiene test applicativi: gli endpoint
possono essere provati manualmente tramite `/docs`.

## Note

- Le ricerche esterne dipendono dalla disponibilità e dalla configurazione di
  SerpApi.
- Le risposte dell'agente dipendono dalla disponibilità e dalla configurazione
  di OpenAI.
- Il CORS è impostato per gli indirizzi locali del client Laravel sulla porta
  `8000`; aggiornare la configurazione in `src/travel_agent_api/main.py` se il
  client viene servito da un'origine diversa.