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
            <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button" role="tab" aria-controls="upload" aria-selected="true">Upload</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="system-default-tab" data-bs-toggle="tab" data-bs-target="#my-upload" type="button" role="tab" aria-controls="system-default" aria-selected="false">My Uploads</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="system-default-tab" data-bs-toggle="tab" data-bs-target="#system-default" type="button" role="tab" aria-controls="system-default" aria-selected="false">System Default</button>
          </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content mt-3">
          <!-- Upload Tab -->
          <div class="tab-pane fade show active" id="upload" role="tabpanel" aria-labelledby="upload-tab">
            <form id="fileUploadForm" method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter image title" required>
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

          <!-- My Upload Tab -->
          <div class="tab-pane fade" id="my-upload" role="tabpanel" aria-labelledby="my-upload-tab">
            <form id="myUploadForm">
              <div class="mb-3 col-md-5">
                <label for="fileTypeSelect" class="form-label">Filter by file type:</label>
                <select class="form-select" id="fileTypeSelect" name="fileType">
                  <option value="" disabled selected>Select file type</option>
                  <option value="image">Images</option>
                  <option value="video">Videos</option>
                  <option value="document">Documents</option>
                  <option value="audio">Audio</option>
                </select>
              </div>
              <div id="containerSystemDefault" class="mb-1 col-md-12 bg-light-3 p-2 d-flex flex-wrap gap-2">

                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="image"
                  file-subtype="image">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="https://via.placeholder.com/800x500" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">File Name.extension asdasdd</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="image"
                  file-subtype="image">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/avatars/profile-avatar-1.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">File Name.extension asdasdd</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="image"
                  file-subtype="image">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/avatars/profile-avatar-2.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">File Name.extension asdasdd</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="doc"
                  file-subtype="doc">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/doc.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">test.docx</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="document"
                  file-subtype="doc">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/excel.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">test.xls</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="audio"
                  file-subtype="audio">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/music.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">my_song.mp3</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="none"
                  file-subtype="none">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/not-recognized.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">1337.hh</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="document"
                  file-subtype="pdf">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/pdf.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">Handout.pdf</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="document"
                  file-subtype="ppt">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/ppt.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">my_presentation-1.pptx</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="document"
                  file-subtype="txt">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/txt.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">logs.txt</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="video"
                  file-subtype="video">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/video.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">Dance Practice.mp4</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="zip"
                  file-subtype="zip">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/zip.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">Activities Compilation.rar</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="image"
                  file-subtype="image">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/placeholder-1.jpg" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">placeholder-1.jpg</p>
                    </div>
                  </div>
                </div>


              </div>
            </form>
          </div>

          <!-- System Default Tab -->
          <div class="tab-pane fade" id="system-default" role="tabpanel" aria-labelledby="system-default-tab">
            <form id="systemDefaultForm">
              <div class="mb-3 col-md-5">
                <label for="fileTypeSelect" class="form-label">Filter by file type:</label>
                <select class="form-select" id="fileTypeSelect" name="fileType">
                  <option value="" disabled selected>Select file type</option>
                  <option value="image">Images</option>
                  <option value="video">Videos</option>
                  <option value="document">Documents</option>
                  <option value="audio">Audio</option>
                </select>
              </div>
              <div id="containerSystemDefault" class="mb-1 col-md-12 bg-light-3 p-2 d-flex flex-wrap gap-2">

                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="image"
                  file-subtype="image">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="https://via.placeholder.com/800x500" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">File Name.extension asdasdd</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="image"
                  file-subtype="image">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/avatars/profile-avatar-1.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">File Name.extension asdasdd</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="image"
                  file-subtype="image">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/avatars/profile-avatar-2.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">File Name.extension asdasdd</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="doc"
                  file-subtype="doc">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/doc.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">test.docx</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="document"
                  file-subtype="doc">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/excel.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">test.xls</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="audio"
                  file-subtype="audio">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/music.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">my_song.mp3</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="none"
                  file-subtype="none">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/not-recognized.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">1337.hh</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="document"
                  file-subtype="pdf">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/pdf.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">Handout.pdf</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="document"
                  file-subtype="ppt">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/ppt.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">my_presentation-1.pptx</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="document"
                  file-subtype="txt">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/txt.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">logs.txt</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="video"
                  file-subtype="video">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/video.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">Dance Practice.mp4</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="zip"
                  file-subtype="zip">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/icons/file_types/zip.png" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">Activities Compilation.rar</p>
                    </div>
                  </div>
                </div>
                <!-- Item -->
                <div
                  class="system-default-item bg-light rounded border p-1"
                  role="button"
                  file-type="image"
                  file-subtype="image">
                  <div class="d-flex gap-1 p-0 flex-column">
                    <div class="preview-container bg-light-3 position-relative d-flex justify-content-center align-items-center">
                      <!-- Icon / Preview -->
                      <img src="../../../../src/assets/images/placeholder-1.jpg" alt="..." class="system-default-image d-block">
                    </div>
                    <div class="system-default-info p-0 m-0 px-2">
                      <p class="mb-0 text-center text-truncate text-wrap">placeholder-1.jpg</p>
                    </div>
                  </div>
                </div>


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