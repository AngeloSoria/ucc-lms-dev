<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Functions']['PHPLogger']);
require_once(FILE_PATHS['Controllers']['GeneralLogs']);

use Dotenv\Dotenv;

class ModuleContent
{
    private $conn;
    private $generalLogsController;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(BASE_PATH);
        $dotenv->load();

        $db = new Database();
        $this->conn = $db->getConnection();
        $this->generalLogsController = new GeneralLogsController();
    }


    // ===============================================
    // MODULES
    // ===============================================
    public function addModule($moduleData)
    {
        try {
            $this->conn->beginTransaction(); // Begin transaction

            $query = "INSERT INTO modules (subject_section_id, title, visibility) VALUES (:subject_section_id, :title, :visibility)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':subject_section_id', $moduleData['subject_section_id']);
            $stmt->bindParam(':title', $moduleData['title']);
            $stmt->bindParam(':visibility', $moduleData['visibility']);

            $stmt->execute(); // Execute statement

            $this->generalLogsController->addLog_CREATE($_SESSION['user_id'], $_SESSION['role'], "Created a module for subject_section_id (" . $moduleData['subject_section_id'] . ")");

            $this->conn->commit(); // Commit transaction

            return ["success" => true];
        } catch (PDOException $e) {
            $this->conn->rollBack(); // Rollback transaction on error
            return ['success' => false, "message" => $e->getMessage()];
        }
    }

    // Multiple modules
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

    public function getNumberOfModulesBySubjectSectionId($subject_section_id)
    {
        try {
            $query = "SELECT COUNT(*) FROM modules WHERE subject_section_id = :subject_section_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":subject_section_id", $subject_section_id, PDO::PARAM_INT); // Bind as integer for better type safety
            $stmt->execute();

            // Use fetchColumn for a single value
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            // Optionally log or handle the error here
            throw new Exception("Error fetching module count: " . $e->getMessage());
        }
    }


    // Single module
    public function getModuleByModuleId($module_id)
    {
        try {
            $query = "SELECT * FROM modules WHERE module_id = :module_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':module_id', $module_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as an associative array

            return $result ? $result : []; // Return result or an empty array if no records found
        } catch (PDOException $e) {
            // Log the error or handle it as needed
            throw new PDOException("Failed to get all terms: " . $e->getMessage());
        }
    }

    // Method to update an existing module
    public function updateModule($moduleData)
    {
        try {
            $this->conn->beginTransaction(); // Begin transaction

            $query = "UPDATE modules SET subject_section_id = :subject_section_id, title = :title, visibility = :visibility WHERE module_id = :module_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':subject_section_id', $moduleData['subject_section_id']);
            $stmt->bindParam(':title', $moduleData['title']);
            $stmt->bindParam(':visibility', $moduleData['visibility']);
            $stmt->bindParam(':module_id', $moduleData['module_id'], PDO::PARAM_INT); // Bind the module ID

            $stmt->execute(); // Execute statement
            $this->conn->commit(); // Commit transaction

            $this->generalLogsController->addLog_UPDATE($_SESSION['user_id'], $_SESSION['role'], "Updated the information of the module (" . $moduleData['module_id'] . ")");

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

            $query = "DELETE FROM modules WHERE module_id = :module_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':module_id', $module_id, PDO::PARAM_INT);
            $stmt->execute();

            $this->generalLogsController->addLog_DELETE($_SESSION['user_id'], $_SESSION['role'], "Deleted the module ($module_id)");

            $this->conn->commit();
            return ["success" => true];
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return ['success' => false, "message" => $e->getMessage()];
        }
    }



    // ===============================================
    // MODULE CONTENTS
    // ===============================================

    // Add content
    public function addContent($contentData)
    {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO contents (module_id, content_title, content_type, description, visibility, start_date, due_date, max_attempts, assignment_type, allow_late, max_score) 
                      VALUES (:module_id, :content_title, :content_type, :description, :visibility, :start_date, :due_date, :max_attempts, :assignment_type, :allow_late, :max_score)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':module_id', $contentData['module_id']);
            $stmt->bindParam(':content_title', $contentData['content_title']);
            $stmt->bindParam(':content_type', $contentData['content_type']);
            $stmt->bindParam(':description', ($contentData['description']));
            $stmt->bindParam(':visibility', $contentData['visibility']);
            $stmt->bindParam(':start_date', $contentData['start_date']);
            $stmt->bindParam(':due_date', $contentData['due_date']);
            $stmt->bindParam(':max_attempts', $contentData['max_attempts']);
            $stmt->bindParam(':assignment_type', $contentData['assignment_type']);
            $stmt->bindParam(':allow_late', $contentData['allow_late']);
            $stmt->bindParam(':max_score', $contentData['max_score']);

            $stmt->execute();

            // Get the last inserted ID
            if (!in_array($contentData['content_type'], ['quiz'])) {
                $contentId = $this->conn->lastInsertId();
                $query_contentFiles = "
                INSERT INTO content_files
                (content_id, file_name, file_data, mime_type)
                VALUES
                (:content_id, :file_name, :file_data, :mime_type)";

                $stmt = $this->conn->prepare($query_contentFiles);

                foreach ($_FILES['input_contentFiles']['tmp_name'] as $key => $tmpName) {
                    // Get the file details for each file using the index $key
                    $fileName = $_FILES['input_contentFiles']['name'][$key];
                    $fileType = $_FILES['input_contentFiles']['type'][$key];
                    $fileSize = $_FILES['input_contentFiles']['size'][$key];
                    $fileError = $_FILES['input_contentFiles']['error'][$key];

                    // Identify File Size
                    $maxFileSize = $_ENV['MAX_UPLOAD_FILE_SIZE'] * 1024 * 1024;
                    if ($fileSize > $maxFileSize) {
                        msgLog("FILE SIZE EXCEEDED", "$fileSize >> $maxFileSize");
                        throw new Exception("File too large. (Max: $maxFileSize)");
                    }

                    // Check for any file upload errors
                    if ($fileError === UPLOAD_ERR_OK) {
                        // Read file content as binary data using streaming
                        $handle = fopen($tmpName, 'rb'); // Open the temporary file
                        if ($handle === false) {
                            throw new Exception("Failed to open the uploaded file.");
                        }

                        $fileData = '';
                        while (!feof($handle)) {
                            $chunk = fread($handle, 8192); // Read the file in 8KB chunks
                            if ($chunk === false) {
                                throw new Exception("Error reading the uploaded file.");
                            }
                            $fileData .= $chunk; // Append each chunk to the file data
                        }
                        fclose($handle); // Close the file handle

                        // Verify the data size matches the uploaded file size
                        if (strlen($fileData) !== $fileSize) {
                            throw new Exception("File data size mismatch: expected $fileSize bytes, got " . strlen($fileData) . " bytes.");
                        }

                        // Execute the database insert query
                        $stmt->execute([
                            ':content_id' => $contentId,
                            ':file_name' => $fileName,
                            ':file_data' => $fileData,
                            ':mime_type' => $fileType,
                        ]);
                    } else {
                        // Handle file upload errors if needed
                        throw new Exception("Error uploading file: " . $fileError);
                    }
                }
            }

            $this->conn->commit();
            return ["success" => true];
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    // Get content by content ID
    public function getContentById($content_id)
    {
        try {
            $query = "SELECT * FROM contents WHERE content_id = :content_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':content_id', $content_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result ? $result : [];
        } catch (PDOException $e) {
            throw new PDOException("Failed to get contents: " . $e->getMessage());
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

    public function getAllContentsFromSubjectSection($subject_section_id)
    {
        try {
            $query = "
                SELECT 
                    c.*,
                    m.module_id,
                    m.title AS module_title
                FROM 
                    contents c
                JOIN 
                    modules m
                ON
                    c.module_id = m.module_id
                JOIN
                    subject_section ss
                ON
                    m.subject_section_id = :subject_section_id
                WHERE
                    c.content_type = 'assignment' OR c.content_type = 'quiz';
            ";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":subject_section_id", $subject_section_id);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
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

    public function updateContentVisibility($contentData)
    {
        try {
            $this->conn->beginTransaction();

            // Get the current visibility value
            $query1 = "SELECT visibility FROM contents WHERE content_id = :content_id AND module_id = :module_id";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bindParam(":content_id", $contentData['content_id']);
            $stmt1->bindParam(":module_id", $contentData['module_id']);
            $stmt1->execute();

            $currentVisibility = $stmt1->fetchColumn();

            // Toggle the visibility value
            if ($currentVisibility === 'shown') {
                $newVisibility = 'hidden';
            } elseif ($currentVisibility === 'hidden') {
                $newVisibility = 'shown';
            } else {
                throw new Exception("Invalid visibility value in the database. | $currentVisibility");
            }

            // Update the visibility value
            $query2 = "UPDATE contents SET visibility = :visibility WHERE content_id = :content_id AND module_id = :module_id";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(":visibility", $newVisibility);
            $stmt2->bindParam(":content_id", $contentData['content_id']);
            $stmt2->bindParam(":module_id", $contentData['module_id']);
            $stmt2->execute();

            $this->conn->commit();
            return ['success' => true];
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception("Failed to update visibility: " . $e->getMessage());
        }
    }


    // Delete a content by ID
    public function deleteContent($contentData)
    {
        try {
            $this->conn->beginTransaction();

            // First, delete all related files for the content
            $queryDeleteFiles = "DELETE FROM contents WHERE content_id = :content_id AND module_id = :module_id";
            $stmtDeleteFiles = $this->conn->prepare($queryDeleteFiles);
            $stmtDeleteFiles->bindParam(':content_id', $contentData['content_id'], PDO::PARAM_INT);
            $stmtDeleteFiles->bindParam(':module_id', $contentData['module_id'], PDO::PARAM_INT);
            $stmtDeleteFiles->execute();

            $this->conn->commit();
            return ["success" => true];
        } catch (Exception $e) {
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
            $stmt->bindParam(':content_id', $content_id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ? $result : [];
        } catch (PDOException $e) {
            throw new PDOException("Failed to get files: " . $e->getMessage());
        }
    }

    // Get files for a content
    public function getFileByContentFileId($content_id, $content_file_id)
    {
        try {
            $query = "SELECT * FROM content_files WHERE content_id = :content_id AND content_file_id = :content_file_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':content_id', $content_id);
            $stmt->bindParam(':content_file_id', $content_file_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result : [];
        } catch (PDOException $e) {
            throw new PDOException("Failed to get file: " . $e->getMessage());
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

    // ===============================================
    // SUBMISSIONS
    // ===============================================

    // Add a new student submission
    public function addSubmission(array $submissionData, array $fileInputs)
    {
        try {
            // Start the transaction
            $this->conn->beginTransaction();

            // Insert the submission record
            $query_submission = "
        INSERT INTO student_submissions (content_id, student_id, attempt_number, submission_text, submission_date, status)
        VALUES (:content_id, :student_id, :attempt_number, :submission_text, NOW(), 'submitted')";
            $stmt = $this->conn->prepare($query_submission);

            // Get latest attempt
            $lastSubmitted = $this->getLatestSubmission($submissionData['content_id'], $submissionData['student_id']);
            if ($lastSubmitted) {
                $submissionData["attempt_number"] = (int) $lastSubmitted['attempt_number'] + 1;
            } else {
                $submissionData["attempt_number"] = 1;
            }

            // Check if submit count exceeded the max attempts.
            $contentInfo = $this->getContentById($submissionData['content_id']);
            if ($contentInfo) {
                if ($submissionData["attempt_number"] > $contentInfo[0]['max_attempts']) {
                    throw new Exception("Submission exceeded the max attempt." . json_encode($contentInfo[0]['max_attempts']));
                }
            } else {
                throw new Exception("Error retrieving content data.");
            }

            $stmt->execute([
                ':content_id' => $submissionData['content_id'],
                ':student_id' => $submissionData['student_id'],
                ':attempt_number' => $submissionData['attempt_number'],
                ':submission_text' => $submissionData['submission_text'],
            ]);

            // Get the last inserted submission ID
            $submissionId = $this->conn->lastInsertId();

            // Process and add submission files
            if (!empty($fileInputs)) {
                $this->addSubmissionFiles($submissionId, $fileInputs);
            }

            // Commit the transaction
            $this->conn->commit();
        } catch (Exception $e) {
            // Rollback on error
            $this->conn->rollBack();
            throw new Exception("Error adding submission: " . $e->getMessage());
        }
    }


    // Add a file to a submission
    private function addSubmissionFiles(int $submissionId, array $fileInputs)
    {
        // Prepare the SQL query for file insertion
        $query_submissionFiles = "
    INSERT INTO submission_files (submission_id, file_name, file_data, mime_type)
    VALUES (:submission_id, :file_name, :file_data, :mime_type)";
        $stmt = $this->conn->prepare($query_submissionFiles);

        // Max file size in bytes (from environment configuration)
        $maxFileSize = $_ENV['MAX_UPLOAD_FILE_SIZE'] * 1024 * 1024;

        foreach ($fileInputs['error'] as $key => $fileError) {
            if ($fileError === UPLOAD_ERR_OK) {
                // Retrieve file details
                $fileName = $fileInputs['name'][$key];
                $tmpName = $fileInputs['tmp_name'][$key];
                $fileType = $fileInputs['type'][$key];
                $fileSize = $fileInputs['size'][$key];

                // Validate file size
                if ($fileSize > $maxFileSize) {
                    throw new Exception("File too large: $fileSize bytes (Max: $maxFileSize bytes)");
                }

                // Read the file content as binary data
                $fileData = file_get_contents($tmpName);
                if ($fileData === false) {
                    throw new Exception("Error reading file: $fileName");
                }

                // Execute the database insert query
                $stmt->execute([
                    ':submission_id' => $submissionId,
                    ':file_name' => $fileName,
                    ':file_data' => $fileData,
                    ':mime_type' => $fileType,
                ]);
            } else {
                // Handle file upload error
                $errorMessage = match ($fileError) {
                    UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
                    UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
                    UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                    UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
                    default => 'Unknown error occurred.',
                };
                throw new Exception("File upload error: $errorMessage");
            }
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
            $stmt->bindParam(':submission_id', $submission_id);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all files as an associative array

            return $result ? $result : [];
        } catch (PDOException $e) {
            throw new PDOException("Failed to get files: " . $e->getMessage());
        }
    }
    public function getFileBySubmissionFilesId($submission_files_id)
    {
        try {
            $query = "SELECT * FROM submission_files WHERE submission_files_id = :submission_files_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':submission_files_id', $submission_files_id);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch all files as an associative array

            return $result ? $result : [];
        } catch (PDOException $e) {
            throw new PDOException("Failed to get file: " . $e->getMessage());
        }
    }

    public function getLatestSubmission($content_id, $student_id)
    {
        try {
            $query = "SELECT * FROM student_submissions WHERE content_id = :content_id AND student_id = :student_id ORDER BY submission_date DESC LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":content_id", $content_id);
            $stmt->bindParam(":student_id", $student_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Failed to get the latest submission: " . $e->getMessage());
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

    public function getStudentsSubmission($content_id)
    {
        try {
            $query = "
            SELECT
                u.user_id,
                u.profile_pic,
                u.first_name,
                u.last_name,
                ss.submission_date,
                ss.content_id,
                ss.submission_id,
                ss.status,
                ss.score,
                c.*
            FROM
                student_subject_section AS sss
            JOIN
                users AS u
            ON
                sss.user_id = u.user_id
            JOIN
                modules AS m
            ON
                sss.subject_section_id = m.subject_section_id
            JOIN
                contents AS c
            ON
                m.module_id = c.module_id
            LEFT JOIN
                student_submissions AS ss
            ON
                ss.student_id = u.user_id AND ss.content_id = c.content_id
            WHERE
                c.content_id = :content_id
            AND (
                ss.submission_date IS NULL OR
                ss.submission_date = (
                    SELECT MAX(submission_date)
                    FROM student_submissions
                    WHERE student_id = ss.student_id AND content_id = ss.content_id
                )
            )
            GROUP BY
                c.content_id, u.user_id; -- Ensure uniqueness by content_id and user_id
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":content_id", $content_id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
