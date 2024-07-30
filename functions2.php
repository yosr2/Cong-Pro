<?php
include 'config.php';


// functions2.php
// functions2.php

function getAllLeaves() {
    global $conn;
    $sql = "SELECT leaves.id, leaves.start_date, leaves.end_date, leaves.reason, leaves.status, users.username AS employee_name
            FROM leaves
            JOIN users ON leaves.employee_id = users.id";
    $result = $conn->query($sql);
    if ($result === false) {
        echo "Erreur lors de la récupération des congés : " . $conn->error;
        exit();
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}





function addLeaveRequest($employee_id, $start_date, $end_date, $reason) {
    global $conn;

    // Préparer la requête SQL
    $stmt = $conn->prepare("INSERT INTO leaves (employee_id, start_date, end_date, reason, status) VALUES (?, ?, ?, ?, 'pending')");

    // Vérifier si la préparation a échoué
    if ($stmt === false) {
        die("Erreur lors de la préparation de la requête : " . $conn->error);
    }

    // Lier les paramètres
    if (!$stmt->bind_param("isss", $employee_id, $start_date, $end_date, $reason)) {
        die("Erreur lors du liage des paramètres : " . $stmt->error);
    }

    // Exécuter la requête
    if (!$stmt->execute()) {
        die("Erreur lors de l'exécution de la requête : " . $stmt->error);
    }

    return true;
}



function addLeave($name, $start_date, $end_date, $reason) {
    global $conn;
    $sql = "INSERT INTO leaves (name, start_date, end_date, reason) VALUES ('$name', '$start_date', '$end_date', '$reason')";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        die("Erreur lors de l'ajout du congé : " . $conn->error);
    }
}

function updateLeave($id, $start_date, $end_date, $reason) {
    global $conn;
    $sql = "UPDATE leaves SET start_date='$start_date', end_date='$end_date', reason='$reason' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function deleteLeave($id) {
    global $conn;
    $sql = "DELETE FROM leaves WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function acceptLeave($leave_id) {
    global $conn;
    
    // Préparer la requête pour mettre à jour le statut du congé
    $sql = "UPDATE leaves SET status = 'accepted' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        throw new Exception("Erreur lors de la préparation de la requête : " . $conn->error);
    }
    
    // Lier le paramètre et exécuter la requête
    $stmt->bind_param('i', $leave_id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function rejectLeave($leave_id) {
    global $conn;
    
    // Préparer la requête pour mettre à jour le statut du congé
    $sql = "UPDATE leaves SET status = 'rejected' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        throw new Exception("Erreur lors de la préparation de la requête : " . $conn->error);
    }
    
    // Lier le paramètre et exécuter la requête
    $stmt->bind_param('i', $leave_id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        return true;
    } else {
        return false;
    }
}
// Récupère les congés de l'employé spécifique
// Récupère les congés de l'employé spécifique avec les dates et le statut
function getEmployeeLeaveStatus($employee_id) {
    global $conn;

    $sql = "SELECT status, start_date, end_date FROM leaves WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        throw new Exception("Erreur lors de la préparation de la requête : " . $conn->error);
    }

    $stmt->bind_param('i', $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }

    return $notifications;
}


?>
