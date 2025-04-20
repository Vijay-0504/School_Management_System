<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
}
include '../includes/db.php';

// Fetch Attendance Data (Detailed)
$attendanceQuery = "SELECT students.id AS student_id, students.name, attendance.date, attendance.status
                    FROM attendance
                    JOIN students ON attendance.student_id = students.id
                    ORDER BY students.id, attendance.date";
$attendanceResult = $conn->query($attendanceQuery);

// Store Attendance Data
$attendanceData = [];
while ($row = $attendanceResult->fetch_assoc()) {
    $attendanceData[$row['student_id']][] = $row;
}

// Fetch Attendance Summary
$attendanceSummaryQuery = "SELECT student_id, total_classes, attended_classes, attendance_percentage FROM attendance_summary";
$attendanceSummaryResult = $conn->query($attendanceSummaryQuery);

// Store Attendance Summary Data
$attendanceSummaryData = [];
while ($row = $attendanceSummaryResult->fetch_assoc()) {
    $attendanceSummaryData[$row['student_id']] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Reports</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Dark theme styling */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #121212, #1a1a2e);
            color: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* Navbar Styling (as in previous pages) */
        .navbar {
            background-color: #333;
            overflow: hidden;
            padding: 10px 0;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-weight: bold;
        }

        .navbar a:hover {
            background-color: #575757;
        }

        .navbar a:last-child {
            float: right;
            background-color: red;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.7);
            box-shadow: 0px 0px 20px rgba(0, 255, 255, 0.2);
            border-radius: 10px;
            text-align: center;
        }

        h2, h3 {
            text-align: center;
            color: #00eaff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            border: 1px solid #00eaff;
            text-align: left;
            color: #ffffff;
        }

        th {
            background: linear-gradient(90deg, #00eaff, #0066ff);
            color: #000;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.1);
        }

        tr:hover {
            background: rgba(0, 255, 255, 0.2);
        }

        /* Mobile responsive */
        @media (max-width: 600px) {
            table {
                font-size: 14px;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation Bar (Same as Previous Pages) -->
    <div class="navbar">
    <a href="add_student.php">Add Student</a>
        <a href="enter_marks.php">Add Marks</a>
        <a href="print_reports.php">Print Reports</a>
        <a href="mark_attendance.php">Mark Attendance</a>
        <a href="generate_grade.php">Generate Grade Sheets</a> 
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <div class="container">
        <h2>Student Attendance Report</h2>

        <!-- Attendance Details for Each Student -->
        <h3>Individual Student Attendance</h3>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendanceData as $studentId => $attendances): ?>
                    <?php 
                        $isFirstAttendance = true;
                        foreach ($attendances as $attendance): 
                    ?>
                        <tr>
                            <td>
                                <?php if ($isFirstAttendance): ?>
                                    <?php echo $attendance['name']; ?>
                                    <?php $isFirstAttendance = false; ?>
                                <?php else: ?>
                                    &nbsp;
                                <?php endif; ?>
                            </td>
                            <td><?php echo $attendance['date']; ?></td>
                            <td><?php echo ($attendance['status'] === 'Present') ? "<span style='color: #00ff00; font-weight: bold;'>Present</span>" : "<span style='color: #ff5555; font-weight: bold;'>Absent</span>"; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Attendance Summary -->
        <h3>Attendance Summary</h3>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Total Classes</th>
                    <th>Attended Classes</th>
                    <th>Attendance Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendanceSummaryData as $studentId => $summary): ?>
                    <tr>
                        <td><?php echo isset($attendanceData[$studentId][0]['name']) ? $attendanceData[$studentId][0]['name'] : 'N/A'; ?></td>
                        <td><?php echo $summary['total_classes']; ?></td>
                        <td><?php echo $summary['attended_classes']; ?></td>
                        <td style="color: <?php echo ($summary['attendance_percentage'] >= 75) ? '#00ff00' : '#ff5555'; ?>;">
                            <?php echo number_format($summary['attendance_percentage'], 2) . '%'; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</body>
</html>
