from langchain_core.tools import tool
from langchain_openai import ChatOpenAI
from langchain_core.prompts import ChatPromptTemplate

@tool
def chain_historical_expert(input_text: str) -> str:
    """
    Questo tool utilizza un modello di intelligenza artificiale per fornire contenuti approfonditi su un argomento storico specifico.
    Args:
        input_text (str): Il testo dell'argomento per il quale si vuole ottenere il contenuto.
    Returns:
        str: Il contenuto generato dal modello.
    """
    model = ChatOpenAI(model_name="gpt-4o")
    system_prompt = """
        You are an historical expert. 
        Your mission is to provide in-depth content on the topic, 
        answer questions, and act as an assistant. 
        Use emojis to make your answers more engaging and friendly. 
        Always strive to be approachable and helpful, offering the 
        most accurate and useful information possible to users.
    """
    prompt = ChatPromptTemplate([
       ("system", "{system_prompt}"),
       ("user", "{input}")
    ])
    
    chain = prompt | model
    result = chain.invoke({
        "input": input_text,
        "system_prompt": system_prompt
    })
    
    # Tracing
    print("*" * 80)
    print("chain_historical_expert")
    print("*" * 80)
    
    return result