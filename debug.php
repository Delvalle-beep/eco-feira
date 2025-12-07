<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Debug - EcoFeira</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .card { background: white; padding: 20px; margin: 10px 0; border-radius: 5px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        table { border-collapse: collapse; width: 100%; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #4CAF50; color: white; }
        code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>🔍 Debug do Sistema de Login - EcoFeira</h1>

    <?php
    // 1. Testar conexão
    echo "<div class='card'>";
    echo "<h2>1. Teste de Conexão</h2>";
    try {
        $conexao = getConexao();
        echo "<p class='success'>✅ Conexão estabelecida com sucesso!</p>";
        echo "<p>Banco: <strong>" . DB_NAME . "</strong></p>";
    } catch (Exception $e) {
        echo "<p class='error'>❌ Erro: " . $e->getMessage() . "</p>";
        die();
    }
    echo "</div>";

    // 2. Verificar tabelas
    echo "<div class='card'>";
    echo "<h2>2. Tabelas no Banco de Dados</h2>";
    $resultado = $conexao->query("SHOW TABLES");
    if ($resultado->num_rows > 0) {
        echo "<ul>";
        while ($row = $resultado->fetch_array()) {
            echo "<li>" . $row[0] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='error'>❌ Nenhuma tabela encontrada!</p>";
    }
    echo "</div>";

    // 3. Verificar estrutura da tabela usuarios
    echo "<div class='card'>";
    echo "<h2>3. Estrutura da Tabela 'usuarios'</h2>";
    $resultado = $conexao->query("DESCRIBE usuarios");
    if ($resultado) {
        echo "<table>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Chave</th><th>Padrão</th><th>Extra</th></tr>";
        while ($row = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    echo "</div>";

    // 4. Listar todos os usuários
    echo "<div class='card'>";
    echo "<h2>4. Usuários Cadastrados</h2>";
    $sql = "SELECT id, login, senha, nome_completo, criado_em FROM usuarios";
    $resultado = $conexao->query($sql);
    
    if ($resultado->num_rows > 0) {
        echo "<p class='success'>✅ Encontrados " . $resultado->num_rows . " usuário(s)</p>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Login</th><th>Nome</th><th>Senha (hash)</th><th>Criado em</th></tr>";
        while ($user = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['login'] . "</td>";
            echo "<td>" . $user['nome_completo'] . "</td>";
            echo "<td>de>" . substr($user['senha'], 0, 30) . "...</code></td>";
            echo "<td>" . $user['criado_em'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'>❌ NENHUM USUÁRIO CADASTRADO!</p>";
        echo "<p>Execute este SQL no phpMyAdmin:</p>";
        echo "<pre>INSERT INTO usuarios (login, senha, nome_completo) 
VALUES ('admin', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador EcoFeira');</pre>";
    }
    echo "</div>";

    // 5. Testar busca do usuário admin
    echo "<div class='card'>";
    echo "<h2>5. Teste: Buscar Usuário 'admin'</h2>";
    $sql = "SELECT id, login, senha, nome_completo FROM usuarios WHERE login = 'admin'";
    $resultado = $conexao->query($sql);
    
    if ($resultado->num_rows === 1) {
        echo "<p class='success'>✅ Usuário 'admin' encontrado!</p>";
        $admin = $resultado->fetch_assoc();
        echo "<p><strong>ID:</strong> " . $admin['id'] . "</p>";
        echo "<p><strong>Login:</strong> " . $admin['login'] . "</p>";
        echo "<p><strong>Nome:</strong> " . $admin['nome_completo'] . "</p>";
        echo "<p><strong>Hash completo:</strong><br>de>" . $admin['senha'] . "</code></p>";
        
        // 6. Testar password_verify
        echo "<hr>";
        echo "<h3>6. Teste de Verificação de Senha</h3>";
        $senha_teste = 'admin123';
        $hash_banco = $admin['senha'];
        
        echo "<p>Testando senha: de>admin123</code></p>";
        echo "<p>Contra hash: de>" . $hash_banco . "</code></p>";
        
        if (password_verify($senha_teste, $hash_banco)) {
            echo "<p class='success'>✅ SUCESSO! A senha 'admin123' bate com o hash!</p>";
            echo "<p style='background: #fff3cd; padding: 10px; border-left: 4px solid #ffc107;'>";
            echo "⚠️ <strong>O banco está correto!</strong> O problema está no arquivo de>login.php</code>.<br>";
            echo "Verifique se o login.php está usando de>password_verify()</code> corretamente.";
            echo "</p>";
        } else {
            echo "<p class='error'>❌ ERRO! A senha não bate com o hash!</p>";
            echo "<p>O hash no banco está incorreto. Execute este SQL:</p>";
            echo "<pre>UPDATE usuarios SET senha = '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE login = 'admin';</pre>";
        }
        
    } else {
        echo "<p class='error'>❌ Usuário 'admin' NÃO encontrado!</p>";
        echo "<p>Encontrados: " . $resultado->num_rows . " registros</p>";
    }
    echo "</div>";

    // 7. Simular o processo de login
    echo "<div class='card'>";
    echo "<h2>7. Simulação do Processo de Login</h2>";
    
    $login_teste = 'admin';
    $senha_teste = 'admin123';
    
    echo "<p>Tentando login com:</p>";
    echo "<p><strong>Usuário:</strong> de>" . $login_teste . "</code></p>";
    echo "<p><strong>Senha:</strong> de>" . $senha_teste . "</code></p>";
    echo "<hr>";
    
    $sql = "SELECT id, login, senha, nome_completo FROM usuarios WHERE login = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $login_teste);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 1) {
        echo "<p class='success'>✅ Passo 1: Usuário encontrado no banco</p>";
        $usuario = $resultado->fetch_assoc();
        
        if (password_verify($senha_teste, $usuario['senha'])) {
            echo "<p class='success'>✅ Passo 2: Senha verificada com sucesso!</p>";
            echo "<p class='success' style='font-size: 18px;'>🎉 LOGIN DEVERIA FUNCIONAR!</p>";
            echo "<p style='background: #d4edda; padding: 15px; border-radius: 5px;'>";
            echo "<strong>Diagnóstico:</strong> O banco de dados está correto!<br>";
            echo "O problema está no arquivo <strong>login.php</strong>.<br><br>";
            echo "Possíveis causas:<br>";
            echo "• Erro na lógica do if/else<br>";
            echo "• Redirecionamento incorreto<br>";
            echo "• Sessão não iniciada<br>";
            echo "• Caminho errado para admin/dashboard.php";
            echo "</p>";
        } else {
            echo "<p class='error'>❌ Passo 2: Senha incorreta!</p>";
        }
    } else {
        echo "<p class='error'>❌ Passo 1: Usuário não encontrado</p>";
    }
    echo "</div>";

    $conexao->close();
    ?>

    <div class="card" style="background: #e8f5e9;">
        <h2>📋 Próximos Passos</h2>
        <p>Após executar este debug, faça o seguinte:</p>
        <ol>
            <li>Tire um print desta página inteira</li>
            <li>Me envie o que apareceu nas seções 4, 5, 6 e 7</li>
            <li>Se a seção 7 mostrar "LOGIN DEVERIA FUNCIONAR", me envie o conteúdo do arquivo de>login.php</code></li>
        </ol>
    </div>
</body>
</html>
