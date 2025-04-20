<?php
include '../includes/db.php'; // Include database connection

// Fetch Marks Data (Student Marks)
$marksQuery = "SELECT students.id AS student_id, students.name, marks.subject, marks.marks
               FROM marks
               JOIN students ON marks.student_id = students.id
               ORDER BY students.id, marks.subject";
$marksResult = $conn->query($marksQuery);

// Store Marks Data
$marksData = [];
while ($row = $marksResult->fetch_assoc()) {
    $marksData[$row['student_id']][] = $row;
}

// Fetch Attendance Data for each student
$attendanceQuery = "SELECT student_id, attended_classes, total_classes, attendance_percentage
                    FROM attendance_summary";
$attendanceResult = $conn->query($attendanceQuery);

// Store Attendance Data
$attendanceData = [];
while ($row = $attendanceResult->fetch_assoc()) {
    $attendanceData[$row['student_id']] = $row;
}

// Calculate Total Marks and Rank for each student
$totalMarksData = [];
foreach ($marksData as $studentId => $marks) {
    $totalMarks = 0;
    foreach ($marks as $mark) {
        $totalMarks += $mark['marks'];
    }
    $totalMarksData[$studentId] = $totalMarks;
}

// Rank students based on their total marks (descending order)
arsort($totalMarksData);
$rankData = array_flip(array_keys($totalMarksData)); // Create rank based on total marks
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Report Cards</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Dark Theme */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #121212, #1a1a2e);
            color: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* Button to Go to Home */
        .go-home-button {
            background-color: #00eaff;
            padding: 12px 20px;
            color: black;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            margin: 20px;
            cursor: pointer;
            text-align: center;
            font-weight: bold;
            box-shadow: 0px 0px 10px rgba(0, 255, 255, 0.5);
        }

        .go-home-button:hover {
            background-color: #0066ff;
        }

        /* Container */
        .container {
            width: 90%;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.8);
            box-shadow: 0px 0px 20px rgba(0, 255, 255, 0.2);
            border-radius: 10px;
            text-align: center;
        }

        h2 {
            color: #00eaff;
        }

        /* Report Card Design */
        .report-card {
            width: 80%;
            margin: 20px auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 255, 255, 0.5);
            page-break-before: always;
        }

        .report-card:first-child {
            page-break-before: auto;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #00eaff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h3 {
            font-size: 24px;
            color: #00eaff;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 16px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: rgba(0, 0, 0, 0.5);
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #00eaff;
            color: white;
        }

        th {
            background-color: #00eaff;
            color: black;
        }

        /* Attendance */
        .attendance, .exam-percentage {
            margin-top: 15px;
            font-size: 16px;
            color: #00ff00;
        }

        /* Rank */
        .rank {
            font-size: 16px;
            color: #ff9900;
            font-weight: bold;
        }

        /* Parent's Signature */
        .signature {
            margin-top: 30px;
            text-align: center;
            font-size: 18px;
        }

        .signature p {
            border-top: 1px solid white;
            width: 60%;
            margin: auto;
            padding-top: 5px;
        }

        /* Print Button */
        .print-button {
            text-align: center;
            margin-top: 20px;
        }

        button {
            background-color: #00eaff;
            color: black;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0px 0px 10px rgba(0, 255, 255, 0.5);
        }

        button:hover {
            background-color: #0066ff;
        }

        /* Print Styling */
        @media print {
            body {
                background: white;
                color: black;
            }

            .container, .report-card {
                background: white;
                color: black;
                box-shadow: none;
            }

            th, td {
                border: 1px solid black;
                color: black;
            }

            .header h3 {
                color: black;
            }

            .attendance {
                color: black;
            }

            .exam-percentage {
                color: black;
            }

            button {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Button to Navigate to Home -->
    <div style="text-align: center;">
        <button class="go-home-button" onclick="window.location.href='home.php'">Go to Home</button>
    </div>

    <div class="container">
        <h2>Grade Report Cards</h2>
        <div class="print-button">
            <button onclick="window.print()">Print All Report Cards</button>
        </div>

        <?php foreach ($marksData as $studentId => $marks): ?>
            <div class="report-card">
                <!-- Header with Student's Details -->
                <div class="header">
                    <h3>Report Card</h3>
                    <p><strong>Name:</strong> <?php echo $marks[0]['name']; ?></p>
                    <p><strong>Class:</strong> <?php echo isset($attendanceData[$studentId]['class_id']) ? $attendanceData[$studentId]['class_id'] : ''; ?></p>
                </div>

                <!-- Rank -->
                <div class="rank">
                    <p><strong>Rank: </strong> <?php echo $rankData[$studentId] + 1; ?></p>
                </div>

                <!-- Marks Table -->
                <table>
                    <tr>
                        <th>Subject</th>
                        <th>Marks</th>
                    </tr>
                    <?php
                        // Sort the marks based on predefined subject order
                        $subjectsOrder = ['Telugu', 'Hindi', 'English', 'Maths', 'Physics', 'Biology', 'Social'];

                        usort($marks, function($a, $b) use ($subjectsOrder) {
                            return array_search($a['subject'], $subjectsOrder) - array_search($b['subject'], $subjectsOrder);
                        });

                        // Initialize the total marks
                        $totalMarks = 0;
                        foreach ($marks as $mark) {
                            $totalMarks += $mark['marks'];
                    ?>
                        <tr>
                            <td><?php echo $mark['subject']; ?></td>
                            <td><?php echo $mark['marks']; ?>/25</td>
                        </tr>
                    <?php } ?>
                    
                    <!-- Display the Total Marks -->
                    <tr>
                        <td><strong>Total Marks</strong></td>
                        <td><strong><?php echo $totalMarks; ?></strong>/175</td>
                    </tr>
                </table>

                <!-- Exam Percentage -->
                <div class="exam-percentage">
                    <p><strong>Exam Percentage: </strong><?php echo number_format(($totalMarks / 175) * 100, 2); ?>%</p>
                </div>

                <!-- Attendance Percentage -->
                <div class="attendance">
                    <p><strong>Attendance Percentage: </strong><?php echo isset($attendanceData[$studentId]) ? number_format($attendanceData[$studentId]['attendance_percentage'], 2) . '%' : 'N/A'; ?></p>
                </div>

                <!-- Parent's Signature -->
                <div class="signature">
                    <p><strong>Parent's Signature:</strong></p>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

</body>
</html>
