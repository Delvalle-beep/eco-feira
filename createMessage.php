<?php
include("database.php");

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$mensagem = $_POST['mensagem'];

if (enviarMensagem($nome, $email, $telefone, $mensagem)) {
    header('Location: index.php?status=sucesso');
    exit();
} else {
    header('Location: index.php?status=erro');
    exit();
}
