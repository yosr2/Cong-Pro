<?php
session_start();
include 'config.php';
include 'functions2.php';

// Vérifiez si l'utilisateur est connecté et a le rôle d'administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo "Accès refusé.";
    exit();
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
            overflow: hidden;
        }
        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1200px;
            max-height:80vh;
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
        .btn-danger {
            background: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background: #c82333;
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
                    <td><?php echo htmlspecialchars($leave['status']); ?></td>
                    <td>
                        <a href="edit_leave.php?id=<?php echo $leave['id']; ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="deleteLeave(<?php echo $leave['id']; ?>)">
                            <i class="fas fa-trash-alt"></i> Supprimer
                        </button>
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
    <script>
        function deleteLeave(id) {
            if (confirm("Voulez-vous vraiment supprimer ce congé ?")) {
                $.ajax({
                    url: 'delete_leave.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        alert(response);
                        $('#leave-' + id).remove();
                    },
                    error: function() {
                        alert('Erreur lors de la suppression du congé.');
                    }
                });
            }
        }
    </script>
</body>
</html>
