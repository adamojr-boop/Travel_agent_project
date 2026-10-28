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

trap 'kill 0' EXIT INT TERM

(cd "$ROOT/frontend" && php artisan serve --host=127.0.0.1 --port=8000) &
(cd "$ROOT/frontend" && npm run dev -- --host 127.0.0.1) &
(cd "$ROOT/backend" && "$VENV_PY" -m uvicorn travel_agent_api.main:app --app-dir src --reload --port 8001) &

wait
