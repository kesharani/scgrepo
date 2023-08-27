<?php
// Initialize variables to store form data and error messages
$name = $email = $message = "";
$nameErr = $emailErr = $messageErr = "";

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize Name
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = sanitizeInput($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameErr = "Only letters and spaces allowed";
        }
    }

    // Validate and sanitize Email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = sanitizeInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Validate and sanitize Message
    if (empty($_POST["message"])) {
        $messageErr = "Message is required";
    } else {
        $message = sanitizeInput($_POST["message"]);
        // You can add additional checks here to prevent malicious code or HTML tags
    }
}

?>



<?php
// Function to establish a database connection
function establishConnection() {
    $servername = "localhost";
    $username = "your_username";
    $password = "your_password";
    $dbname = "project_database";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to insert new project details
function insertProject($title, $description, $image) {
    $conn = establishConnection();

    $title = $conn->real_escape_string($title);
    $description = $conn->real_escape_string($description);
    $image = $conn->real_escape_string($image);

    $sql = "INSERT INTO projects (Title, Description, Image) VALUES ('$title', '$description', '$image')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }

    $conn->close();
}

// Function to retrieve project data
function retrieveProjects() {
    $conn = establishConnection();

    $sql = "SELECT * FROM projects";
    $result = $conn->query($sql);

    $projects = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
    }

    $conn->close();

    return $projects;
}

// Function to update project information
function updateProject($id, $title, $description, $image) {
    $conn = establishConnection();

    $title = $conn->real_escape_string($title);
    $description = $conn->real_escape_string($description);
    $image = $conn->real_escape_string($image);

    $sql = "UPDATE projects SET Title='$title', Description='$description', Image='$image' WHERE ID=$id";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }

    $conn->close();
}

// Function to delete a project
function deleteProject($id) {
    $conn = establishConnection();

    $sql = "DELETE FROM projects WHERE ID=$id";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }

    $conn->close();
}
?>
