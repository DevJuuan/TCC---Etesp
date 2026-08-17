<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Captura de dados usando trim() para remover espaços vazios acidentais
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$mensagem = trim($_POST['mensagem'] ?? '');

function conectar()
{
    $local_server = "DESKTOP-54EB1G4\\SQLEXPRESS";
    $usuario_server = "superclick";
    $senha_server = "teste";
    $banco_de_dados = "SUPERCLICK";

    try {
        $dsn = "sqlsrv:server=$local_server;database=$banco_de_dados";
        $pdo = new PDO($dsn, $usuario_server, $senha_server);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (Exception $erro) {
        die("ATENÇÃO - ERRO NA CONEXÃO: " . $erro->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($nome) || empty($email) || empty($mensagem)) {
        echo "ATENÇÃO: preencha nome, email e mensagem.";
        exit;
    }

    $pdo = conectar();
    $tabela = "contato";

    try {
        $sql = $pdo->prepare(
            "INSERT INTO " . $tabela . " (nome, email, mensagem) VALUES (:nome, :email, :mensagem)"
        );

        $sql->bindValue(":nome", $nome);
        $sql->bindValue(":email", $email);
        $sql->bindValue(":mensagem", $mensagem);

        $sql->execute();

        header('Location: contato.php');
        exit;
    } catch (Exception $erro) {
        echo "ATENÇÃO, erro na inclusão: " . $erro->getMessage();
    }
}
?>