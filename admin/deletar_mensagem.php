<?php
/**
 * admin/deletar_mensagem.php
 * Exclui uma mensagem do banco de dados
 */

session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
    header('Location: ../login.php');
    exit;
}

require_once '../config.php';

// Verificar se foi enviado via POST e se tem o ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    
    $id = (int)$_POST['id']; // Converter para inteiro (segurança)
    
    // Obter conexão
    $conexao = getConexao();
    
    // Preparar query de exclusão usando prepared statement
    $sql = "DELETE FROM contatos WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            // Sucesso
            $_SESSION['mensagem_sucesso'] = "Mensagem excluída com sucesso!";
        } else {
            // Erro ao executar
            $_SESSION['mensagem_erro'] = "Erro ao excluir a mensagem.";
        }
        
        $stmt->close();
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao preparar a exclusão.";
    }
    
    $conexao->close();
    
} else {
    $_SESSION['mensagem_erro'] = "Requisição inválida.";
}

// Redirecionar de volta para o dashboard
header('Location: dashboard.php');
exit;
?>