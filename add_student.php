<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $class_id = $_POST['class_id'];
    $roll_number = $_POST['roll_number'];

    $sql = "INSERT INTO students (name, class_id, roll_number) VALUES ('$name', '$class_id', '$roll_number')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Student added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Dark Theme & Neon Gradient Background */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            color: white;
            margin: 0;
            padding: 0;
        }

        /* Navigation Bar */
        .navbar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            background: linear-gradient(90deg, #0f3460, #16213e);
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
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

        /* Container */
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

        input {
            background: transparent;
            border: 2px solid cyan;
            padding: 10px;
            margin-bottom: 10px;
            color: white;
            border-radius: 5px;
            outline: none;
            transition: 0.3s;
        }
        input:focus {
            border-color: #00f5a0;
            box-shadow: 0px 0px 10px cyan;
        }

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
    <a href="home.php">Home</a>
        <a href="add_student.php">Add Student</a>
        <a href="enter_marks.php">Add Marks</a>
        <a href="print_reports.php">Print Reports</a>
        <a href="mark_attendance.php">Mark Attendance</a>
        <a href="generate_grade.php">Generate Grade Sheets</a> 
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <!-- Student Form -->
    <div class="container">
        <h2>Add Student</h2>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="class_id">Class ID:</label>
            <input type="number" id="class_id" name="class_id" required>

            <label for="roll_number">Roll Number:</label>
            <input type="number" id="roll_number" name="roll_number" required>

            <button type="submit">Add Student</button>
        </form>
    </div>

</body>
</html>
