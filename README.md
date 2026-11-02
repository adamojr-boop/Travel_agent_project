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
- Interfaccia Laravel/Livewire che invia i messaggi all'API, con risposta in
  streaming.
- Gestionale (Livewire + database Laravel) per itinerari, regioni e fornitori, con filtri
  e collegamenti tra le entità.

Le ricerche di voli e hotel richiedono una chiave SerpApi; le risposte
dell'agente e la generazione dei contenuti richiedono una chiave OpenAI.

## Avvio rapido con `start-dev.sh`

Lo script [start-dev.sh](./start-dev.sh) avvia in un solo terminale tutti i
componenti di sviluppo:

| Servizio | Comando eseguito | Indirizzo |
|---|---|---|
| Laravel | `php artisan serve` | `http://127.0.0.1:8000` |
| Vite (asset) | `npm run dev` | gestito da Vite |
| API FastAPI | `uvicorn travel_agent_api.main:app --reload` | `http://127.0.0.1:8001` |

**Prerequisiti (una tantum)**

- PHP 8.2+, Composer, Node.js/npm e un database raggiungibile (vedi
  `frontend\.env`).
- Ambiente virtuale Python in `backend\.venv313` con le dipendenze installate
  (lo script usa direttamente il suo `python.exe`, senza Poetry).
- File `backend\.env` (chiavi OpenAI e SerpApi) e `frontend\.env` con
  `FASTAPI_URL=http://127.0.0.1:8001`.
- Dipendenze frontend installate (`composer install`, `npm install`) e
  migrazioni eseguite (`php artisan migrate`).

**Avvio**

Da **Git Bash**, nella cartella principale del progetto:

```bash
./start-dev.sh
```

Poi aprire `http://127.0.0.1:8000`. Premere `Ctrl+C` per fermare tutti i
servizi. Se l'ambiente virtuale non viene trovato, lo script termina con un
messaggio di errore.

## Avvio manuale

In alternativa, avviare i componenti in terminali separati.

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

## To Do – Roadmap per le prossime fasi

### 1. Integrazione tra Bot e Database (Laravel ⇄ Python)

- **Obiettivo**: abbattere il muro che oggi separa la chat dal gestionale.
- **Cosa fare**: permettere all'agente Python di interrogare i fornitori reali e
  gli itinerari esistenti, e consentire alla chat di creare e salvare bozze di
  itinerario direttamente nel database di Laravel con un semplice comando.

### 2. Ottimizzazione del backend Python (performance e costi)

- **Obiettivo**: rendere il motore veloce e leggero.
- **Cosa fare**:
  - trasformare l'istanza dell'agente in un **singleton**, evitando di
    ricrearlo a ogni chiamata;
  - filtrare e **alleggerire i JSON restituiti da SerpApi** (voli e hotel)
    mantenendo solo i dati essenziali (prezzi, compagnie, link), per ridurre il
    consumo di token e velocizzare le risposte.

### 3. Persistenza della chat

- **Obiettivo**: non perdere la cronologia dei messaggi ricaricando la pagina.
- **Cosa fare**: creare le tabelle per conversazioni e messaggi, associando un
  `thread_id` per mantenere il contesto delle richieste della travel designer.

### 4. Sicurezza e rate limiting

- **Obiettivo**: proteggere le API da consumi anomali di OpenAI e SerpApi.
- **Cosa fare**: introdurre un token di autenticazione condiviso tra Laravel e
  FastAPI, validare i dati in ingresso e impostare un limite di
  messaggi/richieste.

### 5. Esportazione PDF definitiva

- **Obiettivo**: trasformare il pulsante "Genera PDF" da placeholder a
  funzionalità reale.
- **Cosa fare**: una volta ricevuti i file di esempio, realizzare il generatore
  di documenti ufficiali pronti per essere inviati ai clienti.

## Configurazione

Le chiavi per i servizi esterni vanno inserite in `backend\.env`, copiando il
modello da `backend\.env.example`. Non condividere né versionare file `.env`
contenenti credenziali.