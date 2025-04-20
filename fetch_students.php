<?php
include '../includes/db.php';

if (isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];
    $sql = "SELECT students.id, students.name, 
                   COALESCE(attendance_summary.attendance_percentage, 0) AS attendance_percentage 
            FROM students 
            LEFT JOIN attendance_summary ON students.id = attendance_summary.student_id 
            WHERE students.class_id = '$class_id'";

    $result = $conn->query($sql);
    $students = [];

    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    echo json_encode($students);
}
?>
