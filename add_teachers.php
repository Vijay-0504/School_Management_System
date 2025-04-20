<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
}
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $class_id = $_POST['class_id'];

    $sql = "INSERT INTO teachers (name, class_id) VALUES ('$name', '$class_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Teacher added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM classes";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Teacher</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Add Teacher</h2>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="class_id">Class:</label>
            <select id="class_id" name="class_id" required>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['class_name']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Add Teacher</button>
        </form>
    </div>
</body>
</html>