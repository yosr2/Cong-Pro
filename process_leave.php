<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeName = $_POST['name'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $reason = $_POST['reason'];

    // Vérifie à nouveau si l'employé existe (par sécurité)
    $sql = "SELECT COUNT(*) AS count FROM employees WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $employeeName);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        // Ajouter le congé dans la table leaves
        $sqlInsert = "INSERT INTO leaves (name, start_date, end_date, reason) VALUES (?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param('ssss', $employeeName, $startDate, $endDate, $reason);
        
        if ($stmtInsert->execute()) {
            echo "Congé ajouté avec succès.";
        } else {
            echo "Erreur lors de l'ajout du congé : " . $conn->error;
        }
    } else {
        echo "Employé inexistant. Impossible d'ajouter le congé.";
    }
}
?>
