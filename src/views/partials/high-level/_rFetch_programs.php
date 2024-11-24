<?php
// FETCH programs by educational_level

header('Content-Type: application/json');

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['Controllers']['Program']);
require_once(FILE_PATHS['Functions']['PHPLogger']);

$programController = new ProgramController();

if (isset($_POST['educational_level'])) {
    // fetch request
    $fetchProgramsResult = $programController->getAllProgramsByEducationalLevel($_POST['educational_level']);

    if ($fetchProgramsResult['success']) {
        echo json_encode(['data' => $fetchProgramsResult['data']]);
    } else {
        // http_response_code(404);
        msgLog("POST AJAX", $fetchProgramsResult['message']);
        echo json_encode(['error' => $fetchProgramsResult['message']]);
    }
}
