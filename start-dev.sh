#!/usr/bin/env bash
# Avvia frontend (Laravel + Vite) e backend (FastAPI) nello stesso terminale.
# Uso (da Git Bash): ./start-dev.sh   -  Ctrl+C ferma tutto.

ROOT="$(cd "$(dirname "$0")" && pwd)"

# Python dell'ambiente virtuale del progetto (3.13): evita poetry.exe (bloccato)
VENV_PY="$ROOT/backend/.venv313/Scripts/python.exe"
if [ ! -f "$VENV_PY" ]; then
  echo "Ambiente virtuale non trovato: $VENV_PY"
  exit 1
fi

# PHP: usa PHP_BIN se impostato, altrimenti il PHP di Laragon (il php.exe in
# C:\php\8.4 e' bloccato da Windows), altrimenti quello nel PATH
if [ -z "$PHP_BIN" ]; then
  PHP_BIN="$(ls -d /c/laragon/bin/php/php-*/php.exe 2>/dev/null | sort -V | tail -n 1)"
  PHP_BIN="${PHP_BIN:-php}"
fi

trap 'kill 0' EXIT INT TERM

(cd "$ROOT/frontend" && "$PHP_BIN" artisan serve --host=127.0.0.1 --port=8000) &
# Secondo server Laravel solo per l'API interna: il server di sviluppo e' a un
# solo processo, quindi la chat (porta 8000) bloccherebbe le chiamate dell'agente
(cd "$ROOT/frontend" && "$PHP_BIN" artisan serve --host=127.0.0.1 --port=8002) &
(cd "$ROOT/frontend" && npm run dev -- --host 127.0.0.1) &
(cd "$ROOT/backend" && "$VENV_PY" -m uvicorn travel_agent_api.main:app --app-dir src --reload --port 8001) &

wait
