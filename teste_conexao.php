<?php
require_once 'config.php';

echo "<h1>Teste de Conexão com MySQL</h1>";

try {
    $conexao = getConexao();
    echo "<p style='color: green; font-weight: bold;'>✅ Conexão com o banco de dados realizada com SUCESSO!</p>";
    
    // Testar se as tabelas existem
    $resultado = $conexao->query("SHOW TABLES");
    echo "<h2>Tabelas encontradas:</h2><ul>";
    while ($row = $resultado->fetch_array()) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
    
    // Verificar usuário admin
    $sql = "SELECT login, nome_completo FROM usuarios WHERE login = 'admin'";
    $resultado = $conexao->query($sql);
    
    if ($resultado->num_rows > 0) {
        $user = $resultado->fetch_assoc();
        echo "<p style='color: green;'>✅ Usuário admin encontrado: <strong>" . $user['nome_completo'] . "</strong></p>";
    }
    
    $conexao->close();
    
} catch (Exception $e) {
    echo "<p style='color: red; font-weight: bold;'>❌ ERRO: " . $e->getMessage() . "</p>";
}
?>
```

---

### **PASSO 6: Testar a Conexão**

1. No navegador, acesse:
```
   http://localhost/ecofeira/teste_conexao.php
```

2. Você deve ver:
   - ✅ **Conexão realizada com SUCESSO**
   - Lista das tabelas: `contatos` e `usuarios`
   - ✅ **Usuário admin encontrado**

---

### **PASSO 7: Copiar os Outros Arquivos**

Agora copie os arquivos que criei anteriormente para a pasta `ecofeira`:

1. `index.php` (Homepage completa)
2. `style.css` (Estilos)
3. `contato.php` (Processar formulário)
4. `login.php` (Página de login)

**Criar a pasta `admin`:**
1. Dentro de `C:\xampp\htdocs\ecofeira\`, crie a pasta `admin`
2. Dentro de `admin`, crie:
   - `dashboard.php`
   - `deletar_mensagem.php`
   - `logout.php`

---

## 🎯 Estrutura Final
```
C:\xampp\htdocs\ecofeira\
│
├── config.php
├── teste_conexao.php      ← Para testar
├── index.php
├── style.css
├── contato.php
├── login.php
│
└── admin\
    ├── dashboard.php
    ├── deletar_mensagem.php
    └── logout.php
```

---

## 🧪 Testando Tudo

### 1. Testar Homepage:
```
http://localhost/ecofeira/
```

### 2. Testar Formulário de Contato:
- Preencha o formulário na homepage
- Clique em "Enviar Mensagem"
- Deve aparecer: ✅ "Mensagem Enviada com Sucesso!"

### 3. Verificar no Banco:
- Volte ao phpMyAdmin
- Clique na tabela `contatos`
- Clique em "Procurar"
- Você verá a mensagem cadastrada!

### 4. Testar Login Admin:
```
http://localhost/ecofeira/login.php