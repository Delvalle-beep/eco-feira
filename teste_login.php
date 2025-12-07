<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config.php';

echo "<h1>🔍 Teste de Login Detalhado</h1>";
echo "<style>
    body { font-family: Arial; padding: 20px; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    pre { background: #f4f4f4; padding: 10px; border-radius: 5px; }
    hr { margin: 20px 0; }
</style>";

// Simular dados do POST
$login_teste = 'admin';
$senha_teste = 'admin123';

echo "<h2>📋 Dados de Teste:</h2>";
echo "<p>Login: de>$login_teste</code></p>";
echo "<p>Senha: de>$senha_teste</code></p>";
echo "<hr>";

// Conectar ao banco
$conexao = getConexao();
echo "<p class='success'>✅ Conexão estabelecida</p>";

// Buscar usuário
echo "<h2>1️⃣ Buscando usuário no banco...</h2>";
$sql = "SELECT id, login, senha, nome_completo FROM usuarios WHERE login = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $login_teste);
$stmt->execute();
$resultado = $stmt->get_result();

echo "<p>Registros encontrados: <strong>" . $resultado->num_rows . "</strong></p>";

if ($resultado->num_rows === 1) {
    echo "<p class='success'>✅ Usuário encontrado!</p>";
    $usuario = $resultado->fetch_assoc();
    
    echo "<h3>Dados do usuário:</h3>";
    echo "<pre>";
    echo "ID: " . $usuario['id'] . "\n";
    echo "Login: '" . $usuario['login'] . "'\n";
    echo "Nome: " . $usuario['nome_completo'] . "\n";
    echo "Tamanho do hash: " . strlen($usuario['senha']) . " caracteres\n";
    echo "Hash: " . $usuario['senha'] . "\n";
    echo "</pre>";
    
    // Verificar se o hash tem o tamanho correto
    if (strlen($usuario['senha']) !== 60) {
        echo "<p class='error'>❌ PROBLEMA ENCONTRADO!</p>";
        echo "<p>O hash da senha tem " . strlen($usuario['senha']) . " caracteres, mas deveria ter <strong>60 caracteres</strong>!</p>";
        echo "<p>O hash foi truncado. Execute este SQL no phpMyAdmin:</p>";
        echo "<pre>UPDATE usuarios 
SET senha = '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE login = 'admin';</pre>";
        die();
    } else {
        echo "<p class='success'>✅ Tamanho do hash correto (60 caracteres)</p>";
    }
    
    echo "<hr>";
    echo "<h2>2️⃣ Testando password_verify()...</h2>";
    
    echo "<p>Senha digitada: de>$senha_teste</code></p>";
    echo "<p>Hash do banco: de>" . $usuario['senha'] . "</code></p>";
    
    if (password_verify($senha_teste, $usuario['senha'])) {
        echo "<p class='success'>✅✅✅ SENHA CORRETA! O password_verify() FUNCIONOU!</p>";
        
        echo "<hr>";
        echo "<h2>3️⃣ Testando criação de sessão...</h2>";
        
        $_SESSION['usuario_logado'] = true;
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome_completo'];
        $_SESSION['usuario_login'] = $usuario['login'];
        
        echo "<p class='success'>✅ Sessão criada com sucesso!</p>";
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
        
        echo "<hr>";
        echo "<h2>4️⃣ Verificando arquivo dashboard...</h2>";
        
        $dashboard_path = __DIR__ . '/admin/dashboard.php';
        echo "<p>Caminho esperado: de>$dashboard_path</code></p>";
        
        if (file_exists($dashboard_path)) {
            echo "<p class='success'>✅ Arquivo admin/dashboard.php existe!</p>";
            
            echo "<hr>";
            echo "<h2>🎉 DIAGNÓSTICO FINAL</h2>";
            echo "<div style='background: #d4edda; padding: 20px; border-radius: 10px; border: 2px solid #28a745;'>";
            echo "<p class='success' style='font-size: 20px;'>✅ TUDO ESTÁ FUNCIONANDO CORRETAMENTE!</p>";
            echo "<p><strong>O login DEVERIA funcionar perfeitamente.</strong></p>";
            echo "<p>Tente fazer o login novamente em: <a href='login.php'>login.php</a></p>";
            echo "<p>Use: de>admin</code> / de>admin123</code></p>";
            echo "</div>";
            
        } else {
            echo "<p class='error'>❌ Arquivo admin/dashboard.php NÃO EXISTE!</p>";
            echo "<p><strong>ESTE É O PROBLEMA!</strong></p>";
            echo "<p>Você precisa criar a pasta de>admin/</code> e o arquivo de>dashboard.php</code></p>";
        }
        
    } else {
        echo "<p class='error'>❌ SENHA INCORRETA! O password_verify() FALHOU!</p>";
        echo "<p><strong>ESTE É O PROBLEMA!</strong></p>";
        
        // Testar com o hash correto conhecido
        $hash_correto = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        echo "<hr>";
        echo "<h3>Teste com hash conhecido:</h3>";
        echo "<p>Hash correto: de>$hash_correto</code></p>";
        
        if (password_verify($senha_teste, $hash_correto)) {
            echo "<p class='success'>✅ A função password_verify() está funcionando</p>";
            echo "<p class='error'>❌ Mas o hash no banco está ERRADO!</p>";
            echo "<p>Execute este SQL:</p>";
            echo "<pre>UPDATE usuarios 
SET senha = '$hash_correto' 
WHERE login = 'admin';</pre>";
        } else {
            echo "<p class='error'>❌ A função password_verify() NÃO está funcionando!</p>";
            echo "<p>Problema com a instalação do PHP. Verifique a versão do PHP.</p>";
        }
    }
    
} else {
    echo "<p class='error'>❌ Usuário 'admin' não encontrado!</p>";
    echo "<p>Execute este SQL no phpMyAdmin:</p>";
    echo "<pre>INSERT INTO usuarios (login, senha, nome_completo) 
VALUES ('admin', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador EcoFeira');</pre>";
}

$stmt->close();
$conexao->close();
?>
