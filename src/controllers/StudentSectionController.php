<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['Models']['StudentSection']);
require_once(FILE_PATHS['Functions']['PHPLogger']);

class StudentSectionController
{
    private $studentSectionModel;

    public function __construct($db)
    {
        $this->studentSectionModel = new StudentSectionModel($db);
    }

    public function addStudentsToSection($data)
    {
        if (empty($data['student_ids']) || empty($data['section_id']) || empty($data['enrollment_type'])) {
            return ["success" => false, "message" => "All fields are required."];
        }

        if (!$this->studentSectionModel->isSectionExist($data['section_id'])) {
            return ["success" => false, "message" => "The specified section does not exist."];
        }


        $errors = [];
        $successfulAdds = 0;

        foreach ($data['student_ids'] as $student_id) {
            msgLog("1st", $student_id);
            if (!$this->studentSectionModel->isStudentRole($student_id)) {
                $errors[] = "Student ID {$student_id} is invalid.";
                continue;
            }
            msgLog("2nd", $student_id);
            if ($this->studentSectionModel->checkStudentInSection($student_id, $data['section_id'])) {
                $errors[] = "Student ID {$student_id} is already enrolled.";
                continue;
            }
            msgLog("3rd", $student_id);
            $modelResult = $this->studentSectionModel->addStudentToSection([
                'student_id' => $student_id,
                'section_id' => $data['section_id'],
                'enrollment_type' => $data['enrollment_type'],
            ]);

            if ($modelResult['success']) {
                $successfulAdds++;
                $this->logOperation($student_id, $data['section_id']);
            } else {
                $errors[] = "Error adding ID {$student_id}: " . $modelResult['message'];
            }
        }

        if ($successfulAdds > 0) {
            return ["success" => true, "message" => "{$successfulAdds} students added successfully."];
        } else {
            return ["success" => false, "message" => implode(", ", $errors)];
        }
    }

    public function fetchSearchResults($searchByTableName, $query)
    {
        if ($searchByTableName === 'student') {
            return $this->studentSectionModel->searchStudents($query);
        } elseif ($searchByTableName === 'section') {
            return $this->studentSectionModel->searchSections($query);
        }
        return [];
    }

    private function logOperation($student_id, $section_id)
    {
        msgLog("CRUD", "[ADD] [STUDENT_SECTION] Student: {$student_id}, Section: {$section_id}");
    }

    public function getAllEnrolledStudentIdBySectionId($section_id)
    {
        try {
            $result = $this->studentSectionModel->getAllEnrolledStudentIdBySectionId($section_id);
            if (!empty($result)) {
                return ['success' => true, 'data' => $result];
            } else {
                return ['success' => false, 'message' => 'No students enrolled.'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getTotalEnrolleesInSection($section_id)
    {
        try {
            $getCountResult = $this->studentSectionModel->getTotalEnrolleesInSection($section_id);
            return ['success' => true, 'data' => $getCountResult];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
