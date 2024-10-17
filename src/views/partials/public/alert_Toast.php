
<?php


function makeToast($type, $title, $message)
{
    switch($type) {
        case 'success':
            $bg_color = 'bg-success';
            $icon = '';
            break;
        case 'warning':
            $bg_color = 'bg-warning';
            break;
        case 'danger':
            $bg_color = 'bg-danger';
            break;
        default:
            $bg_color = 'bg-primary';
            break;
    }

    echo <<<HTML
        <div class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <h5>$title</h5>
                    <p>$message</p>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    HTML;
}
