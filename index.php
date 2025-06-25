<?php
// Include database connection
require_once 'config/db.php';

// Initialize variables for feedback
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if ($name && $age && $email) {
        try {
            $stmt = $pdo->prepare("INSERT INTO students (name, age, email) VALUES (:name, :age, :email)");
            $stmt->execute(['name' => $name, 'age' => $age, 'email' => $email]);
            $success = "Student added successfully!";
        } catch (PDOException $e) {
            $error = "Error adding student: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all fields correctly.";
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    if ($id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $success = "Student deleted successfully!";
        } catch (PDOException $e) {
            $error = "Error deleting student: " . $e->getMessage();
        }
    } else {
        $error = "Invalid student ID.";
    }
}

// Fetch all students
try {
    $stmt = $pdo->query("SELECT * FROM students");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching students: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Record System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Student Record System</h1>
    </header>
    <main>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required min="1">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Add Student</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['id']); ?></td>
                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                        <td><?php echo htmlspecialchars($student['age']); ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td>
                            <a href="?delete=<?php echo $student['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>