<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Models']['Announcements']);
require_once CONTROLLERS . 'GeneralLogsController.php';

class AnnouncementController
{
    private $db;
    private $announcementModel;
    private $generalLogsController;

    public function __construct()
    {
        // Initialize the database connection
        $database = new Database();
        $this->db = $database->getConnection();

        // Initialize the Announcements model
        $this->announcementModel = new Announcements($this->db);
        $this->generalLogsController = new GeneralLogsController();
    }

    /**
     * Handles adding an announcement via POST request.
     */
    public function addAnnouncement($announcementData)
    {
        try {
            // Check if the announcement is global
            $announcementData['is_global'] = isset($announcementData['is_global']) ? $announcementData['is_global'] : 0;

            // Add the announcement to the database
            $addResult = $this->announcementModel->addAnnouncement($announcementData);

            $logMessage = "Posted an announcement";
            if ($announcementData['is_global'] == 1) {
                $logMessage .= " to global";
            } else {
                $logMessage .= " to subject section (" . $announcementData['subject_section_id'] . ")";
            }
            $this->generalLogsController->addLog_CREATE($_SESSION['user_id'], $_SESSION['role'], $logMessage);

            $addResult['message'] = "Announcement has been posted.";
            return $addResult;
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Retrieves all announcements for a specific subject_section or global announcements.
     */
    public function getAnnouncements($subject_section_id = null)
    {
        try {
            // If subject_section_id is provided, fetch specific section announcements
            if ($subject_section_id) {
                $announcements = $this->announcementModel->getAnnouncementsBySubjectSection($subject_section_id);
            } else {
                // If no subject_section_id, fetch global announcements
                $announcements = $this->announcementModel->getGlobalAnnouncements();
            }

            // Return the announcements if found, or a message if not
            if ($announcements) {
                return ['success' => true, 'data' => $announcements];
            } else {
                return ['success' => false, 'message' => "No announcements found."];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Updates an existing announcement.
     */
    public function updateAnnouncement($announcementData)
    {
        try {
            $updateResult = $this->announcementModel->updateAnnouncement($announcementData);
            if ($updateResult['success']) {
                $logMessage = "Updated an announcement";
                $this->generalLogsController->addLog_UPDATE($_SESSION['user_id'], $_SESSION['role'], $logMessage);
                return ['success' => true, 'message' => "Announcement has been updated."];
            } else {
                return ['success' => false, 'message' => 'Failed to update announcement.'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Deletes an announcement.
     */
    public function deleteAnnouncement($id)
    {
        try {
            $deleteResult = $this->announcementModel->deleteAnnouncement($id);

            $logMessage = "Removed an announcement";
            $this->generalLogsController->addLog_DELETE($_SESSION['user_id'], $_SESSION['role'], $logMessage);

            return $deleteResult['success'] == true ? ['success' => true, 'message' => "Successfully removed an announcement."] : ['success' => false, 'message' => 'Failed to delete announcement.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Retrieves global announcements.
     */
    public function getGlobalAnnouncements()
    {
        try {
            $announcements = $this->announcementModel->getGlobalAnnouncements();

            if ($announcements) {
                return ['success' => true, 'data' => $announcements];
            } else {
                return ['success' => false, 'message' => "No global announcements found."];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
