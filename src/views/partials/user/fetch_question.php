<?php
require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);

header('Content-Type: application/json');

$quiz_question_id = $_GET['quiz_question_id'] ?? null;

if (!$quiz_question_id) {
    echo json_encode(['success' => false, 'message' => 'Question ID is required.']);
    exit;
}

try {
    $db = (new Database())->getConnection();

    $question = fetchQuestionDetails($db, $quiz_question_id);
    if (!$question) {
        echo json_encode(['success' => false, 'message' => 'Question not found.']);
        exit;
    }

    $choices = fetchQuestionOptions($db, $quiz_question_id);
    echo json_encode(['success' => true, 'data' => compact('question', 'choices')]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
