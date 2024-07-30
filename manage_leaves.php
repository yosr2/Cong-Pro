<?php
session_start();
include 'config.php';
include_once 'functions2.php';

// Vérifiez si l'utilisateur est connecté et a le rôle d'administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo "Accès refusé.";
    exit();
}

// Traitement des actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $leave_id = intval($_GET['id']);
    
    try {
        if ($action === 'accept') {
            if (acceptLeave($leave_id)) {
                echo "Congé accepté avec succès.";
            } else {
                echo "Erreur lors de l'acceptation du congé.";
            }
        } elseif ($action === 'reject') {
            if (rejectLeave($leave_id)) {
                echo "Congé refusé avec succès.";
            } else {
                echo "Erreur lors du refus du congé.";
            }
        }
        
        // Redirection après traitement
        header("Location: manage_leaves.php");
        exit();
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Récupérez les congés
$leaves = getAllLeaves();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des congés</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow:hidden;
        }
        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1200px;
            max-height:90%;
            overflow-y:auto;
        }
        h1 {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .btn-primary {
            background: #007bff;
            border: none;
            font-size: 1rem;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-success {
            background: #28a745;
            border: none;
        }
        .btn-success:hover {
            background: #218838;
        }
        .btn-danger {
            background: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        table {
            width: 100%;
            margin-top: 1rem;
        }
        table th, table td {
            text-align: center;
            padding: 1rem;
        }
        .table thead th {
            background: #007bff;
            color: #fff;
        }
        .table tbody tr:hover {
            background: #f1f1f1;
        }
        .btn {
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des congés</h1>
        <div class="text-right mb-3">
            <a href="add_leave.php" class="btn btn-primary">
                <i class="fas fa-calendar-plus"></i> Ajouter un congé
            </a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom de l'employé</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Raison</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($leaves)) : ?>
                    <?php foreach ($leaves as $leave) : ?>
                        <tr id="leave-<?php echo $leave['id']; ?>">
                            <td><?php echo htmlspecialchars($leave['employee_name']); ?></td>
                            <td><?php echo htmlspecialchars($leave['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($leave['end_date']); ?></td>
                            <td><?php echo htmlspecialchars($leave['reason']); ?></td>
                            <td>
                                <?php 
                                // Affiche "accepted" si le statut est vide, sinon affiche le statut
                                echo htmlspecialchars(empty($leave['status']) ? 'accepted' : $leave['status']);
                                ?>
                            </td>
                            <td>
                                <?php if ($leave['status'] === 'pending') : ?>
                                    <a href="manage_leaves.php?action=accept&id=<?php echo htmlspecialchars($leave['id']); ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> Accepter
                                    </a>
                                    <a href="manage_leaves.php?action=reject&id=<?php echo htmlspecialchars($leave['id']); ?>" class="btn btn-danger btn-sm">
                                        <i class="fas fa-times"></i> Refuser
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Aucun congé trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
