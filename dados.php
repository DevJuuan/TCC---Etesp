<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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

// Recebe os dados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') !== 'atualizar') {

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

// Carrega o histórico de compras (pedidos + itens + produtos)
$historico = [];
$totalGasto = 0;
try {
    $sqlHistorico = $pdo->prepare(
        "SELECT p.Id_Pedido, p.Data_Pedido, p.Status_Pedido,
                pr.Nome AS Produto, pr.Preco, pi.Quantidade,
                (pr.Preco * pi.Quantidade) AS Subtotal
         FROM Pedido p
         JOIN Pedido_Item pi ON p.Id_Pedido = pi.Id_Pedido
         JOIN Produto pr ON pi.Id_Produto = pr.Id_Produto
         WHERE p.Id_Cliente = :id
         ORDER BY p.Data_Pedido DESC, p.Id_Pedido DESC"
    );
    $sqlHistorico->bindValue(":id", $id_cliente);
    $sqlHistorico->execute();
    $historico = $sqlHistorico->fetchAll(PDO::FETCH_ASSOC);

    foreach ($historico as $item) {
        $totalGasto += (float) $item['Subtotal'];
    }
} catch (Exception $erro) {
    $historico = [];
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
                                    <th>Produto</th>
                                    <th>Qtd</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historico as $item) { ?>
                                    <tr>
                                        <td>#<?php echo (int) $item['Id_Pedido']; ?></td>
                                        <td><?php
                                        $data = $item['Data_Pedido'];
                                        if ($data instanceof DateTime) {
                                            echo $data->format('d/m/Y');
                                        } else {
                                            echo date('d/m/Y', strtotime($data));
                                        }
                                        ?></td>
                                        <td><?php echo htmlspecialchars($item['Produto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo (int) $item['Quantidade']; ?></td>
                                        <td>R$ <?php echo number_format((float) $item['Subtotal'], 2, ',', '.'); ?></td>
                                        <td><span
                                                class="perfil__status"><?php echo htmlspecialchars($item['Status_Pedido'], ENT_QUOTES, 'UTF-8'); ?></span>
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

    <script src="script.js"></script>
</body>

</html>