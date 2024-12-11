<?php
require_once(__DIR__ . '../../../src/config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(MODELS . 'Quiz.php');

class QuizController
{
    private $db;
    private $quizModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->quizModel = new Quiz($this->db);
    }

    public function getQuestionsByContentID($content_id)
    {
        try {
            $getQuestionsResult = $this->quizModel->getQuestionsByContentID($content_id);
            if ($getQuestionsResult) {
                return ['success' => true, 'data' => $getQuestionsResult];
            } else {
                return ['success' => false, 'message' => "No questions found with id of ($content_id)"];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // public function getQuestionByQuestionID($quiz_question_id)
    // {
    //     try {
    //         // Fetch question details from the model
    //         $questionDetails = $this->quizModel->getQuestionByQuestionID($quiz_question_id);

    //         if ($questionDetails) {
    //             return [
    //                 'success' => true,
    //                 'data' => $questionDetails
    //             ];
    //         } else {
    //             return [
    //                 'success' => false,
    //                 'message' => "No question found with ID ($quiz_question_id)"
    //             ];
    //         }
    //     } catch (Exception $e) {
    //         return [
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ];
    //     }
    // }


    public function deleteQuestion($quiz_question_id)
    {
        try {
            // Call the model's deleteQuestion method to delete the question
            $deleteResult = $this->quizModel->deleteQuestionByQuizQuestionID($quiz_question_id);

            if ($deleteResult) {
                return ['success' => true, 'message' => "Question deleted successfully."];
            } else {
                return ['success' => false, 'message' => "Failed to delete question with id ($quiz_question_id)."];
            }
        } catch (Exception $e) {
            // Handle any exceptions and return an error message
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
