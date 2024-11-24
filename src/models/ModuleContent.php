<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');


class ModuleContent
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db->getConnection();
    }

    public function addModules($moduleData)
    {
        try {
            $this->conn->beginTransaction(); // Begin transaction

            $query = "INSERT INTO modules (subject_section_id, title) VALUES (:subject_section_id, :title)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':subject_section_id', $moduleData['subject_section_id']);
            $stmt->bindParam(':title', $moduleData['title']);

            $stmt->execute(); // Execute statement
            $this->conn->commit(); // Commit transaction

            return ["success" => true];
        } catch (PDOException $e) {
            $this->conn->rollBack(); // Rollback transaction on error
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    public function getModulesBySubjectSection($subject_section_id)
    {
        try {
            $query = "SELECT * FROM modules WHERE subject_section_id = :subject_section_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':subject_section_id', $subject_section_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as an associative array

            return $result ? $result : []; // Return result or an empty array if no records found
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            throw new PDOException("Failed to get all terms: " . $e->getMessage());
        }
    }

    // Method to update an existing module
    public function updateModules($moduleData)
    {
        try {
            $this->conn->beginTransaction(); // Begin transaction

            $query = "UPDATE modules SET subject_section_id = :subject_section_id, title = :title WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':subject_section_id', $moduleData['subject_section_id']);
            $stmt->bindParam(':title', $moduleData['title']);
            $stmt->bindParam(':id', $moduleData['id'], PDO::PARAM_INT); // Bind the module ID

            $stmt->execute(); // Execute statement
            $this->conn->commit(); // Commit transaction

            return ["success" => true];
        } catch (PDOException $e) {
            $this->conn->rollBack(); // Rollback transaction on error
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    // Delete a module by ID
    public function deleteModule($module_id)
    {
        try {
            $this->conn->beginTransaction();

            // First, delete all related contents and their files for the module
            $queryDeleteFiles = "DELETE cf FROM content_files cf 
                                 JOIN contents c ON cf.content_id = c.id 
                                 WHERE c.module_id = :module_id";
            $stmtDeleteFiles = $this->conn->prepare($queryDeleteFiles);
            $stmtDeleteFiles->bindParam(':module_id', $module_id, PDO::PARAM_INT);
            $stmtDeleteFiles->execute();

            $queryDeleteContents = "DELETE FROM contents WHERE module_id = :module_id";
            $stmtDeleteContents = $this->conn->prepare($queryDeleteContents);
            $stmtDeleteContents->bindParam(':module_id', $module_id, PDO::PARAM_INT);
            $stmtDeleteContents->execute();

            // Finally, delete the module
            $query = "DELETE FROM modules WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $module_id, PDO::PARAM_INT);
            $stmt->execute();

            $this->conn->commit();
            return ["success" => true];
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    // Add content
    public function addContent($contentData)
    {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO contents (module_id, content_title, content_type, description, visibility, start_date, due_date, max_attempts, assignment_type, allow_late) 
                      VALUES (:module_id, :content_title, :content_type, :description, :visibility, :start_date, :due_date, :max_attempts, :assignment_type, :allow_late)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':module_id', $contentData['module_id']);
            $stmt->bindParam(':content_title', $contentData['content_title']);
            $stmt->bindParam(':content_type', $contentData['content_type']);
            $stmt->bindParam(':description', $contentData['description']);
            $stmt->bindParam(':visibility', $contentData['visibility']);
            $stmt->bindParam(':start_date', $contentData['start_date']);
            $stmt->bindParam(':due_date', $contentData['due_date']);
            $stmt->bindParam(':max_attempts', $contentData['max_attempts']);
            $stmt->bindParam(':assignment_type', $contentData['assignment_type']);
            $stmt->bindParam(':allow_late', $contentData['allow_late']);

            $stmt->execute();
            $this->conn->commit();

            return ["success" => true];
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    // Get contents by module ID
    public function getContentsByModule($module_id)
    {
        try {
            $query = "SELECT * FROM contents WHERE module_id = :module_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':module_id', $module_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result ? $result : [];
        } catch (PDOException $e) {
            throw new PDOException("Failed to get contents: " . $e->getMessage());
        }
    }

    // Update content
    public function updateContent($contentData)
    {
        try {
            $this->conn->beginTransaction();

            $query = "UPDATE contents 
                      SET content_title = :content_title, 
                          content_type = :content_type, 
                          description = :description, 
                          visibility = :visibility, 
                          start_date = :start_date, 
                          due_date = :due_date, 
                          max_attempts = :max_attempts, 
                          assignment_type = :assignment_type, 
                          allow_late = :allow_late 
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':content_title', $contentData['content_title']);
            $stmt->bindParam(':content_type', $contentData['content_type']);
            $stmt->bindParam(':description', $contentData['description']);
            $stmt->bindParam(':visibility', $contentData['visibility']);
            $stmt->bindParam(':start_date', $contentData['start_date']);
            $stmt->bindParam(':due_date', $contentData['due_date']);
            $stmt->bindParam(':max_attempts', $contentData['max_attempts']);
            $stmt->bindParam(':assignment_type', $contentData['assignment_type']);
            $stmt->bindParam(':allow_late', $contentData['allow_late']);
            $stmt->bindParam(':id', $contentData['id'], PDO::PARAM_INT);

            $stmt->execute();
            $this->conn->commit();

            return ["success" => true];
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    // Delete a content by ID
    public function deleteContent($content_id)
    {
        try {
            $this->conn->beginTransaction();

            // First, delete all related files for the content
            $queryDeleteFiles = "DELETE FROM content_files WHERE content_id = :content_id";
            $stmtDeleteFiles = $this->conn->prepare($queryDeleteFiles);
            $stmtDeleteFiles->bindParam(':content_id', $content_id, PDO::PARAM_INT);
            $stmtDeleteFiles->execute();

            // Then, delete the content
            $query = "DELETE FROM contents WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $content_id, PDO::PARAM_INT);
            $stmt->execute();

            $this->conn->commit();
            return ["success" => true];
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    // Add content file
    public function addContentFile($fileData)
    {
        try {
            $query = "INSERT INTO content_files (content_id, file_name, file_data) 
                      VALUES (:content_id, :file_name, :file_data)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':content_id', $fileData['content_id']);
            $stmt->bindParam(':file_name', $fileData['file_name']);
            $stmt->bindParam(':file_data', $fileData['file_data'], PDO::PARAM_LOB);

            $stmt->execute();

            return ["success" => true];
        } catch (PDOException $e) {
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    // Get files for a content
    public function getFilesByContent($content_id)
    {
        try {
            $query = "SELECT * FROM content_files WHERE content_id = :content_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':content_id', $content_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result ? $result : [];
        } catch (PDOException $e) {
            throw new PDOException("Failed to get files: " . $e->getMessage());
        }
    }

    // Delete a content file by ID
    public function deleteContentFile($file_id)
    {
        try {
            $query = "DELETE FROM content_files WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $file_id, PDO::PARAM_INT);
            $stmt->execute();

            return ["success" => true];
        } catch (PDOException $e) {
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    // Add a new student submission
    public function addSubmission($submissionData)
    {
        try {
            $this->conn->beginTransaction(); // Start transaction

            $query = "INSERT INTO student_submissions (content_id, student_id, attempt_number, submission_text, submission_date, status) 
                  VALUES (:content_id, :student_id, :attempt_number, :submission_text, :submission_date, :status)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':content_id', $submissionData['content_id']);
            $stmt->bindParam(':student_id', $submissionData['student_id']);
            $stmt->bindParam(':attempt_number', $submissionData['attempt_number']);
            $stmt->bindParam(':submission_text', $submissionData['submission_text']);
            $stmt->bindParam(':submission_date', $submissionData['submission_date']);
            $stmt->bindParam(':status', $submissionData['status']);

            $stmt->execute();
            $submissionId = $this->conn->lastInsertId(); // Get the ID of the new submission

            $this->conn->commit(); // Commit transaction
            return ['success' => true, 'submission_id' => $submissionId];
        } catch (PDOException $e) {
            $this->conn->rollBack(); // Rollback transaction on error
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Add a file to a submission
    public function addSubmissionFile($fileData)
    {
        try {
            $query = "INSERT INTO submission_files (submission_id, file_name, file_data) 
                  VALUES (:submission_id, :file_name, :file_data)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':submission_id', $fileData['submission_id']);
            $stmt->bindParam(':file_name', $fileData['file_name']);
            $stmt->bindParam(':file_data', $fileData['file_data'], PDO::PARAM_LOB); // Handle binary data

            $stmt->execute();
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Get all submissions by content id for all, and for specific student id so 2nd argument can be null
    public function getSubmissionsByContent($content_id, $student_id = null)
    {
        try {
            // Construct the query with optional student_id filtering
            $query = "SELECT * FROM student_submissions WHERE content_id = :content_id";

            // If a student_id is provided, add it to the query
            if ($student_id !== null) {
                $query .= " AND student_id = :student_id";
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':content_id', $content_id, PDO::PARAM_INT);

            // Bind student_id if it's provided
            if ($student_id !== null) {
                $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all submissions as an associative array
            return $result ? $result : [];
        } catch (PDOException $e) {
            throw new PDOException("Failed to get submissions: " . $e->getMessage());
        }
    }


    // Get files for a specific submission
    public function getFilesBySubmission($submission_id)
    {
        try {
            $query = "SELECT * FROM submission_files WHERE submission_id = :submission_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':submission_id', $submission_id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all files as an associative array
            return $result ? $result : [];
        } catch (PDOException $e) {
            throw new PDOException("Failed to get files: " . $e->getMessage());
        }
    }

    // Update the status of a submission
    public function updateSubmissionStatus($submission_id, $status)
    {
        try {
            $query = "UPDATE student_submissions SET status = :status WHERE id = :submission_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':submission_id', $submission_id, PDO::PARAM_INT);

            $stmt->execute();
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Delete a specific submission (and cascade to files)
    public function deleteSubmission($submission_id)
    {
        try {
            $query = "DELETE FROM student_submissions WHERE id = :submission_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':submission_id', $submission_id, PDO::PARAM_INT);

            $stmt->execute();
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Delete a specific file from a submission
    public function deleteSubmissionFile($file_id)
    {
        try {
            $query = "DELETE FROM submission_files WHERE id = :file_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':file_id', $file_id, PDO::PARAM_INT);

            $stmt->execute();
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }



}
?>