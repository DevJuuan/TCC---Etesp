<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function conectar()
{
    $local_server = "DESKTOP-54EB1G4\\SQLEXPRESS";
    $usuario_server = "superclick";
    $senha_server = "teste";
    $banco_de_dados = "SUPERCLICK";

    try {
        $dsn = "sqlsrv:server=$local_server;database=$banco_de_dados;Encrypt=true;TrustServerCertificate=true";
        $pdo = new PDO($dsn, $usuario_server, $senha_server);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (Exception $erro) {
        die("ATENÇÃO - ERRO NA CONEXÃO: " . $erro->getMessage());
    }
}

$mensagem_perfil = "";

// Atualização dos dados do cliente (perfil)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'atualizar') {

    $id_cliente = $_SESSION['id_cliente'] ?? 0;
    $nome = trim($_POST['nome'] ?? '');
    $cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cep = preg_replace('/\D/', '', $_POST['cep'] ?? '');
    $rua = trim($_POST['rua'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $bairro = trim($_POST['bairro'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $estado = trim($_POST['estado'] ?? '');
    $complemento = trim($_POST['complemento'] ?? '');
    $telefone = preg_replace('/\D/', '', $_POST['telefone'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($id_cliente > 0 && !empty($nome)) {
        try {
            $pdo = conectar();

            // Atualiza o perfil (mantém a senha atual se o campo vier vazio)
            if (!empty($senha)) {
                $sqlUpdate = $pdo->prepare(
                    "UPDATE Cliente SET Nome = :nome, CPF = :cpf, Email = :email,
                     CEP = :cep, Rua = :rua, Numero = :numero, Bairro = :bairro, Cidade = :cidade, Estado =:estado, Complemento =:complemento, Telefone = :telefone, Senha = :senha
                     WHERE Id_Cliente = :id"
                );
                $sqlUpdate->bindValue(":senha", $senha);
            } else {
                $sqlUpdate = $pdo->prepare(
                    "UPDATE Cliente SET Nome = :nome, CPF = :cpf, Email = :email,
                     CEP = :cep, Rua = :rua, Numero = :numero, Bairro = :bairro, Cidade = :cidade, Estado =:estado, Complemento =:complemento, Telefone = :telefone
                     WHERE Id_Cliente = :id"
                );
            }

            $sqlUpdate->bindValue(":nome", $nome);
            $sqlUpdate->bindValue(":cpf", $cpf);
            $sqlUpdate->bindValue(":email", $email);
            $sqlUpdate->bindValue(":cep", $cep);
            $sqlUpdate->bindValue(":rua", $rua);
            $sqlUpdate->bindValue(":numero", $numero);
            $sqlUpdate->bindValue(":bairro", $bairro);
            $sqlUpdate->bindValue(":cidade", $cidade);
            $sqlUpdate->bindValue(":estado", $estado);
            $sqlUpdate->bindValue(":complemento", $complemento);
            $sqlUpdate->bindValue(":telefone", $telefone);
            $sqlUpdate->bindValue(":id", $id_cliente);
            $sqlUpdate->execute();

            // Atualiza a sessão com o novo nome
            $_SESSION['nome_cliente'] = $nome;

            $mensagem_perfil = "Dados atualizados com sucesso!";
        } catch (Exception $erro) {
            $mensagem_perfil = "Erro ao atualizar: " . $erro->getMessage();
        }
    } else {
        $mensagem_perfil = "Preencha os campos obrigatórios.";
    }
}

// Cancelamento de pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'cancelar_pedido') {

    if (empty($_SESSION['logado'])) {
        header('Location: login.php');
        exit;
    }

    $id_cliente_cancelamento = (int) ($_SESSION['id_cliente'] ?? 0);
    $id_pedido_cancelamento = (int) ($_POST['id_pedido'] ?? 0);
    $csrf_recebido = $_POST['csrf_token'] ?? '';

    if (
        empty($csrf_recebido) ||
        !hash_equals($_SESSION['csrf_token'], $csrf_recebido)
    ) {
        $mensagem_perfil = "Não foi possível validar a solicitação de cancelamento.";
    } elseif ($id_cliente_cancelamento <= 0 || $id_pedido_cancelamento <= 0) {
        $mensagem_perfil = "Pedido inválido.";
    } else {
        try {
            $pdo = conectar();

            $sqlCancelar = $pdo->prepare(
                "UPDATE Pedido
                 SET Status_Pedido = :novo_status
                 WHERE Id_Pedido = :id_pedido
                   AND Id_Cliente = :id_cliente
                   AND Status_Pedido = :status_atual"
            );

            $sqlCancelar->bindValue(":novo_status", "Cancelado");
            $sqlCancelar->bindValue(":id_pedido", $id_pedido_cancelamento, PDO::PARAM_INT);
            $sqlCancelar->bindValue(":id_cliente", $id_cliente_cancelamento, PDO::PARAM_INT);
            $sqlCancelar->bindValue(":status_atual", "Iniciado");
            $sqlCancelar->execute();

            if ($sqlCancelar->rowCount() > 0) {
                $mensagem_perfil = "Pedido cancelado com sucesso.";
            } else {
                $mensagem_perfil = "Este pedido não pode ser cancelado.";
            }

        } catch (Exception $erro) {
            $mensagem_perfil = "Erro ao cancelar o pedido: " . $erro->getMessage();
        }
    }
}

// Recebe os dados de cadastro.
// O formulário de registro não envia o campo "acao".
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['acao'])) {

    // Captura de dados usando trim() para remover espaços vazios acidentais
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $cep = trim($_POST['cep'] ?? '');
    $rua = trim($_POST['rua'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $bairro = trim($_POST['bairro'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $estado = trim($_POST['estado'] ?? '');
    $complemento = trim($_POST['complemento'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    // Guarda tudo em sessão
    $_SESSION['dados'] = [
        'nome' => $nome,
        'cpf' => $cpf,
        'email' => $email,
        ':cep' => $cep,
        ':rua' => $rua,
        ':numero' => $numero,
        ':bairro' => $bairro,
        ':cidade' => $cidade,
        ':estado' => $estado,
        ':complemento' => $complemento,
        ':senha' => $senha,
        'telefone' => $telefone,
        'mensagem' => $mensagem,
    ];

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
        $sqlInsert = $pdo->prepare("INSERT INTO " . $tabela . " (Nome, CPF, Email, CEP, Rua, Numero, Bairro, Cidade, Estado, Complemento, Telefone, Senha) VALUES (:nome, :cpf, :email, :cep, :rua, :numero, :bairro, :cidade, :estado, :complemento, :telefone, :senha)");

        $sqlInsert->bindValue(":nome", $nome);
        $sqlInsert->bindValue(":cpf", $cpf);
        $sqlInsert->bindValue(":email", $email);
        $sqlInsert->bindValue(":cep", $cep);
        $sqlInsert->bindValue(":rua", $rua);
        $sqlInsert->bindValue(":numero", $numero);
        $sqlInsert->bindValue(":bairro", $bairro);
        $sqlInsert->bindValue(":cidade", $cidade);
        $sqlInsert->bindValue(":estado", $estado);
        $sqlInsert->bindValue(":complemento", $complemento);
        $sqlInsert->bindValue(":telefone", $telefone);
        $sqlInsert->bindValue(":senha", $senha);

        $sqlInsert->execute();

        // Redireciona em caso de sucesso
        header('Location: login.php');
        exit;

    } catch (Exception $erro) {
        echo "ATENÇÃO, erro na inclusão: " . $erro->getMessage();
        exit;
    }
}


// Exige login para visualizar o perfil
if (empty($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

$id_cliente = (int) ($_SESSION['id_cliente'] ?? 0);

// Carrega os dados do cliente logado.
// Se o id_cliente ainda não estiver na sessão (sessão antiga do login anterior à correção), recupera o cliente pelo e-mail salvo na sessão.
$dadosCliente = null;
try {
    $pdo = conectar();

    if ($id_cliente > 0) {
        $sqlCliente = $pdo->prepare("SELECT Id_Cliente, Nome, CPF, Email, CEP, Rua, Numero, Bairro, Cidade, Estado, Complemento, Telefone FROM Cliente WHERE Id_Cliente = :id");
        $sqlCliente->bindValue(":id", $id_cliente);
        $sqlCliente->execute();
        $dadosCliente = $sqlCliente->fetch(PDO::FETCH_ASSOC);
    }

    // Tenta recuperar pelo e-mail se não achamos pelo id
    if (!$dadosCliente && !empty($_SESSION['email_cliente'])) {
        $sqlCliente = $pdo->prepare("SELECT Id_Cliente, Nome, CPF, Email, CEP, Rua, Numero, Bairro, Cidade, Estado, Complemento, Telefone FROM Cliente WHERE Email = :email");
        $sqlCliente->bindValue(":email", $_SESSION['email_cliente']);
        $sqlCliente->execute();
        $dadosCliente = $sqlCliente->fetch(PDO::FETCH_ASSOC);

        if ($dadosCliente) {
            $_SESSION['id_cliente'] = $dadosCliente['Id_Cliente'];
            $_SESSION['nome_cliente'] = $dadosCliente['Nome'];
        }
    }

    // Último recurso: recupera pelo nome (sessões antigas que só têm o nome)
    if (!$dadosCliente && !empty($_SESSION['nome_cliente'])) {
        $sqlCliente = $pdo->prepare("SELECT TOP 1 Id_Cliente, Nome, CPF, Email, CEP, Rua, Numero, Bairro, Cidade, Estado, Complemento, Telefone FROM Cliente WHERE Nome = :nome");
        $sqlCliente->bindValue(":nome", $_SESSION['nome_cliente']);
        $sqlCliente->execute();
        $dadosCliente = $sqlCliente->fetch(PDO::FETCH_ASSOC);

        if ($dadosCliente) {
            $_SESSION['id_cliente'] = $dadosCliente['Id_Cliente'];
            $_SESSION['email_cliente'] = $dadosCliente['Email'];
        }
    }
} catch (Exception $erro) {
    $dadosCliente = null;
}

// Caso o cliente não exista mais no banco, redireciona
if (!$dadosCliente) {
    header('Location: login.php');
    exit;
}

$id_cliente = (int) $dadosCliente['Id_Cliente'];
$nome = $dadosCliente['Nome'];

// Carrega o histórico somente dos pedidos.
// Os itens ficam separados em $itensPorPedido e são exibidos apenas quando
// o usuário clicar no número do pedido no histórico.
$historico = [];
$itensPorPedido = [];
$totalGasto = 0;

try {
    $sqlHistorico = $pdo->prepare(
        "SELECT
            Id_Pedido,
            Data_Pedido,
            Status_Pedido,
            Valor_Total,
            ROW_NUMBER() OVER (
                ORDER BY Data_Pedido ASC, Id_Pedido ASC
            ) AS Numero_Pedido_Cliente
         FROM Pedido
         WHERE Id_Cliente = :id
         ORDER BY Data_Pedido DESC, Id_Pedido DESC"
    );

    $sqlHistorico->bindValue(":id", $id_cliente);
    $sqlHistorico->execute();
    $historico = $sqlHistorico->fetchAll(PDO::FETCH_ASSOC);

    foreach ($historico as $pedido) {
        if (strcasecmp(trim((string) $pedido['Status_Pedido']), 'Cancelado') !== 0) {
            $totalGasto += (float) $pedido['Valor_Total'];
        }
    }

    // Busca todos os itens pertencentes aos pedidos do cliente logado.
    // O JOIN com Pedido garante que um usuário nunca veja itens de outro cliente.
    $sqlItens = $pdo->prepare(
        "SELECT
            pi.Id_Pedido,
            pr.Nome AS Produto,
            pr.Marca,
            pi.Quantidade,
            pi.Preco_Unitario,
            (pi.Preco_Unitario * pi.Quantidade) AS Subtotal
         FROM Pedido_Item pi
         INNER JOIN Pedido p ON p.Id_Pedido = pi.Id_Pedido
         INNER JOIN Produto pr ON pr.Id_Produto = pi.Id_Produto
         WHERE p.Id_Cliente = :id
         ORDER BY pi.Id_Pedido DESC, pi.Id_Pedido_Item ASC"
    );

    $sqlItens->bindValue(":id", $id_cliente);
    $sqlItens->execute();

    foreach ($sqlItens->fetchAll(PDO::FETCH_ASSOC) as $item) {
        $idPedido = (int) $item['Id_Pedido'];

        if (!isset($itensPorPedido[$idPedido])) {
            $itensPorPedido[$idPedido] = [];
        }

        $itensPorPedido[$idPedido][] = $item;
    }
} catch (Exception $erro) {
    $historico = [];
    $itensPorPedido = [];
}
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SuperClick - Meu Perfil</title>
    <link rel="icon" type="image/x-icon" href="imagens/Ico-Mercearia.ico">
    <link rel="stylesheet" href="styles.css" />

    <style>
        .pedido__toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 0;
            background: transparent;
            color: var(--cor1);
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            padding: 6px 0;
        }

        .pedido__toggle:hover {
            text-decoration: underline;
        }

        .pedido__seta {
            display: inline-block;
            font-size: 11px;
            transition: transform 0.2s ease;
        }

        .pedido__toggle[aria-expanded="true"] .pedido__seta {
            transform: rotate(180deg);
        }

        .pedido__detalhes > td {
            padding: 0 !important;
            background: #f8fafc;
        }

        .pedido__detalhes-conteudo {
            padding: 18px;
            border-top: 1px solid var(--borda);
            border-bottom: 1px solid var(--borda);
        }

        .pedido__detalhes-conteudo h3 {
            margin: 0 0 14px;
            font-size: 16px;
        }

        .pedido__itens-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .pedido__itens-tabela {
            width: 100%;
            border-collapse: collapse;
            min-width: 620px;
        }

        .pedido__itens-tabela th,
        .pedido__itens-tabela td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid var(--borda);
        }

        .pedido__itens-tabela th {
            font-size: 13px;
            color: var(--texto2);
        }

        .pedido__sem-itens {
            margin: 0;
            color: var(--texto2);
        }

        .pedido__status-acoes {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .pedido__cancelar-form {
            margin: 0;
        }

        .pedido__cancelar-btn {
            border: 1px solid #dc2626;
            background: transparent;
            color: #dc2626;
            padding: 6px 10px;
            border-radius: 8px;
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .pedido__cancelar-btn:hover {
            background: #dc2626;
            color: #ffffff;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/header.php'; ?>

    <main class="container">
        <section class="hero">
            <h1 class="hero__titulo">Meu Perfil</h1>
            <p class="hero__subtitulo">Bem-vindo(a), <?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?>!</p>
        </section>

        <?php if (!empty($mensagem_perfil)) { ?>
            <div class="alerta-sucesso"><?php echo htmlspecialchars($mensagem_perfil, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>

        <section class="perfil__grade">

            <!-- Cartão de dados cadastrais -->
            <div class="filtro perfil__card">
                <div class="perfil__cabecalho">
                    <h2 class="filtro__titulo">Meus Dados</h2>
                </div>

                <form class="auth__form" action="dados.php" method="POST">
                    <input type="hidden" name="acao" value="atualizar" />

                    <div class="contato__grid">
                        <div class="contato__campo">
                            <label for="nome">Nome Completo</label>
                            <input type="text" id="nome" name="nome"
                                value="<?php echo htmlspecialchars($dadosCliente['Nome'], ENT_QUOTES, 'UTF-8'); ?>"
                                required />
                        </div>

                        <div class="contato__campo">
                            <label for="cpf">CPF</label>
                            <input type="text" id="cpf" name="cpf" maxlength="14" placeholder="000.000.000-00"
                                value="<?php echo htmlspecialchars($dadosCliente['CPF'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                required />
                        </div>

                        <div class="contato__campo">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email"
                                value="<?php echo htmlspecialchars($dadosCliente['Email'], ENT_QUOTES, 'UTF-8'); ?>"
                                required />
                        </div>

                        <div class="contato__campo">
                            <label for="cep">CEP</label>
                            <input type="text" id="cep" name="cep" maxlength="9" placeholder="00000-000"
                                value="<?php echo htmlspecialchars($dadosCliente['CEP'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>

                        <div class="contato__campo">
                            <label for="cidade">Cidade</label>
                            <input type="text" id="cidade" name="cidade"
                                value="<?php echo htmlspecialchars($dadosCliente['Cidade'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>

                        <div class="contato__campo">
                            <label for="estado">Estado</label>
                            <input type="text" id="estado" name="estado"
                                value="<?php echo htmlspecialchars($dadosCliente['Estado'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>

                        <div class="contato__campo">
                            <label for="bairro">Bairro</label>
                            <input type="text" id="bairro" name="bairro"
                                value="<?php echo htmlspecialchars($dadosCliente['Bairro'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>

                        <div class="contato__campo">
                            <label for="rua">Rua</label>
                            <input type="text" id="rua" name="rua"
                                value="<?php echo htmlspecialchars($dadosCliente['Rua'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>

                        <div class="contato__campo">
                            <label for="numero">Número</label>
                            <input type="text" id="numero" name="numero"
                                value="<?php echo htmlspecialchars($dadosCliente['Numero'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>

                        <div class="contato__campo">
                            <label for="complemento">Complemento</label>
                            <input type="text" id="complemento" name="complemento"
                                value="<?php echo htmlspecialchars($dadosCliente['Complemento'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>

                        <div class="contato__campo">
                            <label for="telefone">Telefone</label>
                            <input type="text" id="telefone" name="telefone" maxlength="15"
                                placeholder="(00) 00000-0000"
                                value="<?php echo htmlspecialchars($dadosCliente['Telefone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>

                        <div class="contato__campo">
                            <label for="senha">Nova Senha <small>(deixe em branco para manter)</small></label>
                            <input type="password" id="senha" name="senha" placeholder="Digite uma nova senha" />
                        </div>
                    </div>

                    <div class="auth__acoes auth__acoes--registro">
                        <button class="botao botao--secundario" type="submit">Salvar Alterações</button>
                    </div>
                </form>
            </div>

            <!-- Cartão de histórico de compras -->
            <div class="filtro perfil__card">
                <div class="perfil__cabecalho">
                    <h2 class="filtro__titulo">Histórico de Compras</h2>
                    <span class="perfil__total">Total gasto: <strong>R$
                            <?php echo number_format($totalGasto, 2, ',', '.'); ?></strong></span>
                </div>

                <?php if (count($historico) > 0) { ?>
                    <div class="perfil__tabela-wrapper">
                        <table class="perfil__tabela">
                            <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Data</th>
                                    <th>Valor Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historico as $pedido) { ?>
                                    <?php
                                    // Id real do banco: usado para localizar Pedido_Item.
                                    $idPedido = (int) $pedido['Id_Pedido'];

                                    // Número exibido ao cliente: começa em 1 para cada cliente.
                                    $numeroPedidoCliente = (int) $pedido['Numero_Pedido_Cliente'];

                                    $itensPedido = $itensPorPedido[$idPedido] ?? [];
                                    $idDetalhes = 'detalhes-pedido-' . $idPedido;
                                    ?>

                                    <tr class="pedido__linha">
                                        <td>
                                            <button
                                                type="button"
                                                class="pedido__toggle"
                                                data-target="<?php echo $idDetalhes; ?>"
                                                aria-expanded="false"
                                                aria-controls="<?php echo $idDetalhes; ?>"
                                                title="Ver produtos do pedido">
                                                <span>#<?php echo $numeroPedidoCliente; ?></span>
                                                <span class="pedido__seta" aria-hidden="true">▼</span>
                                            </button>
                                        </td>
                                        <td>
                                            <?php
                                            $data = $pedido['Data_Pedido'];

                                            if ($data instanceof DateTime) {
                                                echo $data->format('d/m/Y');
                                            } else {
                                                echo date('d/m/Y', strtotime($data));
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            R$ <?php echo number_format((float) $pedido['Valor_Total'], 2, ',', '.'); ?>
                                        </td>
                                        <td>
                                            <div class="pedido__status-acoes">
                                                <span class="perfil__status">
                                                    <?php echo htmlspecialchars($pedido['Status_Pedido'], ENT_QUOTES, 'UTF-8'); ?>
                                                </span>

                                                <?php if (strcasecmp(trim((string) $pedido['Status_Pedido']), 'Iniciado') === 0) { ?>
                                                    <form
                                                        class="pedido__cancelar-form"
                                                        action="dados.php"
                                                        method="POST"
                                                        onsubmit="return confirm('Tem certeza que deseja cancelar este pedido?');">

                                                        <input type="hidden" name="acao" value="cancelar_pedido">
                                                        <input type="hidden" name="id_pedido" value="<?php echo $idPedido; ?>">
                                                        <input
                                                            type="hidden"
                                                            name="csrf_token"
                                                            value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                                                        <button class="pedido__cancelar-btn" type="submit">
                                                            Cancelar pedido
                                                        </button>
                                                    </form>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr id="<?php echo $idDetalhes; ?>" class="pedido__detalhes" hidden>
                                        <td colspan="4">
                                            <div class="pedido__detalhes-conteudo">
                                                <h3>Produtos do pedido #<?php echo $numeroPedidoCliente; ?></h3>

                                                <?php if (count($itensPedido) > 0) { ?>
                                                    <div class="pedido__itens-wrapper">
                                                        <table class="pedido__itens-tabela">
                                                            <thead>
                                                                <tr>
                                                                    <th>Produto</th>
                                                                    <th>Marca</th>
                                                                    <th>Qtd</th>
                                                                    <th>Preço unitário</th>
                                                                    <th>Subtotal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($itensPedido as $item) { ?>
                                                                    <tr>
                                                                        <td><?php echo htmlspecialchars($item['Produto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                                        <td><?php echo htmlspecialchars($item['Marca'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                                        <td><?php echo (int) $item['Quantidade']; ?></td>
                                                                        <td>R$ <?php echo number_format((float) $item['Preco_Unitario'], 2, ',', '.'); ?></td>
                                                                        <td>R$ <?php echo number_format((float) $item['Subtotal'], 2, ',', '.'); ?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php } else { ?>
                                                    <p class="pedido__sem-itens">Nenhum item encontrado para este pedido.</p>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <p class="carrinho__vazio">Você ainda não realizou nenhuma compra.</p>
                <?php } ?>
            </div>

        </section>
    </main>

    <footer class="rodape">
        <div class="rodape__conteudo">
            <div class="rodape__coluna">
                <h2 class="rodape__logo">SuperClick</h2>
                <p class="rodape__texto">Qualidade, economia e praticidade para sua casa, com produtos de mercado e
                    drogaria em um só lugar.</p>
            </div>
            <div class="rodape__coluna">
                <h3 class="rodape__titulo">Localização</h3>
                <p>Rua Exemplo, 123 - Centro</p>
                <p>Segunda a sábado: 08h às 20h</p>
                <p>Domingo: 08h às 13h</p>
            </div>
        </div>
        <div class="rodape__baixo">
            <p>&copy; 2026 SuperClick Supermercado. Todos os direitos reservados.</p>
            <p>Projeto desenvolvido para fins acadêmicos.</p>
        </div>
    </footer>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const botoesPedido = document.querySelectorAll('.pedido__toggle');

            botoesPedido.forEach(function (botao) {
                botao.addEventListener('click', function () {
                    const idDetalhes = botao.getAttribute('data-target');
                    const detalhes = document.getElementById(idDetalhes);

                    if (!detalhes) return;

                    const estaAberto = botao.getAttribute('aria-expanded') === 'true';

                    botao.setAttribute('aria-expanded', estaAberto ? 'false' : 'true');
                    detalhes.hidden = estaAberto;
                });
            });
        });
    </script>

    <script src="script.js"></script>
</body>

</html>