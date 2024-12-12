<?php
require_once __DIR__ . '../../config/PathsHandler.php';
require_once VENDOR_AUTO_LOAD;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Add headers
$sheet->setCellValue('A1', 'Student Name');
$sheet->setCellValue('B1', 'Assignment Title');
$sheet->setCellValue('C1', 'Score');

// Populate with data
$row = 2;
foreach ($students as $student) {
    foreach ($contents as $content) {
        $sheet->setCellValue("A{$row}", $student['student_name']);
        $sheet->setCellValue("B{$row}", $content['content_title']);
        $sheet->setCellValue("C{$row}", $submissionData[$student['user_id']][$content['content_id']]['score'] ?? '');
        $row++;
    }
}

// Set headers to force download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="gradebook.xlsx"');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
