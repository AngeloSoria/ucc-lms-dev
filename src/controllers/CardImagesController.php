<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Models']['CardImages']);

class CardImagesController
{
    private $cardImagesModel;
    public function __construct()
    {
        $this->cardImagesModel = new CardImages();
    }

    // ROLE BASED
    //GET
    public function getImageByRole($role)
    {
        try {
            $result = $this->cardImagesModel->getImageByRole($role);

            if ($result['success']) {
                if ($result['data']) {
                    return ['success' => true, 'data' => $result['data']];
                } else {
                    return ['success' => false, 'message' => "No image retrieved with role ($role)"];
                }
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function updateImageByRole($role)
    {
        // e
    }
    //===================================

}
