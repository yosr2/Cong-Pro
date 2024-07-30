<?php
session_start();
include 'config.php';
include 'functions2.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "Accès refusé.";
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_SESSION['user_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    if (addLeaveRequest($employee_id, $start_date, $end_date, $reason)) {
        echo "Demande de congé soumise avec succès.";
    } else {
        $error_message = "Erreur lors de la soumission de la demande de congé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Congé</title>
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
        }
        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
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
        .form-group label {
            font-weight: 600;
        }
        .form-control, .btn {
            border-radius: 5px;
        }
        textarea.form-control {
            resize: vertical;
        }
        .alert {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Demande de Congé</h1>
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="start_date">Date de début:</label>
                <input type="date" id="start_date" name="start_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="end_date">Date de fin:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="reason">Raison:</label>
                <textarea id="reason" name="reason" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Soumettre la demande</button>
        </form>
    </div>
</body>
</html>
