<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Models']['ModuleContent']);


class ModuleContentController
{
    private $db;
    private $moduleContentModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->moduleContentModel = new ModuleContent($this->db);
    }

    // Handle adding a module
    public function addModule($moduleData)
    {
        $result = $this->moduleContentModel->addModules($moduleData);
        return $this->jsonResponse($result);
    }

    // Handle getting modules by subject_section_id
    public function getModules($subject_section_id)
    {
        $result = $this->moduleContentModel->getModulesBySubjectSection($subject_section_id);
        return $this->jsonResponse($result);
    }

    // Handle updating a module
    public function updateModule($moduleData)
    {
        $result = $this->moduleContentModel->updateModules($moduleData);
        return $this->jsonResponse($result);
    }

    // Handle deleting a module
    public function deleteModule($module_id)
    {
        $result = $this->moduleContentModel->deleteModule($module_id);
        return $this->jsonResponse($result);
    }

    // Handle adding content to a module
    public function addContent($contentData)
    {
        $result = $this->moduleContentModel->addContent($contentData);
        return $this->jsonResponse($result);
    }

    // Handle getting contents by module_id
    public function getContents($module_id)
    {
        $result = $this->moduleContentModel->getContentsByModule($module_id);
        return $this->jsonResponse($result);
    }

    // Handle updating content
    public function updateContent($contentData)
    {
        $result = $this->moduleContentModel->updateContent($contentData);
        return $this->jsonResponse($result);
    }

    // Handle deleting content
    public function deleteContent($content_id)
    {
        $result = $this->moduleContentModel->deleteContent($content_id);
        return $this->jsonResponse($result);
    }

    // Handle adding a file to content
    public function addContentFile($fileData)
    {
        $result = $this->moduleContentModel->addContentFile($fileData);
        return $this->jsonResponse($result);
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
        $result = $this->moduleContentModel->deleteContentFile($file_id);
        return $this->jsonResponse($result);
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
?>