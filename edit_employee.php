<?php
session_start();
include 'config.php';
include 'functions.php';

// Vérifiez si l'ID de l'employé est fourni dans l'URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Assurez-vous que l'ID est un entier

    // Récupérez les informations de l'employé à partir de la base de données
    $sql = "SELECT * FROM employees WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
    } else {
        echo "Employé non trouvé.";
        exit;
    }
} else {
    echo "ID de l'employé non fourni.";
    exit;
}

// Si le formulaire est soumis, mettez à jour les informations de l'employé
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $email = $_POST['email'];

    if (updateEmployee($id, $name, $position, $email)) {
        header("Location: employees.php");
        exit;
    } else {
        echo "Erreur lors de la mise à jour de l'employé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un employé</title>
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
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
        }
        form {
            margin-bottom: 2rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        input {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        button {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Modifier un employé</h1>
        <form method="post">
            <div class="form-group">
                <label for="name">Nom:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="position">Position:</label>
                <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($employee['position']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
            </div>
            <button type="submit">Mettre à jour</button>
        </form>
        <a href="employees.php">Retour à la liste des employés</a>
    </div>
</body>
</html>
