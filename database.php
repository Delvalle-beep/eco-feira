<?php
function conectarDataBase(){
    $conn = new mysqli("localhost","root","","ecofeira");

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }
    return $conn;
}
function enviarMensagem($nome, $email, $telefone, $mensagem){
    $conn = conectarDataBase();

    $stmt = $conn->prepare("INSERT INTO contato (nome, email, telefone, mensagem) VALUES (?, ?, ?, ?)");

    if (!$stmt) {
        return false; 
    }

    $stmt->bind_param("ssss", $nome, $email, $telefone, $mensagem);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true;  
    } else {
        $stmt->close();
        $conn->close();
        return false; 
    }
}

?>