<?php
session_start();
include 'config.php';

$error_message = ''; // Initialisation de la variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error_message = "Mot de passe incorrect.";
        }
    } else {
        $error_message = "Nom d'utilisateur incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
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
            max-width: 840px !important;
            animation: fadeIn 1s ease-in-out;
            position: relative;
        }
        .logo {
        width: 150px; 
        position: relative;
        left: 69% ;
    }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        h1 {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
        }
        .form-group label {
            font-weight: 600;
            color: #495057;
        }
        .btn-primary {
            background: #007bff;
            border: none;
            font-weight: 600;
            transition: background 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .text-center a {
            color: #007bff;
            font-weight: 600;
        }
        .text-center a:hover {
            text-decoration: none;
            color: #0056b3;
        }
        .alert-popup {
            display: <?php echo $error_message ? 'block' : 'none'; ?>;
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 1rem;
            border-radius: 5px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            animation: popup 0.3s ease-in-out;
            text-align: center;
        }
        @keyframes popup {
            from {
                opacity: 0;
                transform: translate(-50%, -10%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }
        .alert-popup .close-btn {
            cursor: pointer;
            font-size: 1.2rem;
            color: #721c24;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .alert-popup .icon {
            font-size: 2rem;
            margin-right: 0.5rem;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container">
    <img src="assets/logo.png" alt="Logo" class="logo">
        <h1>Connexion</h1>
        <?php if ($error_message): ?>
            <div class="alert-popup">
                <i class="fas fa-exclamation-circle icon"></i>
                <?php echo $error_message; ?>
                <span class="close-btn">&times;</span>
            </div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
        </form>
        <p class="text-center mt-3">
            Pas encore inscrit ? <a href="register.php">Inscription</a>
        </p>
    </div>
    <script>
        document.querySelector('.close-btn').addEventListener('click', function() {
            this.parentElement.style.display = 'none';
        });
    </script>
</body>
</html>
