<!-- Add Modal Flavour -->
<div class="modal fade" id="addFlavourModal" tabindex="-1" aria-labelledby="addFlavourModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFlavourModalLabel">Add Flavour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('flavours.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="flavourName" class="form-label">Flavour Name</label>
                        <input type="text" class="form-control" id="flavourName" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
