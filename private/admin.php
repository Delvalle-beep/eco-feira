<?php
include("autoriza.php");
verificaSeEstaAutorizado();

if (!isset($_SESSION["carrinho"])) {
    $_SESSION["carrinho"] = [];
}
// Produtos fictícios das cestas
//Apenas para ilustrar mesmo, não tem gerenciamento de estoque nem nada e nem é preciso
//devido à complexidade do projeto exigido
$cestas = [
    ["id" => 1, "nome" => "Cesta Pequena", "descricao" => "3 hortaliças + 2 frutas", "preco" => 20],
    ["id" => 2, "nome" => "Cesta Média", "descricao" => "5 hortaliças + 3 frutas", "preco" => 35],
    ["id" => 3, "nome" => "Cesta Grande", "descricao" => "7 hortaliças + 5 frutas", "preco" => 50]
];

if (isset($_POST["adicionar"])) {
    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $preco = $_POST["preco"];

    $_SESSION["carrinho"][] = [
        "id" => $id,
        "nome" => $nome,
        "preco" => $preco
    ];
}

if (isset($_POST["remover"])) {
    $index = $_POST["index"];
    unset($_SESSION["carrinho"][$index]);
    $_SESSION["carrinho"] = array_values($_SESSION["carrinho"]); 
}

$mensagem = "";
if (isset($_POST["finalizar"])) {
    $_SESSION["carrinho"] = [];
    $mensagem = "Compra finalizada com sucesso! 🎉";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Área Administrativa</title>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-success bg-gradient">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="index.php">EcoFeira</a>
        <a class="navbar-brand text-white fw-bold" href="admin.php">Admin</a>

        <div class="ms-auto">
            <a class="btn btn-outline-light" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">

    <?php if (!empty($mensagem)): ?>
        <div class="alert alert-success text-center fw-bold">
            <?= $mensagem ?>
        </div>
    <?php endif; ?>

    <h2 class="fw-bold text-success mb-4 text-center">Área do Administrador</h2>

    <div class="row">

        <div class="col-md-7">
            <div class="card p-4 shadow-sm border-0 rounded-4">
                <h4 class="text-success mb-3 fw-bold">Cestas Disponíveis</h4>

                <?php foreach ($cestas as $cesta): ?>
                    <div class="card mb-3 border-0 shadow-sm rounded-4 p-3">
                        <h5><?= $cesta['nome'] ?></h5>
                        <p class="text-muted"><?= $cesta['descricao'] ?></p>
                        <p class="fw-bold text-success">R$ <?= number_format($cesta['preco'], 2, ',', '.') ?></p>

                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $cesta['id'] ?>">
                            <input type="hidden" name="nome" value="<?= $cesta['nome'] ?>">
                            <input type="hidden" name="preco" value="<?= $cesta['preco'] ?>">

                            <button type="submit" name="adicionar" class="btn btn-success">
                                Adicionar ao Carrinho
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

        <div class="col-md-5">
            <div class="card p-4 shadow-sm border-0 rounded-4">
                <h4 class="text-success fw-bold mb-3">Carrinho</h4>

                <?php if (empty($_SESSION["carrinho"])): ?>
                    <p class="text-muted text-center">Nenhum item no carrinho.</p>

                <?php else: ?>
                    <ul class="list-group mb-3">
                        <?php
                        $total = 0;
                        foreach ($_SESSION["carrinho"] as $index => $item):
                            $total += $item["preco"];
                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <?= $item["nome"] ?>
                                    <span class="text-success fw-bold ms-2">
                                        R$ <?= number_format($item["preco"], 2, ',', '.') ?>
                                    </span>
                                </div>

                                <form method="POST" style="margin:0;">
                                    <input type="hidden" name="index" value="<?= $index ?>">
                                    <button class="btn btn-sm btn-danger" name="remover">
                                        Remover
                                    </button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <h5 class="fw-bold text-success">Total:
                        R$ <?= number_format($total, 2, ',', '.') ?>
                    </h5>

                    <form method="POST">
                        <button class="btn btn-lg btn-success w-100 mt-3" name="finalizar">
                            Finalizar Compra
                        </button>
                    </form>

                <?php endif; ?>

            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>

</body>
</html>

