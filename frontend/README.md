# Travel Agent – Frontend

Client web del progetto Travel Agent, realizzato con Laravel 11, Livewire 3,
Vite, Bootstrap e Tailwind. Contiene la chat con l'agente AI e un gestionale
per itinerari e fornitori. Le regioni restano disponibili come dati associati
agli itinerari, senza una pagina dedicata. Per la panoramica del progetto e
l'avvio completo vedi il [README generale](../README.md).

## Funzionalità

- **Chat** (`/`): componente Livewire [Chatbot](./app/Livewire/Chatbot.php) che
  invia la conversazione all'API FastAPI (`POST /chat/travel-agent/stream`) e
  mostra la risposta in streaming; le risposte sono rese in Markdown
  (`spatie/laravel-markdown`).
- **Itinerari** (`/itinerari`, `/itinerari/{id}`, `/itinerari/{id}/edit`):
  elenco con filtri (prezzo massimo, durata minima, regione), dettaglio,
  modifica ed eliminazione; ogni itinerario ha regioni, fornitori e voci.
- **Fornitori** (`/fornitori`): elenco gestito con Livewire. Le regioni si
  selezionano, filtrano e visualizzano all'interno degli itinerari.

Le route sono definite in [routes/web.php](./routes/web.php).

## Modello dati

Tabelle principali: `regioni`, `itinerari`, `fornitori`, `itinerario_voci` e le
tabelle pivot `itinerario_regione` e `itinerario_fornitore`. Migrazioni e seeder
sono in `database/`.

## Struttura

```text
app/
├── Http/Controllers/   # ChatController (vista della chat) e altri controller
├── Livewire/           # Chatbot, Itinerari*, Fornitori*
└── Models/             # Itinerario, ItinerarioVoce, Regione, Fornitore, User
resources/views/        # Viste Blade e viste dei componenti Livewire
routes/web.php          # Route dell'applicazione
database/               # Migrazioni, seeder e factory
```

## Requisiti

- PHP 8.2 o superiore e Composer.
- Node.js e npm.
- Un database raggiungibile, configurato in `.env`.
- API backend in esecuzione (vedi [README del backend](../backend/README.md)).

## Installazione

Dalla cartella `frontend`:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
```

Configurare in `.env` i parametri del database (`DB_*`) e l'indirizzo
dell'API:

```dotenv
FASTAPI_URL=http://127.0.0.1:8001
```

Quindi creare le tabelle ed eventualmente caricare i dati di esempio:

```powershell
php artisan migrate
php artisan db:seed
```

## Avvio

Per avviare frontend e backend insieme usare `start-dev.sh` dalla radice del
progetto (vedi il [README generale](../README.md)). Per avviare solo il
frontend:

```powershell
php artisan serve --host=127.0.0.1 --port=8000
npm run dev
```

Aprire `http://127.0.0.1:8000`. Il backend accetta richieste CORS dalle
origini `http://127.0.0.1:8000` e `http://localhost:8000`.

## Note

- Il timeout della chiamata all'API della chat è di 120 secondi.
- Le route non sono ancora protette da autenticazione.
- Evoluzioni previste (persistenza della chat, integrazione con il database,
  esportazione PDF, sicurezza): vedi la sezione "To Do" del
  [README generale](../README.md).
