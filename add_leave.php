<?php
include 'config.php';
include 'functions2.php';

// Récupérer les noms des employés pour le champ select
$sql = "SELECT id, name FROM employees";
$result = $conn->query($sql);
$employees = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    if (addLeave($employee_id, $start_date, $end_date, $reason)) {
        header("Location: leave.php");
        exit;
    } else {
        $error_message = "Erreur lors de l'ajout du congé.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un congé</title>
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
        .btn-secondary {
            background: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background: #5a6268;
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
        <h1>Ajouter un congé</h1>
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form id="leaveForm" method="post">
            <div class="form-group">
                <label for="employee_id">Nom de l'employé:</label>
                <select id="employee_id" name="employee_id" class="form-control" required>
                    <option value="" disabled selected>Sélectionner un employé</option>
                    <?php foreach ($employees as $employee) : ?>
                        <option value="<?php echo $employee['id']; ?>"><?php echo $employee['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
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
            <button type="submit" class="btn btn-primary btn-block">Ajouter</button>
        </form>
        <a href="leave.php" class="btn btn-secondary btn-block mt-3">Retour à la liste des congés</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#leaveForm').submit(function(event) {
            event.preventDefault(); // Empêche l'envoi du formulaire

            var employeeId = $('#employee_id').val();

            // Vérifier si l'ID d'employé existe en faisant une requête AJAX
            $.ajax({
                url: 'check_employee.php',
                type: 'POST',
                data: { id: employeeId },
                success: function(response) {
                    if (response === 'exists') {
                        // Si l'employé existe, soumettre le formulaire pour ajouter le congé
                        $('#leaveForm')[0].submit();
                    } else {
                        // Si l'employé n'existe pas, afficher un message d'erreur
                        alert('Employé inexistant. Veuillez vérifier l\'employé sélectionné.');
                    }
                },
                error: function() {
                    alert('Erreur lors de la vérification de l\'employé.');
                }
            });
        });
    });
</script>


</body>
</html>
