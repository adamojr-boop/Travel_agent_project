from datetime import datetime
from langchain_openai import ChatOpenAI
from langgraph.prebuilt import create_react_agent

# Tools
from travel_agent_api.tools.flights_finder import flights_finder
from travel_agent_api.tools.hotels_finder import hotels_finder
from travel_agent_api.tools.chain_historical_expert import chain_historical_expert
from travel_agent_api.tools.chain_travel_plan import chain_travel_plan
from travel_agent_api.tools.fornitori_tool import cerca_fornitori
from travel_agent_api.tools.itinerari_tool import cerca_itinerari, leggi_itinerario, salva_bozza_itinerario
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

ITINERARY_EXAMPLE = """
Esempio reale di struttura (da un itinerario del gestionale, testi in inglese):
Title: Rome pre-cruise stay (24 pax) | type: trip | 2027-05-14 -> 2027-05-16 | 2 nights / 3 days
Day 1 - Friday, May 14 - Rome
  intro: "Every stone tells a story. Rome stuns with ancient ruins, opulent fountains, and narrow streets buzzing with life."
  - Private Transfer: "Private Group transfer from Airport Rome FCO to Hotel in Rome with Assistant" (24 participants)
  - Accommodation: "UNA Hotels Decò Roma" (2 nights, fornitore: UNA Hotels Decò Roma)
  - Private Tour with Vehicle [optional]: "Private evening tour of Rome by Golfcart" (2 hours, $250 per person,
    inclusions: private driver escort, stops at major landmarks; exclusions: personal expenses, gratuities)
Day 2 - Saturday, May 15 - Rome
  - Private Tour with Vehicle [Option 1]: "Full day Rome walking tour with Colosseum and Roman Forum" (8 hours, $340 per person)
  - Private Tour with Vehicle [Option 2]: "Half day Colosseum and Roman Forum" (4 hours, $248 per person)
Day 3 - Sunday, May 16
  - Private Transfer: "Private Group transfer from Hotel in Rome to Civitavecchia Pier with Assistant"
Le sezioni fisse del PDF (Inclusions, Important notes, Italy facts, Concierge Services, contatti) le aggiunge il template: NON ripeterle.
"""

class Agent:
    def __init__(self):
        self.current_datetime = datetime.now()
        self.model = ChatOpenAI(model_name="gpt-4o", streaming=True)
        self.tools = [
            chain_historical_expert,
            flights_finder,
            hotels_finder,
            chain_travel_plan,
            cerca_fornitori,
            cerca_itinerari,
            leggi_itinerario,
            salva_bozza_itinerario,
        ]
        self.agent_executor = create_react_agent(self.model, self.tools)
        
    def stream(self, messages: list):
        """Genera il testo della risposta finale dell'agente a pezzi."""
        conversation_history = self._build_history(messages)
        for chunk, meta in self.agent_executor.stream(
            {"messages": conversation_history}, stream_mode="messages"
        ):
            # Ignora i messaggi dei tool e le chiamate ai tool (senza testo)
            if meta.get("langgraph_node") == "agent" and isinstance(chunk.content, str) and chunk.content:
                yield chunk.content

    def _build_history(self, messages: list):
        SYSTEM_PROMPT = f"""
            Sei un travel planner. Il tuo compito e' organizzare il viaggio per l'utente. 
            Aggiungi delle emojis per rendere il tuo output piu' interessante.
            La data di oggi e' {self.current_datetime}
            Hai a disposizione il tool `cerca_fornitori` per interrogare il database dei fornitori reali di viaggio.
            Usalo ogni volta che l'utente chiede informazioni su hotel, guide, transfer o fornitori specifici
            (ambiti: Alloggi, Mezzi, Servizi),
            senza inventare i dati: se non trova risultati, dillo chiaramente.

            CREAZIONE ITINERARI (viaggi e matrimoni in Italia):
            1. Raccogli, a piccoli passi e senza fare troppe domande insieme, i dati necessari: tipo (trip o wedding),
               date, numero di persone, citta'/regioni da toccare, esigenze (hotel, transfer, tour, guide, eventi), budget se noto.
            2. Usa `cerca_itinerari` (filtri: regione, fornitore, citta', tipo, durata) e `leggi_itinerario` per prendere
               come modello itinerari reali simili; usa `cerca_fornitori` per scegliere hotel reali del database
               e usa il nome ESATTO nel campo fornitore.
            3. Proponi all'utente l'itinerario giorno per giorno. Il contenuto dell'itinerario (titoli, descrizioni,
               inclusioni) va scritto in INGLESE, nello stesso stile e con gli stessi tipi di servizio degli esempi
               (Private Transfer, Private Tour with Vehicle, Private Guide Service, Private Transportation Service,
               Group Guide Service, Accommodation, Assistance). Mostra i prezzi solo se noti: non inventarli.
            4. Solo dopo la conferma esplicita dell'utente chiama UNA volta `salva_bozza_itinerario`; poi riassumi
               cosa e' stato salvato. Se il salvataggio fallisce, riporta l'errore senza ritentare all'infinito.
            {ITINERARY_EXAMPLE}
            Usa le seguenti istruzioni per creare un output:
            Esempio Ouput Voli: 
            {FLIGHTS_OUTPUT}
            Esempio Output Hotel:
            {HOTELS_OUTPUT}
        """
        #Tracing
        print("*" * 80)
        print("Agent service - SYSTEM_PROMPT")
        print("*" * 80)

        return [{"role": "system", "content": SYSTEM_PROMPT}] + messages

    def run(self, messages: list):
        conversation_history = self._build_history(messages)
        response = self.agent_executor.invoke({"messages": conversation_history})
        return response["messages"][1:]