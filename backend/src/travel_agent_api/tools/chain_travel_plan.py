from langchain_core.tools import tool
from langchain_openai import ChatOpenAI
from pydantic import BaseModel, Field
from typing import Optional

class TravelPlanInput(BaseModel):
    start_date: str = Field(description="The start date of the trip (YYYY-MM-DD) e.g. 2024-12-13.")
    end_date: str = Field(description="The end date of the trip (YYYY-MM-DD) e.g. 2024-12-19.")
    destination: str = Field(description="The destination of the trip.")
    adults: Optional[int] = Field(1, description="The number of adults. Defaults to 1.")
    children: Optional[int] = Field(0, description="The number of children. Defaults to 0.")
    travel_style: str = Field(description="The style of travel. e.g. adventure, relax, culture, backpacking, luxury, family-friendly.")
    budget: Optional[int] = Field(description="The total budget for the trip.")
    activities: str = Field(description="The preferred activities. e.g. culture, nature, food, shopping.")
    food_restriction: str = Field(description="Any food restrictions. e.g. vegetarian, gluten-free.")

class TravelPlanInputSchema(BaseModel):
    params: TravelPlanInput

class TravelDayOutput(BaseModel):
    morning: str = Field(description="The activities for the morning.")
    afternoon: str = Field(description="The activities for the afternoon.")
    evening: str = Field(description="The activities for the evening.")

class TravelPlanOutput(BaseModel):
    travel_plan: list[TravelDayOutput]

@tool(args_schema=TravelPlanInputSchema)
def chain_travel_plan(params: TravelPlanInput) -> TravelPlanOutput:
    """
    Generates a comprehensive travel plan based on user input parameters.
    Parameters:
        params (TravelPlanInput): The input parameters for the travel plan including dates, destination, number of travelers, travel style, budget, preferred activities, and any food restrictions.
    Returns:
        TravelPlanOutput: The generated travel plan content.
    """
    # Usiamo il modello nativo con supporto alla struttura Pydantic
    model = ChatOpenAI(model_name="gpt-4o")
    structured_model = model.with_structured_output(TravelPlanOutput)
    
    system_prompt = f"""
        You are a travel expert. 
        Your mission is to provide in-depth content on the topic to create a travel plan.
        The start date of the trip is {params.start_date}. 
        The end date of the trip is {params.end_date}.
        The destination of the trip is {params.destination}.
        The number of adults is {params.adults}.
        The number of children is {params.children}.
        The style of travel is {params.travel_style}.
        The total budget for the trip is {params.budget}.
        The preferred activities are {params.activities}.
        Any food restrictions are {params.food_restriction}
        Use emojis to make your answers more engaging and friendly. 
        Always strive to be approachable and helpful, offering the most accurate and useful information possible to users.
    """
    
    # Invettiamo direttamente il prompt nel modello strutturato
    result = structured_model.invoke(system_prompt)
    
    # Tracing
    print("*" * 80)
    print("chain_travel_plan (Structured Output Success)")
    print("*" * 80)
    
    return result