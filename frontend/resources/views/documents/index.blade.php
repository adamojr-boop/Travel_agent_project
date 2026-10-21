<x-layout>
    
    <div class="container">
        <div class="row gap-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1>Documents</h1>
                    <button class="btn btn-primary-custom fw-bold fs-5" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="bi bi-upload me-2"></i>
                        Upload a file
                    </button>
                </div>
            </div>
            <div class="col-12">
                <div class="card ragsy-card mt-md-4 d-flex flex-column gap-3">
                    <table class="">
                        <thead>
                          <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Type</th>
                            <th scope="col">Size</th>
                            <th scope="col">Uploaded at</th>
                            <th scope="col">
                                <span class="d-none">Actions</span>     
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                            @forelse ($documents as $document)
                            <tr>
                                <td data-label="Name:">{{ $document->name }}</td>
                                <td data-label="Type:">{{ $document->mime }}</td>
                                <td data-label="Size:">{{ $document->getHumanReadableSizeAttribute() }}</td>
                                <td data-label="Uploaded at:">{{ $document->created_at->diffForHumans() }}</td>
                                <td>
                                    <div class="d-flex gap-3">
                                       
                                        <a href="{{ route('documents.download', $document) }}" class="btn btn-outline-light btn-sm">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <form action="{{ route('documents.destroy', $document) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="fs-4 fw-bold">No uploaded files</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                  
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="uploadModalLabel">Upload a new document</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row gap-3">
                        <div class="col-12">
                            <div class="mb-3 has-validation">
                                <label for="name">Name of file*</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="name@example.com" required>
                                @error('name')     
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div> 
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="dropzone-area" for="file">
                                <input class="d-none" type="file" name="file" id="file">
                                <span class="file-name">
                                    <i class="bi bi-upload"></i>
                                    Upload a file
                                </span>
                            </label>
                            @error('file')     
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div> 
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary-custom">Upload</button>
                </div>
            </form>
        </div>
    </div>

</x-layout>