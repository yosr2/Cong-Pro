<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

include 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Assurez-vous que l'ID est un entier
    $sql = "DELETE FROM employees WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header('Location: employees.php');
        exit();
    } else {
        echo "Erreur lors de la suppression: " . $conn->error;
    }
} else {
    echo "ID d'employé manquant.";
}
?>
