<?php
include 'config.php';

function getAllEmployees() {
    global $conn;
    $sql = "SELECT * FROM employees";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function addEmployee($name, $position, $email) {
    global $conn;
    $sql = "INSERT INTO employees (name, position, email) VALUES ('$name', '$position', '$email')";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function updateEmployee($id, $name, $position, $email) {
    global $conn;
    $sql = "UPDATE employees SET name='$name', position='$position', email='$email' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function deleteEmployee($id) {
    global $conn;
    $sql = "DELETE FROM employees WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}
?>
