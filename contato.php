<?php
/**
 * contato.php
 * Processa o formulário de contato e salva no banco de dados
 * Projeto: EcoFeira
 */

// Incluir arquivo de configuração
require_once 'config.php';

// Verificar se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Receber e limpar os dados do formulário
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');
    
    // Validação básica no servidor
    $erros = [];
    
    if (empty($nome)) {
        $erros[] = "O nome é obrigatório.";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "E-mail inválido.";
    }
    
    if (empty($telefone)) {
        $erros[] = "O telefone é obrigatório.";
    }
    
    if (empty($mensagem)) {
        $erros[] = "A mensagem é obrigatória.";
    }
    
    // Se houver erros, exibir e parar
    if (!empty($erros)) {
        echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Erro</title></head><body>";
        echo "<h2>Erro no envio:</h2><ul>";
        foreach ($erros as $erro) {
            echo "<li>" . htmlspecialchars($erro) . "</li>";
        }
        echo "</ul>";
        echo "<a href='index.php'>Voltar</a></body></html>";
        exit;
    }
    
    // Obter conexão com o banco
    $conexao = getConexao();
    
    // Preparar a query SQL usando prepared statement (previne SQL Injection)
    $sql = "INSERT INTO contatos (nome, email, telefone, mensagem) VALUES (?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    
    if ($stmt) {
        // Vincular parâmetros (s = string)
        $stmt->bind_param("ssss", $nome, $email, $telefone, $mensagem);
        
        // Executar a query
        if ($stmt->execute()) {
            // Sucesso - Exibir mensagem de confirmação
            echo "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Mensagem Enviada - EcoFeira</title>
                <link rel='stylesheet' href='style.css'>
                <style>
                    .success-container {
                        max-width: 600px;
                        margin: 100px auto;
                        padding: 40px;
                        background: white;
                        border-radius: 16px;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
                        text-align: center;
                    }
                    .success-icon {
                        font-size: 64px;
                        color: #166534;
                        margin-bottom: 20px;
                    }
                    .success-title {
                        font-size: 28px;
                        color: #166534;
                        margin-bottom: 15px;
                    }
                    .success-message {
                        color: #78716c;
                        margin-bottom: 30px;
                        line-height: 1.6;
                    }
                    .btn-voltar {
                        display: inline-block;
                        padding: 12px 30px;
                        background: linear-gradient(135deg, #166534 0%, #22c55e 100%);
                        color: white;
                        text-decoration: none;
                        border-radius: 50px;
                        font-weight: 600;
                        transition: transform 0.3s;
                    }
                    .btn-voltar:hover {
                        transform: scale(1.05);
                    }
                </style>
            </head>
            <body>
                <div class='success-container'>
                    <div class='success-icon'>✓</div>
                    <h1 class='success-title'>Mensagem Enviada com Sucesso!</h1>
                    <p class='success-message'>
                        Obrigado por entrar em contato, <strong>" . htmlspecialchars($nome) . "</strong>!<br>
                        Recebemos sua mensagem e responderemos em breve no e-mail <strong>" . htmlspecialchars($email) . "</strong>.
                    </p>
                    <a href='index.php' class='btn-voltar'>Voltar para a EcoFeira</a>
                </div>
            </body>
            </html>";
            
        } else {
            echo "Erro ao salvar a mensagem: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Erro ao preparar a query: " . $conexao->error;
    }
    
    // Fechar conexão
    $conexao->close();
    
} else {
    // Se alguém acessar diretamente sem enviar o formulário
    header('Location: index.php');
    exit;
}
?>