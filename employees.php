<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SESSION['role'] != 'admin') {
    echo "Accès refusé.";
    exit();
}

include 'functions.php';
$employees = getAllEmployees();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des employés</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
        }
        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
        }
        h1 {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 2rem;
        }
        .btn-success, .btn-warning, .btn-danger {
            border: none;
            margin: 0 5px;
        }
        .btn-success:hover {
            background: #218838;
        }
        .btn-warning:hover {
            background: #d39e00;
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
            vertical-align: middle;
        }
        .table thead th {
            background: #007bff;
            color: #fff;
        }
        .table tbody tr:hover {
            background: #f1f1f1;
        }
        .action-btns {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Gestion des employés</h1>
        <div class="text-right mb-3">
            <a href="add_employee.php" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Ajouter un employé
            </a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Position</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee) : ?>
                    <tr>
                        <td><?php echo $employee['name']; ?></td>
                        <td><?php echo $employee['position']; ?></td>
                        <td><?php echo $employee['email']; ?></td>
                        <td class="action-btns">
                            <a href="edit_employee.php?id=<?php echo $employee['id']; ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="delete_employee.php?id=<?php echo $employee['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
