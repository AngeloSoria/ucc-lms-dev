<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Models']['ModuleContent']);
require_once(FILE_PATHS['Functions']['PermissionCheck']);


class ModuleContentController
{
    private $moduleContentModel;
    private $generalLogsController;

    public function __construct()
    {
        $database = new Database();
        $this->moduleContentModel = new ModuleContent();
        $this->generalLogsController = new GeneralLogsController();
    }

    // Handle adding a module
    public function addModule($moduleData)
    {
        try {
            if (!isAllowedToProceed(["Teacher", "Admin"])) {
                throw new Exception("You don't have permission to do this action.");
            }

            $result = $this->moduleContentModel->addModule($moduleData);
            if ($result['success']) {
                $result['message'] = "Successfully added a module.";
                return $result;
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
        // return $this->jsonResponse($result);
    }

    // Handle getting modules by subject_section_id
    public function getModules($subject_section_id)
    {
        try {
            $result = $this->moduleContentModel->getModulesBySubjectSection($subject_section_id);
            return ['success' => true, "data" => $result];
        } catch (Exception $e) {
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    public function getModule($module_id)
    {
        try {
            $result = $this->moduleContentModel->getModuleByModuleId($module_id);
            return ['success' => true, "data" => $result];
        } catch (Exception $e) {
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    // Handle updating a module
    public function updateModule($moduleData)
    {
        try {
            if (!isAllowedToProceed(["Teacher", "Admin"])) {
                throw new Exception("You don't have permission to do this action.");
            }
            $result = $this->moduleContentModel->updateModule($moduleData);
            return ['success' => true, "message" => "Successfully updated the module."];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Handle deleting a module
    public function deleteModule($module_id)
    {
        try {
            if (!isAllowedToProceed(["Teacher", "Admin"])) {
                throw new Exception("You don't have permission to do this action.");
            }

            $result = $this->moduleContentModel->deleteModule($module_id);
            return ['success' => true, "message" => "Successfully deleted a module."];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Handle adding content to a module
    public function addContent($contentData)
    {
        try {
            if (!isAllowedToProceed(["Teacher", "Admin"])) {
                throw new Exception("You don't have permission to do this action.");
            }

            $result = $this->moduleContentModel->addContent($contentData);
            if ($result['success']) {
                return ['success' => true, "message" => "Successfully added a content to a module."];
            } else {
                throw new Exception("Something went wrong adding content to a module.");
            }
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Handle getting contents by module_id
    public function getContents($module_id)
    {
        try {
            $success = $this->moduleContentModel->getContentsByModule($module_id);
            return ['success' => true, "data" => $success];
        } catch (Exception $e) {
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    // Handle updating content
    public function updateContent($contentData)
    {
        try {
            if (!isAllowedToProceed(["Teacher", "Admin"])) {
                throw new Exception("You don't have permission to do this action.");
            }

            $result = $this->moduleContentModel->updateContent($contentData);
            return ['success' => true, "data" => $result];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function updateContentVisibility($contentData)
    {
        try {
            $result = $this->moduleContentModel->updateContentVisibility($contentData);
            if ($result['success']) {
                return ['success' => true, 'message' => 'Successfuly updated the visibility of a content.'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Handle deleting content
    public function deleteContent($contentData)
    {
        try {
            if (!isAllowedToProceed(["Teacher", "Admin"])) {
                throw new Exception("You don't have permission to do this action.");
            }

            $result = $this->moduleContentModel->deleteContent($contentData);
            if ($result['success']) {
                return ['success' => true, "message" => "Successfully deleted a content from a module."];
            }
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Handle adding a file to content
    public function addContentFile($fileData)
    {
        try {
            if (!isAllowedToProceed(["Teacher", "Admin"])) {
                throw new Exception("You don't have permission to do this action.");
            }

            $result = $this->moduleContentModel->addContentFile($fileData);
            return ['success' => true, "data" => $result];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Handle getting files for content
    public function getContentFiles($content_id)
    {
        $result = $this->moduleContentModel->getFilesByContent($content_id);
        return $this->jsonResponse($result);
    }

    // Handle deleting a file from content
    public function deleteContentFile($file_id)
    {
        try {
            if (!isAllowedToProceed(["Teacher", "Admin"])) {
                throw new Exception("You don't have permission to do this action.");
            }

            $result = $this->moduleContentModel->deleteContentFile($file_id);
            return ['success' => true, "data" => $result];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    // Handle adding a student submission
    public function addSubmission($submissionData)
    {
        $result = $this->moduleContentModel->addSubmission($submissionData);
        return $this->jsonResponse($result);
    }

    // Handle adding a file to a submission
    public function addSubmissionFile($fileData)
    {
        $result = $this->moduleContentModel->addSubmissionFile($fileData);
        return $this->jsonResponse($result);
    }

    // Helper function to format JSON response
    private function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
