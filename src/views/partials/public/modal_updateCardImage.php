<?php

function create_UpdateCardImage($cardContext, $cardID)
{
    $CardImagesController = new CardImagesController();
    switch ($cardContext) {
        case 'roles':
            $getImageResult = $CardImagesController->getImageByRole($cardID);
            $imageSrc = ($getImageResult['success']) ? base64_encode($getImageResult['data']) : 'https://via.placeholder.com/200?text=No+Image';
            echo <<<HTML
                <div class="modal fade" id="modal_updateCardModal_role" tabindex="-1" aria-labelledby="modal_updateCardModal_role" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header c-header">
                                <h5 class="modal-title fs-5 text-center" id="modal_updateCardModal_role">
                                    Update Card Image (WORK IN PROGRESS)
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <!-- Form inside the modal -->
                                <form method="POST">
                                    <input type="hidden" name="postType" action="updateCardImageByRole">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Currently in used by $cardID</label>
                                        <div>
                                            <img src="$imageSrc" alt="Card Image" class="m-auto w-50 ">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Upload New</label>
                                        <input class="form-control" type="file" name="upload_cardImage" id="upload_cardImage" accept="image/*">
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="modal-footer">
                                        <input type="submit" value="Submit" class="btn btn-primary c-primary px-4 py-2 fs-6 w-100">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            HTML;
        default:
            return false;
    }
}
