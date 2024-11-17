<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Models']['GeneralLogs']);

class GeneralLogsController
{
    private $generalLogsModel;

    public function __construct()
    {
        $this->generalLogsModel = new GeneralLogs();
    }

    public function addLog_LOGIN($user_id, $user_role)
    {
        try {
            $generalLogsModelResult = $this->generalLogsModel->addLog_LOGIN($user_id, $user_role, "User logged in to session.");
            if ($generalLogsModelResult['success'] == false) {
                return ['success' => false, 'message' => 'Login unsuccessful.'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Login unsuccessful.'];
        }
    }
}
