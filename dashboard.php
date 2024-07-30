<?php
session_start();
include 'config.php';
include_once 'functions2.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// Récupérez les notifications si l'utilisateur est un employé
$notifications = [];
if ($user_role == 'employee') {
    $notifications = getEmployeeLeaveStatus($user_id);
}

// Préparez le contenu spécifique au rôle
if ($user_role == 'admin') {
    $role_specific_content = "<a href='employees.php' class='btn btn-primary'><i class='fas fa-users'></i> Gestion des employés</a>";
    $leave_management = "<a href='manage_leaves.php' class='btn btn-secondary'><i class='fas fa-calendar-alt'></i> Gestion des congés</a>";
} else {
    $role_specific_content = "<a href='request_leave.php' class='btn btn-primary'><i class='fas fa-calendar-alt'></i> Demander un congé</a>";
    $leave_management = "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
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
        .dashboard-container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 2rem;
        }
        .btn {
            margin: 1rem 0.5rem;
            font-weight: 600;
            transition: background 0.3s ease-in-out;
        }
        .btn-primary {
            background: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-secondary {
            background: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .logout-btn {
            background: #dc3545;
            border: none;
        }
        .logout-btn:hover {
            background: #c82333;
        }
        .notification {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 5px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <div>
            <?php echo $role_specific_content; ?>
            <?php echo $leave_management; ?>
        </div>

        <?php if ($user_role == 'employee' && !empty($notifications)) : ?>
            <div class="notification">
                <?php foreach ($notifications as $leave) : ?>
                    <?php if ($leave['status'] == '') : ?>
                        <p><i class="fas fa-check"></i> Votre congé du <?php echo htmlspecialchars($leave['start_date']); ?> au <?php echo htmlspecialchars($leave['end_date']); ?> a été accepté.</p>
                    <?php elseif ($leave['status'] == 'rejected') : ?>
                        <p><i class="fas fa-times"></i> Votre congé du <?php echo htmlspecialchars($leave['start_date']); ?> au <?php echo htmlspecialchars($leave['end_date']); ?> a été refusé.</p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <a href="logout.php" class="btn logout-btn"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a>
    </div>
</body>
</html>
