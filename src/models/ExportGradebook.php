<?php
require_once __DIR__ . '../../config/PathsHandler.php';
require_once VENDOR_AUTO_LOAD;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function exportGradebook($gradebookData)
{
    // session_start();
    // ob_start(); // Start output buffering

    $subject_section_id = $gradebookData['subject_section']['id'];
    $subject_name = $gradebookData['subject_section']['subject_name'];
    $contents = $gradebookData['gradebook_data']['contents'];
    $students = $gradebookData['gradebook_data']['students'];

    $exportFileName = "gradebook222.xlsx"; // File name for download

    // Create new spreadsheet object
    // $spreadsheet = new Spreadsheet();
    // $sheet = $spreadsheet->getActiveSheet();

    // // Add a bold header
    // $sheet->setCellValue('A1', 'STUDENTS');
    // $sheet->getStyle('A1')->getFont()->setBold(true);
    // $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

    $row = 2; // Start inserting students below the header
    // foreach ($students as $student) {
    //     $sheet->setCellValue("A$row", $student['student_name']);
    //     $sheet->setCellValue("B$row", "Score here"); // Placeholder score
    //     $row++;
    // }


    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Hello World !');
    $writer = new Xlsx($spreadsheet);
    $writer->save('hello world.xlsx');

    // Headers for Excel download
    // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // header('Content-Disposition: attachment; filename="test.xlsx"');
    header('Cache-Control: no-store, no-cache, must-revalidate'); // Prevent caching
    header('Pragma: no-cache');
    header('Expires: 0');
    // // Clear the output buffer to avoid corrupting the file
    // if (ob_get_length()) {
    //     ob_end_clean();
    // }

    // try {
    //     // Create writer object and output to browser
    //     $writer = new Xlsx($spreadsheet);
    //     $writer->save('php://output'); // Send to browser
    // } catch (Exception $e) {
    //     // Handle any errors
    //     echo 'Error: ', $e->getMessage();
    //     exit;
    // }

    exit;
}
