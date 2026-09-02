<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

function responder(int $status, array $dados): void
{
    http_response_code($status);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}

function conectar(): PDO
{
    $local_server = "DESKTOP-54EB1G4\\SQLEXPRESS";
    $usuario_server = "superclick";
    $senha_server = "teste";
    $banco_de_dados = "SUPERCLICK";

    $dsn = "sqlsrv:server=$local_server;database=$banco_de_dados;Encrypt=true;TrustServerCertificate=true";
    $pdo = new PDO($dsn, $usuario_server, $senha_server);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(405, [
        'sucesso' => false,
        'mensagem' => 'Método não permitido.'
    ]);
}

$idCliente = (int) ($_SESSION['id_cliente'] ?? 0);
if (empty($_SESSION['logado']) || $idCliente <= 0) {
    responder(401, [
        'sucesso' => false,
        'mensagem' => 'Você precisa estar logado para finalizar a compra.'
    ]);
}

$entrada = json_decode(file_get_contents('php://input'), true);
$itens = $entrada['itens'] ?? null;

if (!is_array($itens) || count($itens) === 0) {
    responder(400, [
        'sucesso' => false,
        'mensagem' => 'O carrinho está vazio.'
    ]);
}

try {
    $pdo = conectar();
    $pdo->beginTransaction();

    $buscarProduto = $pdo->prepare(
        "SELECT Id_Produto, Nome, Marca, Preco
         FROM Produto
         WHERE Nome = :nome AND Marca = :marca"
    );

    $itensValidados = [];
    $valorTotal = 0.0;

    foreach ($itens as $item) {
        $nome = trim((string) ($item['nome'] ?? ''));
        $marca = trim((string) ($item['marca'] ?? ''));
        $quantidade = (int) ($item['quantidade'] ?? 0);

        if ($nome === '' || $marca === '' || $quantidade <= 0) {
            throw new RuntimeException('Há um item inválido no carrinho.');
        }

        $buscarProduto->execute([
            ':nome' => $nome,
            ':marca' => $marca,
        ]);
        $produto = $buscarProduto->fetch(PDO::FETCH_ASSOC);

        if (!$produto) {
            throw new RuntimeException("Produto não encontrado no banco: {$nome} ({$marca}).");
        }

        $precoUnitario = (float) $produto['Preco'];
        $valorTotal += $precoUnitario * $quantidade;

        $itensValidados[] = [
            'id_produto' => (int) $produto['Id_Produto'],
            'quantidade' => $quantidade,
            'preco_unitario' => $precoUnitario,
        ];
    }

    // OUTPUT INSERTED é a forma mais segura de obter o IDENTITY recém-criado no SQL Server.
    $inserirPedido = $pdo->prepare(
        "INSERT INTO Pedido (Id_Cliente, Data_Pedido, Status_Pedido, Valor_Total)
         OUTPUT INSERTED.Id_Pedido
         VALUES (:id_cliente, CAST(GETDATE() AS DATE), :status_pedido, :valor_total)"
    );
    $inserirPedido->execute([
        ':id_cliente' => $idCliente,
        ':status_pedido' => 'Iniciado',
        ':valor_total' => number_format($valorTotal, 2, '.', ''),
    ]);

    $idPedido = (int) $inserirPedido->fetchColumn();
    if ($idPedido <= 0) {
        throw new RuntimeException('Não foi possível obter o número do pedido.');
    }

    $inserirItem = $pdo->prepare(
        "INSERT INTO Pedido_Item (Id_Pedido, Id_Produto, Quantidade, Preco_Unitario)
         VALUES (:id_pedido, :id_produto, :quantidade, :preco_unitario)"
    );

    foreach ($itensValidados as $item) {
        $inserirItem->execute([
            ':id_pedido' => $idPedido,
            ':id_produto' => $item['id_produto'],
            ':quantidade' => $item['quantidade'],
            ':preco_unitario' => number_format($item['preco_unitario'], 2, '.', ''),
        ]);
    }

    $pdo->commit();

    responder(200, [
        'sucesso' => true,
        'mensagem' => 'Compra finalizada com sucesso!',
        'id_pedido' => $idPedido,
        'valor_total' => number_format($valorTotal, 2, '.', ''),
    ]);
} catch (Throwable $erro) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    responder(500, [
        'sucesso' => false,
        'mensagem' => $erro->getMessage(),
    ]);
}
