# Planner Helper — Frontend

Applicazione web Laravel 11 e Livewire 3: gestisce accesso, chat, itinerari,
fornitori e PDF. Per installazione completa e configurazione condivisa vedi il
[README generale](../README.md).

## Funzioni

- **Accesso:** registrazione con nome utente, password e codice d'invito;
  login e logout. Non è disponibile il recupero automatico della password.
- **Chat:** conversazioni persistenti, titoli modificabili, cronologia con
  chat fissate e risposte dell'agente in streaming, rese in Markdown.
- **Itinerari:** elenco filtrabile per tipologia, regione e fornitore,
  dettaglio, modifica, eliminazione, stato bozza/confermato ed esportazione
  PDF con dompdf.
- **Fornitori:** inserimento ed eliminazione, ricerca e filtri per ambito e
  regione. Le regioni sono dati di supporto, non una vista separata.

Chat, itinerari e fornitori appartengono all'utente autenticato. I dati
preesistenti senza proprietario vengono assegnati al primo utente registrato.
Il bot accede al database tramite le API interne, non direttamente.

## Struttura

```text
app/Livewire/          Componenti delle pagine e della cronologia chat
app/Http/              Controller e middleware
app/Models/            Modelli delle conversazioni e del gestionale
app/Support/           Identità dell'agente e logica di supporto
resources/views/       Viste Blade, autenticazione e PDF
resources/css/         Stili dell'interfaccia
routes/                Route web e API interne
database/              Migrazioni, seeder e dati iniziali
tests/Feature/         Test applicativi
```

## Installazione e dati iniziali

Servono PHP 8.2+, Composer, Node.js/npm e un database. Da `frontend`:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
```

Se `.env` esiste già, non sovrascriverlo. Configurare il database, `FASTAPI_URL`,
`REGISTRATION_INVITE_CODE` e `INTERNAL_API_TOKEN` come indicato nel
[README generale](../README.md). Il token interno deve essere identico nel
backend; il codice d'invito deve essere diverso.

```powershell
php artisan migrate
```

## Avvio manuale

In terminali separati dalla cartella `frontend`:

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

```powershell
php artisan serve --host=127.0.0.1 --port=8002
```

```powershell
npm run dev
```

Avviare anche FastAPI come descritto nel [README backend](../backend/README.md)
e aprire `http://127.0.0.1:8000`. La porta 8002 è usata dal backend per le
API interne quando il server di sviluppo sulla 8000 è occupato dalla chat.
Per generare gli asset senza il server Vite usare `npm run build`.
Se PowerShell blocca `npm.ps1` su Windows, usare `npm.cmd` negli stessi comandi.

## Verifiche

Da `frontend`:

```powershell
php artisan test
npm run build
```

I test applicativi configurati dal progetto usano SQLite in memoria; non
eseguire test puntando a un database con dati reali.
