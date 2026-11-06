# Planner Helper — Backend

API Python con FastAPI, LangChain/LangGraph e OpenAI per l'assistente di
Planner Helper. Per la configurazione dell'intero progetto vedi il
[README generale](../README.md).

## Funzioni dell'agente

L'agente usa il modello `gpt-4o` e può:

- rispondere in chat;
- consultare fornitori e itinerari dell'utente attraverso l'API interna Laravel;
- proporre itinerari e salvarli come bozze dopo conferma dell'utente;
- consultare tre esempi strutturali condivisi di viaggi e matrimoni;
- utilizzare strumenti per ricerche di voli e hotel tramite SerpApi e per
  informazioni storiche e pianificazione.

La selezione degli strumenti dipende dall'utente. Verificare sempre risultati,
prezzi e disponibilità. Il prompt contiene anche un esempio concreto:
controllarne il contenuto prima di pubblicare o usare il progetto.

## Installazione

Servono Python 3.10 o superiore, Poetry e chiavi API OpenAI e SerpApi.
Da `backend`, in PowerShell:

```powershell
poetry install
Copy-Item .env.example .env
```

Se `.env` esiste già, non sovrascriverlo. Configurare:

| Variabile | Scopo |
|---|---|
| `OPENAI_API_KEY` | Accesso al modello OpenAI |
| `SERPAPI_API_KEY` | Ricerche di voli e hotel |
| `INTERNAL_API_TOKEN` | Segreto condiviso con Laravel, identico su entrambi i servizi |
| `LARAVEL_API_URL` | URL dell'API interna, localmente `http://127.0.0.1:8002/api/internal` |

Non versionare le credenziali. Il codice d'invito è configurato solo nel
frontend e deve essere distinto dal token interno.

## Avvio

Da `backend`:

```powershell
poetry run uvicorn travel_agent_api.main:app --host 127.0.0.1 --port 8001 --reload
```

Avviare anche Laravel e Vite seguendo il
[README frontend](../frontend/README.md). Lo script
[`start-dev.sh`](../start-dev.sh) avvia tutti i servizi da Git Bash, ma richiede
lo specifico ambiente `backend/.venv313`.

FastAPI espone la documentazione locale su `http://127.0.0.1:8001/docs`.
Gli endpoint di chat richiedono l'header `X-Agent-Identity` firmato da Laravel:
non è sufficiente inviare soltanto il corpo della richiesta tramite `/docs`.

## Integrazione

- `POST /chat/travel-agent/stream`: risposta testuale in streaming, usata
  dall'interfaccia.
- `POST /chat/travel-agent`: restituisce i messaggi con la risposta aggiunta.
- Le chiamate dal bot all'API interna Laravel includono un token condiviso e
  l'identità firmata dell'utente; il gestionale limita i dati a quell'utente.

In caso di errore durante la generazione, il backend può restituire un
messaggio di errore nel contenuto della risposta. I servizi interni non devono
essere esposti pubblicamente; la configurazione di sviluppo non è una guida
completa al deployment.

## Struttura e test

```text
src/travel_agent_api/
├── main.py                 App FastAPI
├── agent_identity.py       Verifica dell'identità e header interni
├── routes/                 Endpoint chat
├── services/               Configurazione dell'agente
└── tools/                  Strumenti per ricerca, esempi e itinerari
tests/                      Test dell'identità e degli esempi condivisi
```

Da `backend`:

```powershell
poetry run python -m unittest discover -s tests
```

I test presenti usano mock per i servizi esterni; non sostituiscono una prova
reale della chat con le chiavi configurate.
