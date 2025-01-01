<?php
require_once __DIR__ . '../../config/PathsHandler.php';
require_once FILE_PATHS['DATABASE'];

header('Content-Type: application/json');

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$studentId = $_POST['student_id'] ?? null;
$contentId = $_POST['content_id'] ?? null;
$score = $_POST['score'] ?? null;

// Validate input
if (!$studentId || !$contentId || !is_numeric($score) || $score < 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid input. Ensure all fields are filled correctly.']);
    exit;
}

$senderId = $_POST['sender_id']; // Get the teacher's user_id from the session

try {
    $db = new Database();
    $pdo = $db->getConnection();
    // Find the latest attempt number for the student and content_id
    $stmt = $pdo->prepare("SELECT MAX(attempt_number) AS max_attempt, score FROM student_submissions WHERE student_id = ? AND content_id = ?");
    $stmt->execute([$studentId, $contentId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no submission exists, return an error
    if (!$result['max_attempt']) {

        $stmt = $pdo->prepare("INSERT INTO student_submissions 
                                        (content_id, student_id, attempt_number, status, score, graded_date)
                                        VALUES
                                        (?, ?, 1, ?, ?, CURRENT_TIMESTAMP)");

        $stmt->execute([$contentId, $studentId, 'graded', $score]);

        // Fetch content details (content_type, content_title, subject_name)
        $stmt = $pdo->prepare("SELECT c.content_type, c.content_title, s.subject_name
                                        FROM contents c
                                        JOIN modules m ON c.module_id = m.module_id
                                        JOIN subject_section ss ON m.subject_section_id = ss.subject_section_id
                                        JOIN subjects s ON ss.subject_id = s.subject_id
                                        WHERE c.content_id = ?");
        $stmt->execute([$contentId]);
        $contentDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($contentDetails) {
            $contentType = $contentDetails['content_type'];
            $contentTitle = $contentDetails['content_title'];
            $subjectName = $contentDetails['subject_name'];

            // Format the notification message
            $notificationMessage = "Graded: $contentType '$contentTitle' in subject '$subjectName'";

            // Insert the notification for the student
            $stmt = $pdo->prepare("INSERT INTO notifications (user_id, content_id, message, sender_id, is_read, created_at) VALUES (?, ?, ?, ?, 0, NOW())");
            $stmt->execute([$studentId, $contentId, $notificationMessage, $senderId]); // Use senderId from session

            echo json_encode(['success' => true, 'message' => 'Score updated successfully and notification sent.']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Content details not found.']);
            exit;
        }
    }

    $latestAttemptNumber = $result['max_attempt']; // Get the latest attempt number
    $currentScore = $result['score']; // Get the current score

    // If the score hasn't changed, no need to update or notify
    if ($score == $currentScore) {
        echo json_encode(['success' => true, 'message' => 'Score is the same, no update needed.']);
        exit;
    }

    // Set the current date and time for graded_date
    $gradedDate = date('Y-m-d H:i:s');

    // Update the latest submission (most recent attempt) with the new score
    $stmt = $pdo->prepare("UPDATE student_submissions SET score = ?, status = 'graded', graded_date = ? WHERE student_id = ? AND content_id = ? AND attempt_number = ?");
    $stmt->execute([$score, $gradedDate, $studentId, $contentId, $latestAttemptNumber]);

    // Fetch content details (content_type, content_title, subject_name)
    $stmt = $pdo->prepare("SELECT c.content_type, c.content_title, s.subject_name
                                                FROM contents c
                                                JOIN modules m ON c.module_id = m.module_id
                                                JOIN subject_section ss ON m.subject_section_id = ss.subject_section_id
                                                JOIN subjects s ON ss.subject_id = s.subject_id
                                                WHERE c.content_id = ?");
    $stmt->execute([$contentId]);
    $contentDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($contentDetails) {
        $contentType = $contentDetails['content_type'];
        $contentTitle = $contentDetails['content_title'];
        $subjectName = $contentDetails['subject_name'];

        // Format the notification message
        $notificationMessage = "Graded: $contentType '$contentTitle' in subject '$subjectName'";

        // Insert the notification for the student
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, content_id, message, sender_id, is_read, created_at) VALUES (?, ?, ?, ?, 0, NOW())");
        $stmt->execute([$studentId, $contentId, $notificationMessage, $senderId]); // Use senderId from session

        echo json_encode(['success' => true, 'message' => 'Score updated successfully and notification sent.']);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Content details not found.']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error updating score: ' . $e->getMessage()]);
    exit;
}
