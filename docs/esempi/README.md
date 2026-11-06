# Documenti di esempio

Questa cartella può contenere documenti locali di riferimento, ad esempio
itinerari o programmi di matrimonio in PDF. I documenti qui presenti sono
esclusi da Git dalle regole della cartella; questo README e `.gitignore` sono
invece versionati.

I dati estratti da alcuni esempi sono già presenti nel file tracciato
[`frontend/database/seeders/data/esempi.json`](../../frontend/database/seeders/data/esempi.json)
e possono essere caricati nel database con i seeder prima della prima
registrazione. Il prompt dell'agente contiene inoltre un esempio concreto.
L'esclusione dei documenti originali non protegge automaticamente i dati
derivati: prima del push controllare che sia autorizzata la pubblicazione di
nomi, prezzi, contatti e qualsiasi altra informazione presente.

Non inserire dati personali o riservati senza autorizzazione e tenere presente
che i contenuti usati nelle conversazioni possono essere inviati a servizi
esterni. Il bot dispone separatamente di modelli strutturali condivisi nel
codice, che non sono itinerari visibili nell'app.
