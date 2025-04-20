<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
}
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $subjects = ['Telugu', 'Hindi', 'English', 'Maths', 'Physics', 'Biology', 'Social'];
    
    $errors = [];
    foreach ($subjects as $subject) {
        $marks = $_POST[strtolower($subject)];
        if (!is_numeric($marks) || $marks < 0 || $marks > 100) {
            $errors[] = "Invalid marks for $subject. Enter a number between 0 and 100.";
        }
    }

    if (empty($errors)) {
        foreach ($subjects as $subject) {
            $marks = $_POST[strtolower($subject)];
            $sql = "INSERT INTO marks (student_id, subject, marks) VALUES ('$student_id', '$subject', '$marks')";
            $conn->query($sql);
        }
        echo "<p style='color: green;'>Marks added successfully!</p>";
    } else {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
}

$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Marks</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Dark Neon Theme */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #0d1b2a, #1b263b);
            color: white;
            margin: 0;
            padding: 0;
        }

        /* Navigation Bar */
        .navbar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            background: linear-gradient(90deg, #1b263b, #0d1b2a);
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0, 255, 255, 0.3);
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            transition: 0.3s;
            border-radius: 5px;
        }
        .navbar a:hover {
            background: #00f5a0;
            color: black;
        }
        .logout {
            background-color: red;
            padding: 10px 15px;
            border-radius: 5px;
        }

        /* Form Container */
        .container {
            width: 90%;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0px 0px 15px rgba(0, 255, 255, 0.3);
            text-align: center;
        }

        h2 {
            text-shadow: 0px 0px 5px cyan;
        }

        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
        }

        /* Neon Input & Select Box */
        input, select {
            background: transparent;
            border: 2px solid cyan;
            padding: 10px;
            margin-bottom: 10px;
            color: white;
            border-radius: 5px;
            outline: none;
            transition: 0.3s;
            font-size: 16px;
        }

        /* Glow effect on focus */
        input:focus, select:focus {
            border-color: #00f5a0;
            box-shadow: 0px 0px 10px cyan;
        }

        /* Customizing the Dropdown */
        select {
    appearance: none; /* Removes default styles */
    cursor: pointer;
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="cyan"><path d="M7 10l5 5 5-5H7z"/></svg>') no-repeat right 10px center;
    background-size: 15px;
    padding-right: 40px;
    color: green; /* 👈 Text color changed to black */
    font-weight: bold;
}

        /* Dropdown Arrow Styling */
        select::-ms-expand {
            display: none;
        }

        /* Button Styling */
        button {
            background: linear-gradient(90deg, cyan, #00f5a0);
            border: none;
            padding: 12px;
            color: black;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        button:hover {
            background: linear-gradient(90deg, #00f5a0, cyan);
            box-shadow: 0px 0px 15px cyan;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                width: 95%;
            }
            .navbar {
                flex-direction: column;
                align-items: center;
            }
            .navbar a {
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="add_student.php">Add Student</a>
        <a href="enter_marks.php">Add Marks</a>
        <a href="print_reports.php">Print Reports</a>
        <a href="mark_attendance.php">Mark Attendance</a>
        <a href="generate_grade.php">Generate Grade Sheets</a> 
        <a href="../logout.php" class="logout">Logout</a>
    </div>

    <div class="container">
        <h2>Enter Marks</h2>
        <form method="POST">
            <label for="student_id">Student:</label>
            <select id="student_id" name="student_id" required>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php endwhile; ?>
            </select>

            <?php
            $subjects = ['Telugu', 'Hindi', 'English', 'Maths', 'Physics', 'Biology', 'Social'];
            foreach ($subjects as $subject):
            ?>
                <label for="<?php echo strtolower($subject); ?>"><?php echo $subject; ?>:</label>
                <input type="number" id="<?php echo strtolower($subject); ?>" name="<?php echo strtolower($subject); ?>" min="0" max="100" required>
            <?php endforeach; ?>

            <button type="submit">Submit Marks</button>
        </form>
    </div>
</body>
</html>
