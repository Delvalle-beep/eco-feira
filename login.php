<?php
/**
 * login.php
 * Página de autenticação para acesso à área administrativa
 * Projeto: EcoFeira
 */

session_start();
require_once 'config.php';

// Se já estiver logado, redirecionar para o dashboard
if (isset($_SESSION['usuario_logado'])) {
    header('Location: admin/dashboard.php');
    exit;
}

$erro = '';

// Processar login quando o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $login = trim($_POST['login'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    // Validação básica
    if (empty($login) || empty($senha)) {
        $erro = "Preencha todos os campos.";
    } else {
        // Buscar usuário no banco de dados
        $conexao = getConexao();
        
        $sql = "SELECT id, login, senha, nome_completo FROM usuarios WHERE login = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            
            // Verificar se a senha está correta usando password_verify
            if (password_verify($senha, $usuario['senha'])) {
                // Login bem-sucedido - Criar sessão
                $_SESSION['usuario_logado'] = true;
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome_completo'];
                $_SESSION['usuario_login'] = $usuario['login'];
                
                // Redirecionar para o dashboard
                header('Location: admin/dashboard.php');
                exit;
            } else {
                $erro = "Login ou senha incorretos.";
            }
        } else {
            $erro = "Login ou senha incorretos.";
        }
        
        $stmt->close();
        $conexao->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EcoFeira</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #166534 0%, #15803d 50%, #22c55e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
            padding: 40px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-logo {
            font-size: 48px;
            color: #166534;
            margin-bottom: 15px;
        }
        
        .login-title {
            font-size: 24px;
            color: #1c1917;
            margin-bottom: 8px;
        }
        
        .login-subtitle {
            color: #78716c;
            font-size: 14px;
        }
        
        .alert-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: #44403c;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a8a29e;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px 14px 45px;
            border: 2px solid #e7e5e4;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s;
            outline: none;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #166534;
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #166534 0%, #22c55e 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            transform: scale(1.02);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e7e5e4;
        }
        
        .login-footer a {
            color: #166534;
            text-decoration: none;
            font-size: 14px;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .credentials-info {
            background: #fef3c7;
            border: 1px solid #fde68a;
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 12px;
            color: #78350f;
        }
        
        .credentials-info strong {
            display: block;
            margin-bottom: 5px;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">
                <i class="fas fa-leaf"></i>
            </div>
            <h1 class="login-title">Área Administrativa</h1>
            <p class="login-subtitle">EcoFeira</p>
        </div>
        
        <?php if (!empty($erro)): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="login">Usuário</label>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" 
                           id="login" 
                           name="login" 
                           placeholder="Digite seu usuário"
                           required
                           autofocus>
                </div>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" 
                           id="senha" 
                           name="senha" 
                           placeholder="Digite sua senha"
                           required>
                </div>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i>
                Entrar
            </button>
        </form>
        
        <div class="credentials-info">
            <strong>Credenciais padrão para teste:</strong>
            Usuário: <code>admin</code><br>
            Senha: <code>admin123</code>
        </div>
        
        <div class="login-footer">
            <a href="index.php">
                <i class="fas fa-arrow-left"></i>
                Voltar para a EcoFeira
            </a>
        </div>
    </div>
</body>
</html>
