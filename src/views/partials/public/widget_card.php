<?php

require_once(__DIR__ . '../../../../config/PathsHandler.php');

class Card
{
    public function Create($cardType = 'small', $unique_id, $image, $textContents, $hasImage = true)
    {
        $card_type = ($cardType == 'small') ? 'c-card-small' : 'c-card-long';

        // Define the image source path as a variable
        $imageSrc = ($image == null) ? UPLOAD_PATH['System'] . '/img/no-image-found.jpg' : $image;

        // Build the dynamic card text HTML
        $cardTitle = $textContents['title'];
        $cardTextHtml = '';
        foreach ($textContents["others"] as $content) {
            $hint = $content['hint'];
            $icon = $content['icon'];
            $text = $content['data'];
            $cardTextHtml .= <<<HTML
                <p class="card-text" title="$hint">
                $icon
                $text
                </p>
            HTML;
        }

        $DOM_IMAGE = <<<HTML

        HTML;
        if ($hasImage == true) {
            $DOM_IMAGE = <<<HTML
                <div class="card-preview">
                    <img src="$imageSrc" alt="No Image Available" class="rounded">
                </div>
            HTML;
        }

        // Use Heredoc to create the main card structure, embedding the dynamic content
        $DOM = <<<HTML
        <div class="c-card rounded $card_type" id="$unique_id">
            $DOM_IMAGE
            <div class="card-body p-2">
                <div class="row">
                    <div class="col-md-10">
                        <h5 class="card-title w-100 fw-bold bg-transparent">$cardTitle</h5>
                        $cardTextHtml
                    </div>
                    <div class="col-md-2 d-flex justify-content-end align-items-start">
                        <div class="dropdown">
                            <button class="btn btn-lg c-primary p-0 text-white dropdown-toggle dropdown-no-icon" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="javascript:void(0);" role="button" data-bs-toggle="modal" data-bs-target="#detailsSectionModal">Details</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0)" role="button" data-bs-toggle="modal" data-bs-target="#configSectionModal">Configure</a></li>
                                <li><a class="dropdown-item" href="#">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        HTML;

        return $DOM;
    }
}
