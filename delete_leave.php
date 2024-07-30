<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $leave_id = $_POST['id'];

    // Récupérez les détails du congé pour notification
    $leave = getLeaveById($leave_id);
    if ($leave) {
        $employee_id = $leave['employee_id'];
        $employee_email = getEmployeeEmailById($employee_id);

        // Supprimez le congé
        $sql = "DELETE FROM leaves WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            echo "Erreur lors de la préparation de la requête : " . $conn->error;
            exit();
        }
        $stmt->bind_param('i', $leave_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Envoyer l'email à l'employé
            $subject = "Votre congé a été supprimé";
            $message = "Votre demande de congé du " . $leave['start_date'] . " au " . $leave['end_date'] . " a été supprimée.";
            mail($employee_email, $subject, $message, "From: no-reply@votreentreprise.com");
            
            echo "Congé supprimé avec succès.";
        } else {
            echo "Erreur lors de la suppression du congé.";
        }
    } else {
        echo "Congé non trouvé.";
    }
} else {
    echo "Paramètre manquant.";
}

function getLeaveById($id) {
    global $conn;
    $sql = "SELECT * FROM leaves WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getEmployeeEmailById($id) {
    global $conn;
    $sql = "SELECT email FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();
    return $employee['email'];
}
?>
