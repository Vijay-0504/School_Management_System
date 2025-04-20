<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}
include '../includes/db.php';

$class_id = isset($_POST['class_id']) ? $_POST['class_id'] : '';

// Fetch all classes for dropdown
$sql_classes = "SELECT DISTINCT class_id FROM students";
$result_classes = $conn->query($sql_classes);

// Fetch students from selected class
$students = [];
if ($class_id) {
    $sql_students = "SELECT id, name FROM students WHERE class_id = '$class_id'";
    $result_students = $conn->query($sql_students);
    while ($row = $result_students->fetch_assoc()) {
        $students[] = $row;
    }
}

// Handle attendance submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_attendance'])) {
    $date = date('Y-m-d');

    foreach ($students as $student) {
        $student_id = $student['id'];
        $status = isset($_POST['attendance'][$student_id]) ? 'Present' : 'Absent';

        // Insert attendance record
        $insert_sql = "INSERT INTO attendance (student_id, class_id, date, status) 
                       VALUES ('$student_id', '$class_id', '$date', '$status')";

        if ($conn->query($insert_sql) === TRUE) {
            echo "<p style='color: green;'>✅ Attendance recorded for Student ID: $student_id ($status)</p>";
        } else {
            echo "<p style='color: red;'>❌ Error: " . $conn->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #121212, #1a1a2e);
            color: #ffffff;
            margin: 0;
            padding: 0;
        }
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
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.7);
            box-shadow: 0px 0px 20px rgba(0, 255, 255, 0.2);
            border-radius: 10px;
            text-align: center;
        }
        h2 {
            color: #00eaff;
        }
        .form-group {
            margin-bottom: 20px;
        }
        select, button {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
        }
        .attendance-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            padding: 20px;
        }
        .attendance-box {
            border: 2px solid #00eaff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 255, 255, 0.5);
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
        }
        .attendance-box label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .attendance-box input[type="checkbox"] {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #00eaff;
            border-radius: 4px;
            background-color: transparent;
            cursor: pointer;
            position: relative;
        }
        .attendance-box input[type="checkbox"]:checked {
            background-color: #00eaff;
        }
        .attendance-box input[type="checkbox"]::after {
            content: "✔";
            font-size: 14px;
            color: black;
            position: absolute;
            top: 2px;
            left: 3px;
            visibility: hidden;
        }
        .attendance-box input[type="checkbox"]:checked::after {
            visibility: visible;
        }
        button {
            background-color: #00eaff;
            color: black;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
            box-shadow: 0px 0px 10px rgba(0, 255, 255, 0.5);
        }
        button:hover {
            background-color: #0066ff;
        }
        @media (max-width: 600px) {
            .attendance-container {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
            .container {
                width: 95%;
            }
        }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="add_student.php">Add Student</a>
        <a href="enter_marks.php">Add Marks</a>
        <a href="print_reports.php">Print Reports</a>
        <a href="mark_attendance.php">Mark Attendance</a>
        <a href="generate_grade.php">Generate Grade Sheets</a> 
        <a href="../logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Mark Attendance</h2>

        <form method="POST">
            <div class="form-group">
                <label for="class_id">Select Class:</label>
                <select name="class_id" required>
                    <option value="">-- Choose Class --</option>
                    <?php while ($row = $result_classes->fetch_assoc()): ?>
                        <option value="<?php echo $row['class_id']; ?>" <?php echo ($class_id == $row['class_id']) ? 'selected' : ''; ?>>
                            Class <?php echo $row['class_id']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" name="get_list">Get List</button>
            </div>
        </form>

        <?php if (!empty($students)): ?>
            <form method="POST">
                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                <div class="attendance-container">
                    <?php foreach ($students as $student): ?>
                        <div class="attendance-box">
                            <label><?php echo $student['name']; ?></label>
                            <input type="checkbox" name="attendance[<?php echo $student['id']; ?>]" value="Present"> Present
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" name="submit_attendance">Submit Attendance</button>
            </form>
        <?php endif; ?>
    </div>

</body>
</html>
