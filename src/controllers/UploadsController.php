
<?php

require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Models']['Uploads']);


class UploadsController
{
    private $uploadsModel;
    public function __construct()
    {
        $this->uploadsModel = new Uploads();
    }

    public function initUserDirectoriesOnCreate($user_id)
    {
        $this->uploadsModel->initUserDirectoriesOnCreate($user_id);
    }
    public function uploadProfileImage($user_id, $file)
    {
        return $this->uploadsModel->uploadProfileImage($user_id, $file);
    }
}
