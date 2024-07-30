<?php
// check_employee.php
include 'config.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("SELECT id FROM employees WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo 'exists'; // L'employé existe
    } else {
        echo 'not_exists'; // L'employé n'existe pas
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'error'; // Erreur si l'ID n'est pas fourni
}

?>
