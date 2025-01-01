<?php
class Quiz
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // public function getQuestionByQuestionID($quiz_question_id)
    // {
    //     try {
    //         // Fetch the question by ID
    //         $query = "SELECT * FROM quiz_questions WHERE quiz_question_id = :quiz_question_id";
    //         $stmt = $this->conn->prepare($query);
    //         $stmt->bindParam(':quiz_question_id', $quiz_question_id);
    //         $stmt->execute();

    //         $question = $stmt->fetch(PDO::FETCH_ASSOC);

    //         if (!$question) {
    //             throw new Exception("Question not found");
    //         }

    //         // Fetch additional details based on question type
    //         $question_type = $question['question_type'];
    //         $quiz_question_id = $question['quiz_question_id']; // Assuming this is the ID column in the question table.

    //         if ($question_type === 'MCQ' || $question_type === 'TRUE_FALSE') {
    //             // Fetch choices for MCQ or TRUE_FALSE
    //             $query = "SELECT * FROM quiz_question_options WHERE quiz_question_id = :quiz_question_id";
    //             $stmt = $this->conn->prepare($query);
    //             $stmt->bindParam(':quiz_question_id', $quiz_question_id);
    //             $stmt->execute();
    //             $question['choices'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //         } elseif ($question_type === 'FILL_IN_THE_BLANKS') {
    //             // Fetch the correct answer for FILL_IN_THE_BLANKS
    //             $query = "SELECT option_text FROM quiz_question_options WHERE quiz_question_id = :quiz_question_id AND is_correct = 1";
    //             $stmt = $this->conn->prepare($query);
    //             $stmt->bindParam(':quiz_question_id', $quiz_question_id);
    //             $stmt->execute();
    //             $correct_answer = $stmt->fetch(PDO::FETCH_ASSOC);

    //             // Add the correct answer text to the result
    //             $question['correct_answer'] = $correct_answer ? $correct_answer['option_text'] : '';
    //         }

    //         return $question;
    //     } catch (Exception $e) {
    //         throw new Exception($e->getMessage());
    //     }
    // }


    public function getQuestionsByContentID($content_id)
    {
        try {
            $query = "SELECT * FROM quiz_questions WHERE content_id = :content_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':content_id', $content_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function deleteQuestionByQuizQuestionID($quiz_question_id)
    {
        try {
            // SQL query to delete the quiz question based on the quiz_question_id
            $query = "DELETE FROM quiz_questions WHERE quiz_question_id = :quiz_question_id";

            // Prepare the statement
            $stmt = $this->conn->prepare($query);

            // Bind the quiz_question_id to the query parameter
            $stmt->bindParam(':quiz_question_id', $quiz_question_id);

            // Execute the statement
            $stmt->execute();

            // Check if the row was deleted
            if ($stmt->rowCount() > 0) {
                return true;  // Success
            } else {
                return false;  // No rows were deleted (quiz_question_id not found)
            }
        } catch (Exception $e) {
            // Handle any exceptions (errors) that occur
            throw new Exception($e->getMessage());
        }
    }


}
