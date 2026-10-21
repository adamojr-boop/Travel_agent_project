# Travel Agent

Applicazione per l'organizzazione di viaggi tramite una chat: il client web è
realizzato con Laravel e comunica con un'API Python basata su FastAPI e
LangChain.

## Struttura del progetto

```text
.
├── backend/   # API FastAPI, agente LangChain e strumenti di viaggio
└── frontend/  # Client web Laravel e interfaccia chat Livewire
```
## Funzionalità principali

- Chat per richiedere assistenza nella pianificazione di un viaggio.
- Agente LangChain con strumenti per creare itinerari, cercare voli e hotel e
  fornire informazioni storiche.
- Interfaccia Laravel/Livewire che invia i messaggi all'API.

Le ricerche di voli e hotel richiedono una chiave SerpApi; le risposte
dell'agente e la generazione dei contenuti richiedono una chiave OpenAI.

## Avvio rapido

Avviare i componenti in terminali separati.

### 1. API Python

Seguire i passaggi completi nel [README del backend](./backend/README.md). In
sintesi, dalla cartella `backend`:

```powershell
poetry install
Copy-Item .env.example .env
# Inserire le chiavi API in backend\.env
poetry run uvicorn travel_agent_api.main:app --reload --port 8001
```

L'API sarà disponibile su `http://127.0.0.1:8001`; la documentazione interattiva
di FastAPI sarà su `http://127.0.0.1:8001/docs`.

### 2. Client Laravel

Da un altro terminale, dalla cartella `frontend`:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
```

Configurare il database nel file `frontend\.env` se richiesto dalla propria
installazione, quindi impostare anche l'indirizzo dell'API:

```dotenv
FASTAPI_URL=http://127.0.0.1:8001
```

Avviare il client e gli asset:

```powershell
php artisan serve --port=8000
npm run dev
```

Aprire `http://127.0.0.1:8000`. Il backend consente richieste CORS dal client
agli indirizzi locali `localhost:8000` e `127.0.0.1:8000`.

## Configurazione

Le chiavi per i servizi esterni vanno inserite in `backend\.env`, copiando il
modello da `backend\.env.example`. Non condividere né versionare file `.env`
contenenti credenziali.