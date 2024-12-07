<?php

require_once(__DIR__ . '../../../../config/PathsHandler.php');

class Card
{
    public function Create($cardsPerRow = 1, $unique_id, $image, $textContents, $hasImage = true, $isLink = false, $linkPath = null)
    {
        // Calculate the Bootstrap column class based on $cardsPerRow
        $bootstrapColClass = 12 / $cardsPerRow; // 12 is the total grid units in Bootstrap

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
                <p class="card-text fs-7" title="$hint">
                $icon
                $text
                </p>
            HTML;
        }

        $DOM_IMAGE = '';
        if ($hasImage) {
            $DOM_IMAGE = <<<HTML
                <div class="card-preview">
                    <img src="$imageSrc" alt="No Image Available" class="img-fluid lazy-image" loading="lazy">
                </div>
            HTML;
        }

        // Use Heredoc to create the main card structure, embedding the dynamic content
        $location = $isLink ? "window.location.href = '$linkPath'; return false;" : "javascript:void(0);";

        $DOM = <<<HTML
            <div class="col-sm-12 col-md-6 col-lg-$bootstrapColClass p-1 m-0 box-sizing-box">
                <div class="c-card border rounded" id="$unique_id" onclick="$location">
                    $DOM_IMAGE
                    <div class="card-body p-2">
                        <p class="card-title fw-bold fs-6 text-wrap">$cardTitle</p>
                        $cardTextHtml
                    </div>
                </div>
            </div>
        HTML;

        return $DOM;
    }
}
