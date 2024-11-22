<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Models']['SubjectSection']);
require_once(FILE_PATHS['Functions']['PHPLogger']);

class SubjectSectionController
{
    private $subjectSectionModel;

    public function __construct($db)
    {
        $this->subjectSectionModel = new SubjectSectionModel($db);
    }

    // Check if the subject is already assigned to the section and period

    public function addSubjectSection($data)
    {
        // Validate required fields
        if (empty($data['subject_ids']) || empty($data['section_id']) || empty($data['teacher_id'])) {
            return [
                "success" => false,
                "message" => "All fields are required."
            ];
        }

        // Initialize response array
        $response = [
            "success" => true,
            "message" => "Subject Sections added successfully"
        ];

        // Iterate over subject_ids to add each subject to the section
        foreach ($data['subject_ids'] as $subject_id) {
            // Prepare data for each subject_id
            $subjectData = [
                'subject_id' => $subject_id,
                'section_id' => $data['section_id'],
                'teacher_id' => $data['teacher_id'],
                'subject_section_image' => $data['subject_section_image'] // Assuming this field is the same for all subjects
            ];

            // Call the model's addSubjectSection method for each subject_id
            $result = $this->subjectSectionModel->addSubjectSection($subjectData);

            if (!$result['success']) {
                // If any subject fails to be added, return failure message with the error
                return [
                    "success" => false,
                    "message" => "Error: " . $result['message']
                ];
            }

            // Log successful operation for each subject
            $this->logOperation($subject_id, $data['section_id']);
        }

        // Return successful response if all subject sections are added
        return $response;
    }


    // Update academic period

    // Fetch subject section details
    public function getSubjectSectionDetails($subjectSectionId)
    {
        if (empty($subjectSectionId)) {
            return [
                "success" => false,
                "message" => "Subject Section ID is required."
            ];
        }

        $details = $this->subjectSectionModel->getSubjectSectionDetails($subjectSectionId);

        if ($details) {
            return [
                "success" => true,
                "data" => $details
            ];
        }

        return [
            "success" => false,
            "message" => "No details found for the provided Subject Section ID."
        ];
    }



    // Fetch search results for different entities
    public function fetchSearchResults($searchByTableName, $query, $additionalFilters = [])
    {
        switch ($searchByTableName) {
            case 'section':
                return $this->subjectSectionModel->searchSections($query);

            case 'subject':
                // Check if an educational level filter is provided in the additional filters
                $educationalLevel = $additionalFilters['educational_level'] ?? null;
                return $this->subjectSectionModel->searchSubject($query, $educationalLevel);

            case 'teacher':
                // Handle search for teacher with optional educational level filter
                $educationalLevel = $additionalFilters['educational_level'] ?? null;
                return $this->subjectSectionModel->searchTeacher($query, $educationalLevel);

            default:
                return [];
        }
    }



    private function logOperation($subject_id, $section_id)
    {
        msgLog("CRUD", "[ADD] [SUBJECT_SECTION] Subject: {$subject_id}, Section: {$section_id}");
    }
}
