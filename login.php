<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username === "Vitoria" && $password === "12345") {

        $_SESSION["username"] = $username;
        $_SESSION["logged_in"] = true;

        header("Location: private/admin.php");
        exit();

    } else {

        header("Location: login.php?erro=1");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login - EcoFeira</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-success bg-gradient">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="index.php">EcoFeira</a>
            <a class="navbar-brand text-white fw-bold" href="login.php" id="user-area">Área do Usuário</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container mt-5">

        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="card shadow-lg p-4 border-0 rounded-4">
                    <h3 class="text-center fw-bold text-success mb-4">Login</h3>

                    <?php if (isset($_GET['erro']) && $_GET['erro'] === '1'): ?>
                        <div class="alert alert-danger text-center">
                            Usuário ou senha incorretos.
                        </div>
                    <?php endif; ?>

                    <form action="login.php" method="POST">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Usuário</label>
                            <input type="text" name="username" class="form-control" placeholder="Digite seu usuário" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Senha</label>
                            <input type="password" name="password" class="form-control" placeholder="Digite sua senha" required>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-success btn-lg px-5" type="submit">Entrar</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>

</body>
</html>


