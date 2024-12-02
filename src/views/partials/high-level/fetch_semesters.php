<?php

require_once(__DIR__ . '../../../../config/PathsHandler.php');
require_once(FILE_PATHS['DATABASE']);
require_once(FILE_PATHS['Functions']["PHPLogger"]);

$database = new Database();
$pdo = $database->getConnection();

try {
    // Fetch all academic periods
    $stmt = $pdo->prepare("SELECT * 
                           FROM academic_period 
                           ORDER BY academic_year_start, semester");
    $stmt->execute();
    $semesters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $options = '';
    $activeSemester = null;

    // Find the active semester
    $stmtActive = $pdo->prepare("SELECT * 
                                 FROM academic_period 
                                 WHERE is_active = 1 
                                 ORDER BY academic_year_start, semester 
                                 LIMIT 1");
    $stmtActive->execute();
    $activeSemester = $stmtActive->fetch(PDO::FETCH_ASSOC);

    if ($activeSemester) {
        // Case: Active semester exists
        $academicYearStart = $activeSemester['academic_year_start'];
        $academicYearEnd = $activeSemester['academic_year_end'];

        // Add only the active semester to the options
        $options .= '<option value="' . $activeSemester['period_id'] . '">' .
            ($activeSemester['semester'] == 1 ? '1st Semester' : '2nd Semester') .
            ' (' . $academicYearStart . '-' . $academicYearEnd . ')</option>';

        // Check if it's the 1st or 2nd semester and add the next semester
        if ($activeSemester['semester'] == 1) {
            // Add the 2nd Semester of the same academic year
            $stmtNext = $pdo->prepare("SELECT * 
                                       FROM academic_period 
                                       WHERE semester = 2 AND academic_year_start = :current_year 
                                       LIMIT 1");
            $stmtNext->execute(['current_year' => $academicYearStart]);
            $nextSemester = $stmtNext->fetch(PDO::FETCH_ASSOC);

            if ($nextSemester) {
                $options .= '<option value="' . $nextSemester['period_id'] . '">' .
                    '2nd Semester (' . $nextSemester['academic_year_start'] . '-' . $nextSemester['academic_year_end'] . ')</option>';
            }
        } elseif ($activeSemester['semester'] == 2) {
            // Add the 1st Semester of the next academic year
            $stmtNext = $pdo->prepare("SELECT * 
                                       FROM academic_period 
                                       WHERE semester = 1 AND academic_year_start = :next_year 
                                       LIMIT 1");
            $stmtNext->execute(['next_year' => $academicYearEnd]);
            $nextSemester = $stmtNext->fetch(PDO::FETCH_ASSOC);

            if ($nextSemester) {
                $options .= '<option value="' . $nextSemester['period_id'] . '">' .
                    '1st Semester (' . $nextSemester['academic_year_start'] . '-' . $nextSemester['academic_year_end'] . ')</option>';
            }
        }

    } else {
        // Case: No active semester, find the nearest upcoming semester based on current date and academic year
        $currentDate = date('Y-m-d'); // Current date for comparison

        // Find upcoming semesters after the current date
        $stmtUpcoming = $pdo->prepare("SELECT * 
                                       FROM academic_period 
                                       WHERE (academic_year_start > :current_year OR 
                                              (academic_year_start = :current_year AND start_date >= :current_date)) 
                                       ORDER BY academic_year_start, semester");
        $stmtUpcoming->execute(['current_year' => date('Y'), 'current_date' => $currentDate]);
        $upcomingSemesters = $stmtUpcoming->fetchAll(PDO::FETCH_ASSOC);

        if ($upcomingSemesters) {
            // Track added semesters to avoid duplicates
            $addedSemesters = [];
            // Get the first upcoming semester
            $firstSemester = $upcomingSemesters[0];
            $academicYearStart = $firstSemester['academic_year_start'];
            $academicYearEnd = $firstSemester['academic_year_end'];

            // Add the first semester
            $options .= '<option value="' . $firstSemester['period_id'] . '">' .
                ($firstSemester['semester'] == 1 ? '1st Semester' : '2nd Semester') .
                ' (' . $academicYearStart . '-' . $academicYearEnd . ')</option>';
            $addedSemesters[] = $firstSemester['period_id'];

            // Now, add the next semester, if available
            if ($firstSemester['semester'] == 1) {
                // Add the 2nd Semester of the same academic year
                $stmtNext = $pdo->prepare("SELECT * 
                                           FROM academic_period 
                                           WHERE semester = 2 AND academic_year_start = :current_year 
                                           LIMIT 1");
                $stmtNext->execute(['current_year' => $academicYearStart]);
                $nextSemester = $stmtNext->fetch(PDO::FETCH_ASSOC);

                if ($nextSemester && !in_array($nextSemester['period_id'], $addedSemesters)) {
                    $options .= '<option value="' . $nextSemester['period_id'] . '">' .
                        '2nd Semester (' . $nextSemester['academic_year_start'] . '-' . $nextSemester['academic_year_end'] . ')</option>';
                    $addedSemesters[] = $nextSemester['period_id'];
                }
            } elseif ($firstSemester['semester'] == 2) {
                // If it's the 2nd semester, add the next academic year's 1st semester
                $stmtNext = $pdo->prepare("SELECT * 
                                           FROM academic_period 
                                           WHERE semester = 1 AND academic_year_start = :next_year 
                                           LIMIT 1");
                $stmtNext->execute(['next_year' => $academicYearEnd]);
                $nextSemester = $stmtNext->fetch(PDO::FETCH_ASSOC);

                if ($nextSemester && !in_array($nextSemester['period_id'], $addedSemesters)) {
                    $options .= '<option value="' . $nextSemester['period_id'] . '">' .
                        '1st Semester (' . $nextSemester['academic_year_start'] . '-' . $nextSemester['academic_year_end'] . ')</option>';
                    $addedSemesters[] = $nextSemester['period_id'];
                }
            }
        } else {
            // If no upcoming semesters are found, add a default message
            $options = '<option value="" disabled>No Semesters Available</option>';
        }
    }

    // Check if options is empty after processing
    if (empty($options)) {
        $options = '<option value="" disabled>No Semesters Available</option>';
    }

    echo $options;

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
