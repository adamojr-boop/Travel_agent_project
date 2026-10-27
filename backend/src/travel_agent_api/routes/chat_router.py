from fastapi import APIRouter
from fastapi.responses import StreamingResponse
from pydantic import BaseModel  
from travel_agent_api.services.agent_service import Agent  

# Creazione di un nuovo router FastAPI
router = APIRouter()

# Definizione del modello di dati per la richiesta di chat
class ChatComplentionRequest(BaseModel):
    messages: list
    
    model_config = {
        "json_schema_extra": {
            "example": { 
                "messages": [
                    {
                        "role": "user",  
                        "content": "Vorrei organizzare un viaggio a Roma"  
                    }
                ]
            }
        }
    }

@router.post("/travel-agent/stream")
def chat_completion_stream(request: ChatComplentionRequest):
    """Restituisce la risposta dell'agente in streaming (testo semplice)."""
    def generate():
        try:
            yield from Agent().stream(messages=request.messages)
        except Exception as e:
            yield f"⚠️ Si è verificato un errore nel backend dell'agente: {e}"

    return StreamingResponse(
        generate(),
        media_type="text/plain; charset=utf-8",
        headers={"Cache-Control": "no-cache", "X-Accel-Buffering": "no"},
    )

@router.post("/travel-agent")  
def chat_completion(request: ChatComplentionRequest):
    """
    Endpoint per la gestione delle richieste di chat.
    Processa i messaggi ricevuti e restituisce la conversazione aggiornata.
    """
    try:
        # Creazione di una nuova istanza dell'agente
        agent = Agent()
        
        # Elaborazione dei messaggi con l'agente
        response = agent.run(messages=request.messages)
        
        # Partiamo dalla lista di messaggi ricevuti dalla richiesta
        updated_messages = list(request.messages)
        
        # Estraiamo il contenuto testuale in modo sicuro (gestendo oggetti LangChain, dizionari o stringhe)
        if hasattr(response, "content"):
            ai_content = response.content
        elif isinstance(response, dict):
            content_val = response.get("content", response.get("output", response))
            ai_content = getattr(content_val, "content", content_val)
        elif isinstance(response, list) and response:
            last_item = response[-1]
            ai_content = getattr(last_item, "content", last_item.get("content", str(last_item)) if isinstance(last_item, dict) else str(last_item))
        elif isinstance(response, str):
            ai_content = response
        else:
            ai_content = str(getattr(response, "content", response))

        if not isinstance(ai_content, str):
            ai_content = str(ai_content)
            
        # Aggiungiamo la risposta dell'IA alla lista dei messaggi
        updated_messages.append({
            "type": "ai",
            "content": ai_content
        })

        #tracing
        print("*" * 80)
        print("Chat Router - Updated Messages")
        print(updated_messages)
        print("*" * 80)
        
        return updated_messages

    except Exception as e:
        # Se c'è un errore (es. OutputParserException di LangChain), 
        # restituiamo comunque un messaggio di errore leggibile nella chat anziché far crashare tutto
        error_messages = list(request.messages)
        error_messages.append({
            "type": "ai",
            "content": f"⚠️ Si è verificato un errore nel backend dell'agente: {str(e)}"
        })
        return error_messages