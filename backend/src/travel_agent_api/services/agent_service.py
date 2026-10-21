from datetime import datetime
from langchain_openai import ChatOpenAI
from langgraph.prebuilt import create_react_agent

# Tools
from travel_agent_api.tools.flights_finder import flights_finder
from travel_agent_api.tools.hotels_finder import hotels_finder
from travel_agent_api.tools.chain_historical_expert import chain_historical_expert
from travel_agent_api.tools.chain_travel_plan import chain_travel_plan

FLIGHTS_OUTPUT = """
format: markdown 
## Miglior Opzione 
### Andata:- Compagnia aerea: Ryanair- Data di partenza: 2024-12-13- Ora di partenza: 10:00- Durata del volo: 1h 30m
### Ritorno:- Compagnia aerea: Ryanair- Data di ritorno: 2024-12-19- Ora di ritorno: 14:30- Durata del volo: 1h 30m
Inserisci il link di Google per la prenotazione se possibile.
    #### Altri opzioni disponibili:
- Compagnia aerea: Ryanair
- Data di partenza: 2024-12-13
- Ora di partenza: 10:00
- Durata del volo: 1h 30m
- Compagnia aerea: Ryanair
- Data di ritorno: 2024-12-19
- Ora di ritorno: 14:30
- Durata del volo: 1h 30m
    ...
"""

HOTELS_OUTPUT = """
format: markdown 
    #### Hotel 1
    Inserisci la foto dell'hotel se disponibile. 
    *Descrizione:* Camere e suite eleganti, a volte con vista sulla città, in hotel esclusivo con piscina panoramica e spa.
    *Prezzo per notte:* €296 (prima delle tasse e spese: €260)
    *Prezzo totale:* €2,660 (prima delle tasse e spese: €2,336)
    *Check-in:* 15:00, Check-out: 12:00
    *Valutazione complessiva:* 4.5 su 5
    *Servizi Inclusi:* Spa, Piscina, Parcheggio gratuito
    #### Hotel 2
    Inserisci la foto dell'hotel se disponibile. 
    Descrizione: Hotel in stile Liberty con alloggi arredati in maniera artistica, ristorante elegante, bar e spa.
    *Prezzo per notte:* €380 (prima delle tasse e spese: €333)
    *Prezzo totale:* €3,418 (prima delle tasse e spese: €3,000)
    *Check-in:* 15:00, Check-out: 12:00
    *Valutazione complessiva:* 4.5 su 5
    *Servizi Inclusi:* Spa, Piscina, Parcheggio gratuito
"""

TRAVEL_PLAN_OUTPUT = """
format: markdown 
    ### Itinerario: 
    ### Giorno 1 - 2024-12-13:
    *Mattina:* Descrizione dell'attivita' da svolgere la mattina
    *Pomeriggio:* Descrizione dell'attivita' da svolgere il pomeriggio
    *Sera:* Descrizione dell'attivita' da svolgere la sera
    ### Giorno 2 - 2024-12-14:
    *Mattina:* Descrizione dell'attivita' da svolgere la mattina
    *Pomeriggio:* Descrizione dell'attivita' da svolgere il pomeriggio
    *Sera:* Descrizione dell'attivita' da svolgere la sera
    ...
"""

class Agent:
    def __init__(self):
        self.current_datetime = datetime.now()
        self.model = ChatOpenAI(model_name="gpt-4o")
        self.tools = [
            chain_historical_expert,
            flights_finder,
            hotels_finder,
            chain_travel_plan,
        ]
        self.agent_executor = create_react_agent(self.model, self.tools)
        
    def run(self, messages: list):
        SYSTEM_PROMPT = f"""
            Sei un travel planner. Il tuo compito e' organizzare il viaggio per l'utente. 
            Aggiungi delle emojis per rendere il tuo output piu' interessante.
            La data di oggi e' {self.current_datetime}
            Usa le seguenti istruzioni per creare un output:
            Esempio Ouput Voli: 
            {FLIGHTS_OUTPUT}
            Esempio Output Hotel:
            {HOTELS_OUTPUT}
            Esempio di Output Viaggio:
            {TRAVEL_PLAN_OUTPUT}
        """
        #Tracing
        print("*" * 80)
        print("Agent service - SYSTEM_PROMPT")
        print("*" * 80)

        conversation_history = [{"role": "system", "content": SYSTEM_PROMPT}] + messages
        response = self.agent_executor.invoke({"messages": conversation_history})
        return response["messages"][1:]