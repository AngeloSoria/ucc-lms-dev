<style>
  #containerSystemDefault {
    max-height: 500px;
    overflow-y: scroll;
  }

  #containerSystemDefault .system-default-item {
    min-width: 150px;
    max-width: 200px;
    min-height: 150px;
    max-height: 150px;

    box-sizing: border-box;
    transition: 0.05s ease;
  }

  #containerSystemDefault .system-default-item:hover {
    outline: 2px solid rgba(0, 148, 50, 0.5) !important;
  }

  #containerSystemDefault .system-default-item .preview-container {
    padding: 5px;
    width: 100%;
    height: 100px;
  }

  #containerSystemDefault .system-default-item .preview-container img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  #containerSystemDefault .system-default-item .system-default-info {
    font-size: 0.75rem;
  }
</style>

<div class="modal fade" id="fileSelectModal" tabindex="-1" aria-labelledby="fileSelectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fileSelectModalLabel">Select File</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Tab navigation -->
        <ul class="nav nav-tabs" id="fileTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button"
              role="tab" aria-controls="upload" aria-selected="true">Upload</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="system-default-tab" data-bs-toggle="tab" data-bs-target="#my-upload"
              type="button" role="tab" aria-controls="system-default" aria-selected="false">My Uploads</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="system-default-tab" data-bs-toggle="tab" data-bs-target="#system-default"
              type="button" role="tab" aria-controls="system-default" aria-selected="false">System Default</button>
          </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content mt-3">
          <!-- Upload Tab -->
          <div class="tab-pane fade show active" id="upload" role="tabpanel" aria-labelledby="upload-tab">
            <form id="fileUploadForm" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="action" value="addCarousel">
              <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter image title"
                  required>
              </div>

              <div class="mb-3">
                <label for="formFile" class="form-label">Choose file to upload</label>
                <input class="form-control" type="file" id="formFile" name="file" accept="image/*">
              </div>

              <div class="mb-3">
                <label for="view_type" class="form-label">View Type</label>
                <select class="form-select" id="view_type" name="view_type" required>
                  <option value="" disabled selected>Select view type</option>
                  <option value="home">Home</option>
                  <option value="dashboard">Dashboard</option>
                </select>
              </div>

            </form>
          </div>



        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="fileUploadForm">Save</button>
      </div>
    </div>
  </div>
</div>