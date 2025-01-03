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

            $moduleData['visibility'] = $moduleData['visibility'] == '1' ? 'shown' : 'hidden';

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

    public function getNumberOfModulesBySubjectSectionId($subject_section_id)
    {
        try {
            $result = $this->moduleContentModel->getNumberOfModulesBySubjectSectionId($subject_section_id);
            return ['success' => true, "data" => $result];
        } catch (Exception $e) {
            return ['success' => false, "message" => $e->getMessage()];
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
            msgLog("123", $e->getMessage());
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function getContentById($content_id)
    {
        try {
            $success = $this->moduleContentModel->getContentById($content_id);
            return ['success' => true, "data" => $success];
        } catch (Exception $e) {
            return ['success' => false, "message" => $e->getMessage()];
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

    // Handle getting files for contentw
    public function getContentFiles($content_id)
    {
        try {
            $result = $this->moduleContentModel->getFilesByContent($content_id);
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getFileByContentFileId($content_id, $content_file_id)
    {
        try {
            $result = $this->moduleContentModel->getFileByContentFileId($content_id, $content_file_id);
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getSubmittedFilesByContentIdStudentId($content_id, $student_id, $submission_id = null)
    {
        try {
            $result = $this->moduleContentModel->getSubmittedFilesByContentIdStudentId($content_id, $student_id, $submission_id);
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
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
    public function addSubmission($submissionData, $fileInputs)
    {
        try {
            $this->moduleContentModel->addSubmission($submissionData, $fileInputs);
            return ['success' => true, "message" => "Submission success."];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateSubmissionGrade($submissionData)
    {
        try {
            $this->moduleContentModel->updateSubmissionGrade($submissionData);
            return ['success' => true, 'message' => "Score has been set to this submission."];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    public function getSubmissionsByContent($content_id, $student_id = null)
    {
        try {
            $result = $this->moduleContentModel->getSubmissionsByContent($content_id, $student_id);
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function getLatestSubmission($content_id, $student_id)
    {
        try {
            $result = $this->moduleContentModel->getLatestSubmission($content_id, $student_id);
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function getFilesBySubmission($submission_id)
    {
        try {
            $result = $this->moduleContentModel->getFilesBySubmission($submission_id);
            return ["success" => true, "data" => $result];
        } catch (Exception $e) {
            return ["success" => false, "message" => "Error fetching submitted files:" . $e->getMessage()];
        }
    }

    public function getFileBySubmissionFilesId($submission_files_id)
    {
        try {
            $result = $this->moduleContentModel->getFileBySubmissionFilesId($submission_files_id);
            return ["success" => true, "data" => $result];
        } catch (Exception $e) {
            return ["success" => false, "message" => "Error fetching submitted file:" . $e->getMessage()];
        }
    }

    public function getStudentsSubmission($content_id)
    {
        try {
            $result = $this->moduleContentModel->getStudentsSubmission($content_id);
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getAllContentsFromSubjectSection($subject_section_id)
    {
        try {
            $result = $this->moduleContentModel->getAllContentsFromSubjectSection($subject_section_id);
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getStudentSubmission($content_id, $student_id, $isLatestOnly = false)
    {
        try {
            $result = $this->moduleContentModel->getStudentSubmission($content_id, $student_id, $isLatestOnly);
            return ['success' => true, 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
