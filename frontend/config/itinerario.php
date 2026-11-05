<?php

// Parti fisse del documento (identiche in tutti gli itinerari d'esempio).
return [
    'inclusions' => [
        'Daily breakfast in all hotel Accommodations.',
        'Complimentary Wi-Fi.',
        'Complimentary upgrade, early check-in and/or late check-out, subject to availability.',
        'All of your drivers and guides have been hand selected and are fully screened to ensure the right fit and a high level of service.',
    ],

    'important_notes' => [
        'Entrance tickets for Italian sites are released by the Ministry of Culture approximately one month prior to the service date and are subject to limited availability. We will notify you as soon as ticket sales open and confirm arrangements accordingly, offering alternative solutions if needed.',
        'Tours and experiences are subject to date and time availability.',
        'Your private transfer can accommodate up to 1 check-in luggage and 1 carry-on per person. For additional luggage, a larger vehicle should be requested. Additional charges may apply.',
        'A night supplement may apply for transfers provided between 9pm and 7am.',
        'Room configurations - including layout and furnishings - may vary within the same category. Images are for reference only and may depict shared or public spaces rather than the specific room assigned.',
        'Some destinations impose a government-mandated Accommodation Tax, which is payable locally. This tax is excluded from the itinerary.',
        'An overnight stay tax may be applied and collected by the hotel upon check-out. This tax is excluded from the itinerary and is subject to local regulations.',
    ],

    'italy_facts' => [
        'Capital' => 'Rome',
        'Total area' => '302,073 km²',
        'Population (2023 est.)' => '58.99 million',
        'Official language' => 'Italian',
        'Currency' => 'Euro (€) (EUR)',
    ],

    'contact_text' => 'Our dedicated Support team is available to assist you with any needs that may arise during your trip. Additionally, we provide comprehensive assistance with pre-travel inquiries to ensure your journey is smooth from start to finish. Whether you need recommendations for dining at your destination or have questions before your departure, our team is here to help you every step of the way.',

    'contact_email' => env('ITINERARY_CONTACT_EMAIL', ''),
];
