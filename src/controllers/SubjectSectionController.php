<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Models']['SubjectSection']);
require_once(FILE_PATHS['Controllers']['StudentEnrollment']);
require_once(FILE_PATHS['Functions']['PHPLogger']);

class SubjectSectionController
{
    private $subjectSectionModel;
    private $studentEnrollmentController;

    public function __construct($db)
    {
        $this->subjectSectionModel = new SubjectSectionModel($db);
        $this->studentEnrollmentController = new StudentEnrollmentController($db);
    }

    // Check if the subject is already assigned to the section and period

    public function addSubjectSection($data)
    {
        if (isset($data['multi_subject'])) {
            switch ($data['multi_subject']) {
                case 'true':
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

                    $this->studentEnrollmentController->enrollRegularStudentsToSubjects();

                    // Return successful response if all subject sections are added
                    return $response;
                case 'false':
                    // Validate required fields
                    if (empty($data['subject_id']) || empty($data['section_id']) || empty($data['teacher_id'])) {
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

                    // Call the model's addSubjectSection method for each subject_id
                    $result = $this->subjectSectionModel->addSubjectSection($data);

                    if (!$result['success']) {
                        // If any subject fails to be added, return failure message with the error
                        return [
                            "success" => false,
                            "message" => "Error: " . $result['message']
                        ];
                    }

                    $this->studentEnrollmentController->enrollRegularStudentsToSubjects();

                    // Log successful operation for each subject
                    $this->logOperation($data['subject_id'], $data['section_id']);
                    return $response;
            }
        }
    }

    public function deleteSubjectsFromSection($subject_section_ids)
    {
        try {
            if (!is_array($subject_section_ids) || empty($subject_section_ids)) {
                throw new Exception("Passed subject_section_ids contains empty or not an array.");
            }

            foreach ($subject_section_ids as $subject_section_id) {
                $this->subjectSectionModel->deleteSubjectFromSection($subject_section_id);
            }

            return ["success" => true, "message" => "Successfully deleted a subject from a section."];
        } catch (Exception $e) {
            return ['success' => false, "message" => $e->getMessage()];
        }
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

            case 'student':
                // Handle search for teacher with optional educational level filter
                $educationalLevel = $additionalFilters['educational_level'] ?? null;
                return $this->subjectSectionModel->searchStudent($query, $educationalLevel);

            default:
                return [];
        }
    }

    public function getAllEnrolledSubjectsFromSectionBySectionId($section_id)
    {
        try {
            $retrievedEnrolledSubjects = $this->subjectSectionModel->getAllEnrolledSubjectsFromSectionBySectionId($section_id);
            if ($retrievedEnrolledSubjects['success']) {
                return ['success' => true, 'data' => $retrievedEnrolledSubjects['data']];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getAllEnrolledSubjectsFromSectionByTeacherId($teacher_id)
    {
        try {
            $retrievedSubjectsFromTeacher = $this->subjectSectionModel->getAllEnrolledSubjectsFromSectionByTeacherId($teacher_id);
            return $retrievedSubjectsFromTeacher;
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function logOperation($subject_id, $section_id)
    {
        msgLog("CRUD", "[ADD] [SUBJECT_SECTION] Subject: {$subject_id}, Section: {$section_id}");
    }

    public function getNumberOfEnrolledStudentsInSubject($subject_section_id)
    {
        try {
            $result = $this->subjectSectionModel->getNumberOfEnrolledStudentsInSubject($subject_section_id);
            return $result['student_count'];
        } catch (Exception $e) {
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    public function getEnrolledStudentsFromSubject($subject_section_id)
    {
        try {
            $result = $this->subjectSectionModel->getEnrolledStudentsFromSubject($subject_section_id);
            return ['success' => true, 'message' => 'Success in retrieving enrolled students.', 'data' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function isStudentEnrolledFromSubjectSection($student_id, $subject_section_id)
    {
        try {
            $result = $this->subjectSectionModel->isStudentEnrolledFromSubjectSection($student_id, $subject_section_id);
            return [
                'success' => true,
                'message' => 'Success in retrieving students enrolled to student_subject_section.',
                'data' => $result
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
