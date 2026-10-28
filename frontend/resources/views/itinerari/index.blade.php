<x-layout>
    <div class="container-fluid">
        <div class="card ragsy-card p-4 d-flex flex-column gap-3">
            
            <!-- Intestazione della sezione -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-2">
                <div>
                    <h2 class="mb-1" style="color: var(--text-dark);">Gestione Itinerari</h2>
                    <p class="mb-0" style="color: var(--text-dark);">Cerca, visualizza e gestisci i tuoi itinerari di viaggio salvati.</p>
                </div>
                <button class="btn rounded-3" style="background-color: var(--accent-color); border: 1px solid var(--lilac-ash); color: var(--text-dark);">
                    <i class="bi bi-plus-lg me-1"></i> Nuovo Itinerario
                </button>
            </div>

            <!-- Barra di Ricerca (Solo grafica statica per ora) -->
            <div class="input-group mb-3">
                <span class="input-group-text" style="background-color: var(--lavender-veil); border-color: var(--lilac-ash);">
                    <i class="bi bi-search" style="color: var(--text-dark);"></i>
                </span>
                <input 
                    type="text" 
                    class="form-control"
                    placeholder="Cerca per titolo o destinazione..." 
                    style="background-color: #f8f9fa; border-color: var(--lilac-ash);"
                >
            </div>

            <!-- Tabella dei contenuti di esempio -->
            <div class="table-responsive rounded-3" style="border: 1px solid var(--border-dark);">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="py-3 border-bottom">Titolo</th>
                            <th class="py-3 border-bottom">Destinazione</th>
                            <th class="py-3 border-bottom">Durata</th>
                            <th class="py-3 border-bottom">Data</th>
                            <th class="py-3 border-bottom text-end">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-semibold">Tour delle Città d'Arte</td>
                            <td>
                                <span class="badge px-2 py-1" style="background-color: var(--lavender-veil); color: var(--text-dark); border: 1px solid var(--lilac-ash);">
                                    Italia
                                </span>
                            </td>
                            <td>5 Giorni</td>
                            <td>2026-06-10</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary me-1 rounded-2" title="Visualizza">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-2" title="Elimina">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Weekend Romantico a Parigi</td>
                            <td>
                                <span class="badge px-2 py-1" style="background-color: var(--lavender-veil); color: var(--text-dark); border: 1px solid var(--lilac-ash);">
                                    Francia
                                </span>
                            </td>
                            <td>3 Giorni</td>
                            <td>2026-07-15</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary me-1 rounded-2" title="Visualizza">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-2" title="Elimina">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-layout>