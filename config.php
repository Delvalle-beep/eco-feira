<?php
/**
 * config.php
 * Arquivo de configuração e conexão com o banco de dados MySQL
 * Projeto: EcoFeira
 */

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'ecofeira');
define('DB_USER', 'root');
define('DB_PASS', ''); // Senha vazia no XAMPP padrão

/**
 * Função para obter conexão com o banco de dados
 * @return mysqli Objeto de conexão MySQLi
 */
function getConexao() {
    // Criar nova conexão MySQLi
    $conexao = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Verificar se houve erro na conexão
    if ($conexao->connect_error) {
        die("Erro ao conectar com o banco de dados: " . $conexao->connect_error);
    }
    
    // Definir charset para UTF-8 (evita problemas com acentuação)
    $conexao->set_charset("utf8mb4");
    
    return $conexao;
}

/**
 * Função auxiliar para debug (remover em produção)
 */
function debug($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}
?>