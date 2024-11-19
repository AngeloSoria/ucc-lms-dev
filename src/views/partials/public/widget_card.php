<?php

require_once(__DIR__ . '../../../../config/PathsHandler.php');

class Card
{
    public function Create($cardsPerRow = 1, $unique_id, $image, $textContents, $hasImage = true, $isLink = false, $linkPath = null)
    {
        // $card_type = ($cardType == 'small') ? 'c-card-small' : 'c-card-long';

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
                    <img src="$imageSrc" alt="No Image Available">
                </div>
            HTML;
        }

        // Use Heredoc to create the main card structure, embedding the dynamic content
        $location = ($isLink == true) ? "window.location.href = '" . $linkPath . "'; return false;" : "javascript:void(0);";
        $DOM = <<<HTML
            <div class="c-card c-cards-row-$cardsPerRow border rounded" id="$unique_id" onclick="$location">
                $DOM_IMAGE
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="card-title w-100 fw-bold bg-transparent">$cardTitle</h5>
                            $cardTextHtml
                        </div>
                    </div>
                </div>
            </div>
        HTML;

        return $DOM;
    }
}
