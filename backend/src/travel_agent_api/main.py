from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from travel_agent_api.routes import chat_router

# Creazione dell'istanza principale dell'applicazione FastAPI
app = FastAPI()

# Configurazione CORS (Cross-Origin Resource Sharing)
origins = [
    "http://127.0.0.1:8000",  # Porta standard dell'applicazione front-end Laravel
    "http://localhost:8000"    # Alias per localhost e porta standard
]

app.add_middleware(
    CORSMiddleware,
    allow_origins=origins,      # Domini permessi
    allow_credentials=True,     # Permette l'invio di credenziali
    allow_methods=["*"],        # Permette tutti i metodi HTTP
    allow_headers=["*"],        # Permette tutti gli headers
)

# Inclusione del Router della chat con prefisso /chat
app.include_router(
    chat_router.router,   
    tags=["Chat"],        
    prefix="/chat"        
)