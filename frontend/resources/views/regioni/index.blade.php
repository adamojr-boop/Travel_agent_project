<x-layout>
    <div class="container-fluid">
        <div class="card ragsy-card p-4 d-flex flex-column gap-3">
            
            <!-- Intestazione della sezione -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-2">
                <div>
                    <h2 class="mb-1" style="color: var(--text-dark);">Gestione Regioni</h2>
                    <p class="mb-0" style="color: var(--text-dark);">Esplora e configura le regioni geografiche e le aree di interesse.</p>
                </div>
                <button class="btn rounded-3" style="background-color: var(--accent-color); border: 1px solid var(--lilac-ash); color: var(--text-dark);">
                    <i class="bi bi-plus-lg me-1"></i> Nuova Regione
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
                    placeholder="Cerca regione..." 
                    style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);"
                >
            </div>

            <!-- Tabella dei contenuti -->
            <div class="table-responsive rounded-3 overflow-hidden" style="border: 1px solid var(--lilac-ash);">
                <table class="table align-middle mb-0" style="background-color: var(--lavender-veil);">
                    <thead>
                        <tr style="border-color: var(--lilac-ash);">
                            <th class="py-3 px-3 border-bottom fw-bold" style="border-color: var(--lilac-ash); color: var(--text-dark);">Nome Regione</th>
                            <th class="py-3 px-3 border-bottom fw-bold" style="border-color: var(--lilac-ash); color: var(--text-dark);">Paese</th>
                            <th class="py-3 px-3 border-bottom fw-bold" style="border-color: var(--lilac-ash); color: var(--text-dark);">Itinerari Associati</th>
                            <th class="py-3 px-3 border-bottom fw-bold text-end" style="border-color: var(--lilac-ash); color: var(--text-dark);">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-color: var(--lilac-ash);">
                            <td class="fw-semibold px-3 py-3" style="color: var(--text-dark);">Toscana</td>
                            <td class="px-3 py-3">
                                <span class="badge px-2 py-1" style="background-color: #ffffff; color: var(--text-dark); border: 1px solid var(--lilac-ash);">
                                    Italia
                                </span>
                            </td>
                            <td class="px-3 py-3" style="color: var(--text-dark);">4 Attivi</td>
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
                            <td class="fw-semibold px-3 py-3" style="color: var(--text-dark);">Île-de-France</td>
                            <td class="px-3 py-3">
                                <span class="badge px-2 py-1" style="background-color: #ffffff; color: var(--text-dark); border: 1px solid var(--lilac-ash);">
                                    Francia
                                </span>
                            </td>
                            <td class="px-3 py-3" style="color: var(--text-dark);">2 Attivi</td>
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