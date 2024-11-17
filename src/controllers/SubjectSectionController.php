<?php
class SubjectSectionController
{
    private $subjectSectionModel;

    public function __construct($subjectSectionModel)
    {
        $this->subjectSectionModel = $subjectSectionModel;
    }

    // Add subject section
    public function addSubjectSection($data)
    {
        // Validate required fields
        if (empty($data['subject_id']) || empty($data['section_id']) || empty($data['teacher_id']) || empty($data['period_id'])) {
            return [
                "success" => false,
                "message" => "All fields are required: subject_id, section_id, teacher_id, and period_id."
            ];
        }

        // Check if the subject section already exists
        if ($this->subjectSectionModel->checkSubjectSectionExists($data['subject_id'], $data['section_id'], $data['period_id'])) {
            return [
                "success" => false,
                "message" => "This subject section already exists."
            ];
        }

        // Add the subject section to the database
        $result = $this->subjectSectionModel->addSubjectSection($data);

        if ($result['success']) {
            msgLog("CRUD", "[ADD] [SUBJECT_SECTION] [SUBJECT_ID: " . $data['subject_id'] . "] [SECTION_ID: " . $data['section_id'] . "] | [" . $_SESSION["username"] . "] [" . $_SESSION["role"] . "]");
            return [
                "success" => true,
                "message" => "Subject Section added successfully."
            ];
        } else {
            return [
                "success" => false,
                "message" => "Error adding subject section: " . $result['message']
            ];
        }
    }

    // Get subject section details
    public function getSubjectSection($subject_section_id)
    {
        $result = $this->subjectSectionModel->getSubjectSectionDetails($subject_section_id);

        if ($result) {
            return [
                "success" => true,
                "data" => $result
            ];
        } else {
            return [
                "success" => false,
                "message" => "Subject Section not found."
            ];
        }
    }
}
