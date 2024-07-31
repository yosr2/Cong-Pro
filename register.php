<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $position = isset($_POST['position']) ? $_POST['position'] : null;

    // Début d'une transaction
    $conn->begin_transaction();

    try {
        // Insertion dans la table users
        $sql1 = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt1 = $conn->prepare($sql1);
        if ($stmt1 === false) {
            throw new Exception("Erreur lors de la préparation de la requête pour users : " . $conn->error);
        }
        $stmt1->bind_param('sss', $username, $password, $role);
        $stmt1->execute();

        // Vérifiez si l'insertion a réussi
        if ($stmt1->affected_rows === 0) {
            throw new Exception("Erreur lors de l'insertion dans la table users.");
        }

        $user_id = $stmt1->insert_id; // Récupère l'ID généré pour l'utilisateur
        if (!$user_id) {
            throw new Exception("Erreur lors de la récupération de l'ID de l'utilisateur : " . $conn->error);
        }

        // Insertion dans la table employees uniquement si le rôle est employee
        if ($role === 'employee') {
            if ($email === null || $position === null) {
                throw new Exception("Les informations nécessaires pour les employés ne sont pas complètes.");
            }

            $sql2 = "INSERT INTO employees (id, name, email, position) VALUES (?, ?, ?, ?)";
            $stmt2 = $conn->prepare($sql2);
            if ($stmt2 === false) {
                throw new Exception("Erreur lors de la préparation de la requête pour employees : " . $conn->error);
            }
            $stmt2->bind_param('isss', $user_id, $username, $email, $position);
            $stmt2->execute();

            // Vérifiez si l'insertion a réussi
            if ($stmt2->affected_rows === 0) {
                throw new Exception("Erreur lors de l'insertion dans la table employees.");
            }
        }

        // Commit de la transaction
        $conn->commit();
        header("Location: login.php");
        exit;

    } catch (Exception $e) {
        // Rollback en cas d'erreur
        $conn->rollback();
        echo "Erreur lors de l'inscription : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Inscription</title>
    <link rel="icon" type="png" href="/assets/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
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
        .logo {
        width: 150px; 
        position: relative;
        left: 69% ;
    }
        h1 {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-control, .btn {
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .login-link {
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
    <img src="assets/logo.png" alt="Logo" class="logo">
        <h1>Inscription</h1>
        <form id="registrationForm" method="post" action="register.php">
            <div class="form-group">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div id="emailGroup" class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="role">Rôle:</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="" disabled selected>Sélectionner un rôle</option>
                    <option value="admin">Administrateur</option>
                    <option value="employee">Employé</option>
                </select>
            </div>
            <div id="positionGroup" class="form-group" style="display: none;">
                <label for="position">Poste:</label>
                <input type="text" id="position" name="position" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
        </form>
        <div class="login-link">
            <p>Déjà un compte? <a href="login.php">Connectez-vous ici</a></p>
        </div>
    </div>
    <script>
        document.getElementById('role').addEventListener('change', function() {
            var role = this.value;
            var positionGroup = document.getElementById('positionGroup');
            var emailGroup = document.getElementById('emailGroup');
            
            if (role === 'employee') {
                positionGroup.style.display = 'block';
                emailGroup.style.display = 'block'; // Affiche le champ email pour les employés
            } else {
                positionGroup.style.display = 'none';
                emailGroup.style.display = 'none'; // Masque le champ email pour les autres rôles
            }
        });
    </script>
</body>
</html>

