<?php
    include("database.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoFeira</title>
    <!--Este link é para importar o CSS do Bootstrap-->
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <!--Este script é para dar movimento aos componentes do Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    <nav class="navbar navbar-expand-lg navbar-light bg-success bg-gradient">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="index.php">EcoFeira</a>
            <a class="navbar-brand text-white fw-bold" href="login.php" id="user-area">Área do Usuário</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <header class="bg-light py-5" id="header-bg">
        <div class="container text-center">
            <h1 class="fw-bold text-success">Bem-vindo à EcoFeira</h1>
            <p class="lead" style="color: whitesmoke;"> Produtos orgânicos frescos, direto da agricultura familiar.</p></b>
            <a href="#produtos" class="btn btn-success btn-lg mt-3">Ver Produtos</a>
        </div>
    </header>
    <section class="py-5" id="sobre">
        <div class="container">
            <h2 class="fw-bold text-success text-center mb-4">Sobre Nós</h2>
            <p class="text-center mx-auto" style="max-width: 800px;">
                A <strong>EcoFeira</strong> nasceu com o objetivo de aproximar pequenos agricultores e consumidores locais,
                promovendo um comércio mais justo, sustentável e saudável. Valorizamos as famílias produtoras,
                garantindo alimentos frescos e de qualidade diretamente do campo para a sua mesa.
            </p>

            <div class="row mt-4">
                <div class="col-md-4 text-center">
                    <h5 class="fw-bold text-success">Missão</h5>
                    <p>Fortalecer a agricultura familiar e oferecer alimentos orgânicos acessíveis e de origem transparente.</p>
                </div>
                <div class="col-md-4 text-center">
                    <h5 class="fw-bold text-success">Visão</h5>
                    <p>Ser referência regional em comércio sustentável e apoio ao produtor local.</p>
                </div>
                <div class="col-md-4 text-center">
                    <h5 class="fw-bold text-success">Valores</h5>
                    <p>Transparência, sustentabilidade, respeito ao agricultor e qualidade dos alimentos.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5 bg-light" id="produtos">
        <div class="container">
            <h2 class="fw-bold text-success text-center mb-4">Nossos Produtos</h2>

            <div class="row g-4">
                <!-- Produto 1 -->
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <img src="assets/img/hortalicas-frescas.jpg" class="card-img-top" alt="Hortaliças orgânicas">
                        <div class="card-body">
                            <h5 class="card-title">Hortaliças Frescas</h5>
                            <p class="card-text">Alfaces, couves, rúculas e muito mais, colhidas no mesmo dia.</p>
                            <a href="#" class="btn btn-success">Comprar</a>
                        </div>
                    </div>
                </div>

                <!-- Produto 2 -->
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <img src="assets/img/frutas.jpg" class="card-img-top" alt="Frutas orgânicas">
                        <div class="card-body">
                            <h5 class="card-title">Frutas da Estação</h5>
                            <p class="card-text">Mamão, banana, maçã, abacaxi e outras frutas 100% orgânicas.</p>
                            <a href="#" class="btn btn-success">Comprar</a>
                        </div>
                    </div>
                </div>

                <!-- Produto 3 -->
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <img src="assets/img/legumes.jpg" class="card-img-top" alt="Legumes orgânicos">
                        <div class="card-body">
                            <h5 class="card-title">Legumes Selecionados</h5>
                            <p class="card-text">Batata, cenoura, beterraba, abobrinha e muito mais.</p>
                            <a href="#" class="btn btn-success">Comprar</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="fw-bold text-success text-center mb-4">Contato</h2>

            <div class="row justify-content-center mb-5">
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Endereço:</strong> Rua da Agricultura, 123 – Centro
                        </li>
                        <li class="list-group-item">
                            <strong>Telefone:</strong> (11) 99999-9999
                        </li>
                        <li class="list-group-item">
                            <strong>E-mail:</strong> contato@ecofeira.com
                        </li>
                        <li class="list-group-item">
                            <strong>Redes sociais:</strong>
                            <br>
                            Instagram: @ecofeira<br>
                            Facebook: EcoFeira Oficial
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8">

                    <?php if (isset($_GET['status']) && $_GET['status'] === 'sucesso'): ?>
                        <div class="alert alert-success text-center">
                            Sua mensagem foi enviada com sucesso! Em breve retornaremos.
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['status']) && $_GET['status'] === 'erro'): ?>
                        <div class="alert alert-danger text-center">
                            Ocorreu um erro ao enviar a mensagem. Tente novamente.
                        </div>
                    <?php endif; ?>

                    <h4 class="text-success fw-bold text-center mb-3">Envie uma Mensagem</h4>

                    <form action="createMessage.php" method="post">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nome *</label>
                            <input type="text" name="nome" id="nome" class="form-control" placeholder="Digite seu nome" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">E-mail *</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu e-mail" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Telefone *</label>
                            <input type="text" name="telefone" id="telefone" class="form-control" placeholder="(00) 00000-0000" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mensagem *</label>
                            <textarea class="form-control" name="mensagem" id="mensagem" rows="4" placeholder="Escreva sua mensagem" required></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg px-5">Enviar</button>
                        </div>
                    </form>

                </div>
            </div>
            </div>

        </div>
    </section>

    <footer class="bg-success text-white text-center py-3">
        © 2025 EcoFeira – Agricultura Familiar e Orgânicos
    </footer>
    </body>
</html>