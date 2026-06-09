<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Captura de dados usando trim() para remover espaços vazios acidentais
$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');
$nome = trim($_POST['nome'] ?? '');
$cpf = trim($_POST['cpf'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$mensagem = trim($_POST['mensagem'] ?? '');

// Guarda tudo em sessão 
$_SESSION['dados'] = [
    'nome' => $nome,
    'cpf' => $cpf,
    'email' => $email,
    'endereco' => $endereco,
    'telefone' => $telefone,
    'mensagem' => $mensagem,
];


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

    if (empty($nome)) {
        echo "ATENÇÃO: campo 'nome' veio vazio. Verifique o form do registro.php.";
        exit;
    }

    $pdo = conectar();
    $tabela = "cliente";

    try {
        // Verifica se o email já está cadastrado
        $sqlCheck = $pdo->prepare("SELECT 1 FROM " . $tabela . " WHERE email = :email");
        $sqlCheck->bindValue(":email", $email);
        $sqlCheck->execute();
        $existeEmail = $sqlCheck->fetchColumn();

        if ($existeEmail) {
            $_SESSION['registro_erro'] = "Este e-mail já está cadastrado.";
            header('Location: registro.php');
            exit;
        }

        // Insere novo cliente
        $sqlInsert = $pdo->prepare("INSERT INTO " . $tabela . " (nome, cpf, email, endereco, telefone, senha) VALUES (:nome, :cpf, :email, :endereco, :telefone, :senha)");

        $sqlInsert->bindValue(":nome", $nome);
        $sqlInsert->bindValue(":cpf", $cpf);
        $sqlInsert->bindValue(":email", $email);
        $sqlInsert->bindValue(":endereco", $endereco);
        $sqlInsert->bindValue(":telefone", $telefone);
        $sqlInsert->bindValue(":senha", $senha);

        $sqlInsert->execute();

        // Redireciona em caso de sucesso
        header('Location: login.php');
        exit;

    } catch (Exception $erro) {
        echo "ATENÇÃO, erro na inclusão: " . $erro->getMessage();
    }
}
?>