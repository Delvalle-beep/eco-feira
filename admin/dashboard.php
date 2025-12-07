<?php
/**
 * admin/dashboard.php
 * Área restrita - Dashboard administrativo
 * Lista todas as mensagens recebidas via formulário de contato
 */

session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
    header('Location: ../login.php');
    exit;
}

// Incluir configuração do banco
require_once '../config.php';

// Buscar todas as mensagens ordenadas pela mais recente
$conexao = getConexao();
$sql = "SELECT id, nome, email, telefone, mensagem, data_envio 
        FROM contatos 
        ORDER BY data_envio DESC";
$resultado = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EcoFeira</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #faf7f2;
            color: #44403c;
        }
        
        /* Header */
        .admin-header {
            background: linear-gradient(135deg, #166534 0%, #15803d 100%);
            color: white;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logo {
            font-size: 2rem;
        }
        
        .header-title h1 {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }
        
        .header-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .user-role {
            font-size: 0.85rem;
            opacity: 0.8;
        }
        
        .btn-logout {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
        }
        
        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            background: #dcfce7;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #166534;
            margin-bottom: 1rem;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #166534;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #78716c;
            font-size: 0.9rem;
        }
        
        /* Messages Section */
        .messages-section {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .section-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f5f5f4;
        }
        
        .section-header h2 {
            font-size: 1.5rem;
            color: #1c1917;
        }
        
        .messages-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .messages-table th {
            background: #f5f5f4;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #57534e;
            border-bottom: 2px solid #e7e5e4;
        }
        
        .messages-table td {
            padding: 1rem;
            border-bottom: 1px solid #f5f5f4;
            vertical-align: top;
        }
        
        .messages-table tr:hover {
            background: #fafaf9;
        }
        
        .message-preview {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .message-date {
            color: #78716c;
            font-size: 0.85rem;
        }
        
        .btn-delete {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s;
        }
        
        .btn-delete:hover {
            background: #fecaca;
        }
        
        .no-messages {
            text-align: center;
            padding: 3rem;
            color: #a8a29e;
        }
        
        .no-messages i {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        /* Modal para visualizar mensagem completa */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .modal-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f5f5f4;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .btn-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #78716c;
        }
        
        .modal-field {
            margin-bottom: 1.5rem;
        }
        
        .modal-field label {
            display: block;
            font-weight: 600;
            color: #57534e;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .modal-field p {
            color: #44403c;
            line-height: 1.6;
        }
        
        .message-full {
            background: #fafaf9;
            padding: 1rem;
            border-radius: 8px;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="admin-header">
        <div class="header-container">
            <div class="header-left">
                <div class="logo">
                    <i class="fas fa-leaf"></i>
                </div>
                <div class="header-title">
                    <h1>Painel Administrativo</h1>
                    <p class="header-subtitle">EcoFeira</p>
                </div>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <div class="user-name">
                        <i class="fas fa-user-circle"></i>
                        <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>
                    </div>
                    <div class="user-role">Administrador</div>
                </div>
                <a href="logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Sair
                </a>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-value"><?php echo $resultado->num_rows; ?></div>
                <div class="stat-label">Total de Mensagens</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">
                    <?php
                    // Contar mensagens de hoje
                    $sql_hoje = "SELECT COUNT(*) as total FROM contatos WHERE DATE(data_envio) = CURDATE()";
                    $resultado_hoje = $conexao->query($sql_hoje);
                    $hoje = $resultado_hoje->fetch_assoc();
                    echo $hoje['total'];
                    ?>
                </div>
                <div class="stat-label">Mensagens Hoje</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <div class="stat-value">
                    <?php
                    // Contar mensagens desta semana
                    $sql_semana = "SELECT COUNT(*) as total FROM contatos WHERE YEARWEEK(data_envio) = YEARWEEK(NOW())";
                    $resultado_semana = $conexao->query($sql_semana);
                    $semana = $resultado_semana->fetch_assoc();
                    echo $semana['total'];
                    ?>
                </div>
                <div class="stat-label">Mensagens esta Semana</div>
            </div>
        </div>
        
        <!-- Messages Table -->
        <div class="messages-section">
            <div class="section-header">
                <h2><i class="fas fa-inbox"></i> Mensagens Recebidas</h2>
            </div>
            
            <?php if ($resultado->num_rows > 0): ?>
                <table class="messages-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Mensagem</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($mensagem = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $mensagem['id']; ?></td>
                                <td><?php echo htmlspecialchars($mensagem['nome']); ?></td>
                                <td><?php echo htmlspecialchars($mensagem['email']); ?></td>
                                <td><?php echo htmlspecialchars($mensagem['telefone']); ?></td>
                                <td class="message-preview">
                                    <?php echo htmlspecialchars(substr($mensagem['mensagem'], 0, 50)) . '...'; ?>
                                </td>
                                <td class="message-date">
                                    <?php echo date('d/m/Y H:i', strtotime($mensagem['data_envio'])); ?>
                                </td>
                                <td>
                                    <button onclick="verMensagem(<?php echo $mensagem['id']; ?>)" 
                                            style="background: #dcfce7; color: #166534; border: none; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer; margin-right: 0.5rem;">
                                        <i class="fas fa-eye"></i> Ver
                                    </button>
                                    <form method="POST" action="deletar_mensagem.php" style="display: inline;" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta mensagem?');">
                                        <input type="hidden" name="id" value="<?php echo $mensagem['id']; ?>">
                                        <button type="submit" class="btn-delete">
                                            <i class="fas fa-trash"></i> Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-messages">
                    <i class="fas fa-inbox"></i>
                    <p>Nenhuma mensagem recebida ainda.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <!-- Modal para visualizar mensagem -->
    <div class="modal" id="messageModal">
        <div class="modal-content" id="modalContent">
            <!-- Conteúdo será inserido via JavaScript -->
        </div>
    </div>
    
    <script>
        // Dados das mensagens em JSON para o JavaScript
        const mensagens = <?php 
            $conexao2 = getConexao();
            $sql2 = "SELECT * FROM contatos ORDER BY data_envio DESC";
            $resultado2 = $conexao2->query($sql2);
            $dados = [];
            while ($row = $resultado2->fetch_assoc()) {
                $dados[] = $row;
            }
            echo json_encode($dados);
        ?>;
        
        function verMensagem(id) {
            const mensagem = mensagens.find(m => m.id == id);
            if (!mensagem) return;
            
            const modal = document.getElementById('messageModal');
            const content = document.getElementById('modalContent');
            
            content.innerHTML = `
                <div class="modal-header">
                    <h2>Detalhes da Mensagem #${mensagem.id}</h2>
                    <button class="btn-close" onclick="fecharModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-field">
                    <label>Nome:</label>
                    <p>${mensagem.nome}</p>
                </div>
                <div class="modal-field">
                    <label>E-mail:</label>
                    <p>${mensagem.email}</p>
                </div>
                <div class="modal-field">
                    <label>Telefone:</label>
                    <p>${mensagem.telefone}</p>
                </div>
                <div class="modal-field">
                    <label>Data de Envio:</label>
                    <p>${new Date(mensagem.data_envio).toLocaleString('pt-BR')}</p>
                </div>
                <div class="modal-field">
                    <label>Mensagem Completa:</label>
                    <div class="message-full">${mensagem.mensagem}</div>
                </div>
            `;
            
            modal.classList.add('active');
        }
        
        function fecharModal() {
            document.getElementById('messageModal').classList.remove('active');
        }
        
        // Fechar modal ao clicar fora
        document.getElementById('messageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModal();
            }
        });
    </script>
</body>
</html>
<?php
$conexao->close();
?>