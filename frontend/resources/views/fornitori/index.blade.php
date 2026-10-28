<x-layout>
    <div class="container-fluid">
        <div class="card ragsy-card p-4 d-flex flex-column gap-3">
            
            <!-- Intestazione della sezione -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-2">
                <div>
                    <h2 class="mb-1" style="color: var(--text-dark);">Gestione Fornitori</h2>
                    <p class="mb-0" style="color: var(--text-dark);">Tieni traccia di partner, agenzie e fornitori di servizi turistici.</p>
                </div>
                <button class="btn rounded-3" style="background-color: var(--accent-color); border: 1px solid var(--lilac-ash); color: var(--text-dark);">
                    <i class="bi bi-plus-lg me-1"></i> Nuovo Fornitore
                </button>
            </div>

            <!-- Barra di Ricerca -->
            <div class="input-group mb-3">
                <span class="input-group-text" style="background-color: var(--lavender-veil); border-color: var(--lilac-ash);">
                    <i class="bi bi-search" style="color: var(--text-dark);"></i>
                </span>
                <input 
                    type="text" 
                    class="form-control" 
                    placeholder="Cerca fornitore o servizio..." 
                    style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);"
                >
            </div>

            <!-- Tabella dei contenuti -->
            <div class="table-responsive rounded-3 overflow-hidden" style="border: 1px solid var(--lilac-ash);">
                <table class="table align-middle mb-0" style="background-color: var(--lavender-veil);">
                    <thead>
                        <tr style="border-color: var(--lilac-ash);">
                            <th class="py-3 px-3 border-bottom fw-bold" style="border-color: var(--lilac-ash); color: var(--text-dark);">Nome Fornitore</th>
                            <th class="py-3 px-3 border-bottom fw-bold" style="border-color: var(--lilac-ash); color: var(--text-dark);">Servizio Offerto</th>
                            <th class="py-3 px-3 border-bottom fw-bold" style="border-color: var(--lilac-ash); color: var(--text-dark);">Contatto</th>
                            <th class="py-3 px-3 border-bottom fw-bold text-end" style="border-color: var(--lilac-ash); color: var(--text-dark);">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-color: var(--lilac-ash);">
                            <td class="fw-semibold px-3 py-3" style="color: var(--text-dark);">Hotel Bella Vista</td>
                            <td class="px-3 py-3">
                                <span class="badge px-2 py-1" style="background-color: #ffffff; color: var(--text-dark); border: 1px solid var(--lilac-ash);">
                                    Pernottamento
                                </span>
                            </td>
                            <td class="px-3 py-3" style="color: var(--text-dark);">info@bellavista.it</td>
                            <td class="text-end px-3 py-3">
                                <button class="btn btn-sm btn-outline-secondary me-1 rounded-2" title="Modifica" style="border-color: var(--lilac-ash); background-color: #ffffff;">
                                    <i class="bi bi-pencil" style="color: var(--text-dark);"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-2" title="Elimina" style="border-color: #f5c6cb; background-color: #ffffff;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr style="border-color: var(--lilac-ash);">
                            <td class="fw-semibold px-3 py-3" style="color: var(--text-dark);">Roma Shuttle Tours</td>
                            <td class="px-3 py-3">
                                <span class="badge px-2 py-1" style="background-color: #ffffff; color: var(--text-dark); border: 1px solid var(--lilac-ash);">
                                    Transporti
                                </span>
                            </td>
                            <td class="px-3 py-3" style="color: var(--text-dark);">booking@romashuttle.com</td>
                            <td class="text-end px-3 py-3">
                                <button class="btn btn-sm btn-outline-secondary me-1 rounded-2" title="Modifica" style="border-color: var(--lilac-ash); background-color: #ffffff;">
                                    <i class="bi bi-pencil" style="color: var(--text-dark);"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-2" title="Elimina" style="border-color: #f5c6cb; background-color: #ffffff;">
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