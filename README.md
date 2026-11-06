# Planner Helper

Planner Helper è un'applicazione per organizzare viaggi: unisce
una chat con assistente AI a un gestionale di itinerari e fornitori.
L'interfaccia è realizzata con Laravel e Livewire; l'agente usa FastAPI,
LangChain e OpenAI.

## Cosa permette di fare

- Conversare con l'assistente e conservare più chat, con titoli e chat fissate.
- Consultare itinerari per tipologia, regione e fornitore; modificarli, gestirne
  lo stato ed esportarli in PDF.
- Gestire fornitori e filtrarli per nome, ambito e regione.
- Chiedere al bot di cercare fornitori e itinerari del proprio archivio e di
  preparare una bozza di itinerario. Il salvataggio è previsto dopo conferma.
- Registrarsi con un codice d'invito: chat, itinerari e fornitori sono associati
  al singolo utente.

Il bot dispone di tre esempi strutturali condivisi, non
visualizzati nell'elenco degli itinerari.

## Organizzazione

| Cartella | Contenuto |
|---|---|
| [frontend](./frontend/README.md) | Applicazione Laravel/Livewire, database e PDF |
| [backend](./backend/README.md) | API FastAPI, agente e strumenti |
| [docs](./docs/README.md) | Note sulla documentazione e sugli esempi locali |

## Installazione locale

Servono PHP 8.2 o superiore, Composer, Node.js/npm, Python 3.10 o superiore,
Poetry e un database raggiungibile. Sono necessarie una chiave OpenAI e, per
la ricerca di voli e hotel, una chiave SerpApi. L'uso di questi servizi può
comportare costi.

I comandi seguenti sono per PowerShell, eseguiti nelle rispettive cartelle.
Se esiste già un file `.env`, modificarlo senza sovrascriverlo.

### 1. Backend

Da `backend`:

```powershell
poetry install
Copy-Item .env.example .env
```

In `backend\.env` impostare `OPENAI_API_KEY`, `SERPAPI_API_KEY` e un
`INTERNAL_API_TOKEN` lungo, casuale e riservato. Lasciare
`LARAVEL_API_URL=http://127.0.0.1:8002/api/internal` per l'avvio locale sotto.

### 2. Frontend e database

Da `frontend`:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
```

In `frontend\.env` configurare il database con le proprie impostazioni `DB_*`,
`FASTAPI_URL=http://127.0.0.1:8001`, lo stesso `INTERNAL_API_TOKEN` del backend
e un `REGISTRATION_INVITE_CODE` robusto, riservato e diverso dal token.
Il modello usa MySQL: creare prima il database indicato nella configurazione.

```powershell
php artisan migrate
```

Su un'installazione con dati esistenti, fare un backup prima di migrare. I
seeder sono facoltativi e caricano dati tratti dagli esempi:

```powershell
php artisan db:seed
```

Eseguirli **solo prima della prima registrazione**, dopo aver controllato i
dati: la prima registrazione assegna all'utente i dati preesistenti non
assegnati e successivamente i seeder vengono bloccati per proteggerli.
Senza seeder si parte con un archivio inizialmente vuoto.

## Avvio in sviluppo

Da Git Bash nella radice, `./start-dev.sh` avvia i servizi seguenti. Lo script
è pensato per Windows, richiede `backend\.venv313` e usa PHP da Laragon o dal
PATH; è possibile specificarlo con `PHP_BIN`. Arrestare con `Ctrl+C`.

| Servizio | Indirizzo locale |
|---|---|
| Laravel, interfaccia | `http://127.0.0.1:8000` |
| Laravel, API interna | `http://127.0.0.1:8002` |
| FastAPI | `http://127.0.0.1:8001` |
| Vite | indirizzo mostrato nel terminale |

Poetry può utilizzare un ambiente virtuale diverso da `.venv313`: in tal caso
usare l'avvio manuale descritto nei README specifici, oppure predisporre
quell'ambiente per lo script. La seconda istanza Laravel consente al bot di
chiamare l'API interna mentre la prima gestisce la chat.

Aprire l'interfaccia e registrare il primo utente con il codice d'invito.
La password deve avere almeno 12 caratteri. Non è previsto un recupero
automatico via email; custodire le credenziali in modo sicuro. Per impedire
nuove registrazioni, rimuovere il codice dalla configurazione; cambiarlo
invalida invece il codice precedente.
Dopo modifiche alla configurazione riavviare i servizi e, se necessario,
eseguire `php artisan config:clear` da `frontend`.

## Uso responsabile e sicurezza

- Controllare sempre disponibilità, prezzi e contenuti generati dal bot:
  l'AI può commettere errori e gli esempi non sono preventivi.
- Le richieste al bot e i dati utilizzati per rispondere possono essere inviati
  a servizi esterni; non inserire informazioni che non si è autorizzati a
  condividere.
- La configurazione qui descritta è **per sviluppo locale**. Mantenere FastAPI
  e l'API interna su reti private; per accesso remoto servono HTTPS e
  protezione del traffico tra servizi. Disattivare `APP_DEBUG` in produzione e
  configurare cookie sicuri.
- Conservare backup periodici del database fuori dal PC e provare il
  ripristino in un ambiente separato. Per i dettagli del database fare
  riferimento alla documentazione del motore utilizzato.

Per test e dettagli tecnici consultare i README del
[frontend](./frontend/README.md) e del [backend](./backend/README.md).
