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

    public function addLog_LOGOUT($user_id, $user_role = "anonymous", $description = "no description passed.")
    {
        try {
            // prepare
            $generalLogsModelResult = $this->generalLogsModel->addLog_LOGOUT($user_id, $user_role, "User logged out to session.");
            if ($generalLogsModelResult['success'] == false) {
                return ['success' => false, 'message' => 'Login unsuccessful.'];
            }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function addLog_UPDATEPASS($user_id, $user_role)
    {
        try {
            $generalLogsModelResult = $this->generalLogsModel->addLog_UPDATEPASS($user_id, $user_role, "User updated their password.");
            // if ($generalLogsModelResult['success'] == false) {
            //     return ['success' => false, 'message' => 'Password update success.'];
            // }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Failed to log: ' . $e->getMessage()];
        }
    }

    public function addLog_CREATE($user_id, $user_role, $description)
    {
        try {
            $this->generalLogsModel->addLog_CREATE($user_id, $user_role, $description);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Failed to log: ' . $e->getMessage()];
        }
    }

    public function addLog_DELETE($user_id, $user_role, $description)
    {
        try {
            $this->generalLogsModel->addLog_DELETE($user_id, $user_role, $description);
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Failed to log: ' . $e->getMessage()];
        }
    }

    public function addLog_UPDATE($user_id, $user_role, $description)
    {
        try {
            $this->generalLogsModel->addLog_UPDATE($user_id, $user_role, $description);
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Failed to log: ' . $e->getMessage()];
        }
    }

    public function getAllLatestLogs($limit = 100)
    {
        try {
            $logsResult = $this->generalLogsModel->getAllLatestLogs($limit);
            return ['success' => true, 'data' => $logsResult];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
