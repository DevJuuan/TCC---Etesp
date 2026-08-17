<?php
session_start();

$mensagem_erro = "";

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {

    $email_digitado = trim($_POST['email']);
    $senha_digitada = trim($_POST['password']);

    // Função de conexão 
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
            die("Erro de conexão: " . $erro->getMessage());
        }
    }

    try {
        $pdo = conectar();

        // Busca no banco de dados pelo e-mail
        $sql = $pdo->prepare("SELECT * FROM cliente WHERE email = :email");
        $sql->bindValue(":email", $email_digitado);
        $sql->execute();

        // Pega o resultado se existir
        $usuario = $sql->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário existe e se a senha bate com a do banco
        if ($usuario && $senha_digitada === $usuario['Senha']) {

            // Login com sucesso
            $_SESSION['logado'] = true;
            $_SESSION['id_cliente'] = $usuario['Id_Cliente'];
            $_SESSION['nome_cliente'] = $usuario['Nome'];
            $_SESSION['email_cliente'] = $usuario['Email'];

            header("Location: index.php");
            exit;

        } else {
            $mensagem_erro = "E-mail ou senha incorretos.";
        }

    } catch (PDOException $erro) {
        $mensagem_erro = "Erro no banco de dados: " . $erro->getMessage();
    }
}
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SuperClick - Login</title>
    <link rel="icon" type="image/x-icon" href="imagens/Ico-Mercearia.ico">
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <header class="cabecalho">
        <div class="container cabecalho__interno">
            <a href="index.php"><img class="cabecalho__logo" src="imagens/lg.png" alt="" /></a>
            <button class="cabecalho__hamburguer" type="button" id="menu-hamburguer" aria-label="Abrir menu">
                ☰
            </button>

            <nav class="cabecalho__navegacao">
                <a class="cabecalho__link" href="mercearia.php">Mercearia</a>
                <a class="cabecalho__link" href="drogaria.php">Drogaria</a>
                <a class="cabecalho__link" href="sobre.php">Sobre Nós</a>
                <a class="cabecalho__link" href="contato.php">Contato</a>
            </nav>

            <div class="cabecalho__auth">
                <a class="botao botao--primario" href="login.php">Entrar</a>
                <a class="botao botao--secundario" href="registro.php">Registrar</a>
                <button class="botao" type="button" id="carrinho__abrir" aria-label="Abrir carrinho">🛒</button>
            </div>
            <!-- Menu mobile -->
            <div class="menu-mobile__overlay" id="menu-mobile__overlay" aria-hidden="true">
                <aside class="menu-mobile__painel" role="dialog" aria-modal="true" aria-label="Menu">
                    <div class="menu-mobile__cabecalho">
                        <h2 class="menu-mobile__titulo">Menu</h2>
                        <button type="button" class="menu-mobile__botao-fechar" id="menu-mobile__fechar"
                            aria-label="Fechar menu">
                            ✕
                        </button>
                    </div>

                    <nav class="menu-mobile__links" aria-label="Links do menu">
                        <a class="menu-mobile__link" href="login.php">Login</a>
                        <a class="menu-mobile__link" href="registro.php">Registro</a>
                        <a class="menu-mobile__link" href="index.php">Mercearia</a>
                        <a class="menu-mobile__link" href="drogaria.php">Drogaria</a>
                        <a class="menu-mobile__link" href="contato.php">Contato</a>
                        <a class="menu-mobile__link" href="sobre.php">Sobre Nós</a>
                        <button type="button" class="menu-mobile__link menu-mobile__link--botao"
                            id="menu-mobile__carrinho">
                            Carrinho 🛒
                        </button>
                    </nav>
                </aside>
            </div>

        </div>
    </header>

    <main class="container">
        <section id="login" class="hero">
            <h1 class="hero__titulo">Login</h1>
            <p class="hero__subtitulo">Entre para continuar</p>
        </section>

        <section class="filtros">
            <div class="filtros__grade">
                <div class="filtro auth__card">
                    <h2 class="filtro__titulo">Acesse sua conta</h2>

                    <?php
                    // Exibe a mensagem de erro se o login falhar
                    if (!empty($mensagem_erro)) {
                        echo "<div class='alerta-erro'>" . $mensagem_erro . "</div>";
                    }

                    echo "<form class='auth__form' action='login.php' method='POST'>";
                    echo "<div class='contato__grid'>";
                    echo "<div class='contato__campo'>";
                    echo "<label for='email'>Email</label>";
                    echo "<input id='email' name='email' type='email' placeholder='Digite seu e-mail' required />";
                    echo "</div>";

                    echo "<div class='contato__campo'>";
                    echo "<label for='password'>Senha</label>";
                    echo "<input id='password' name='password' type='password' placeholder='Digite sua senha' required />";
                    echo "</div>";

                    echo "<div class='auth__acoes auth__acoes--registro'>";
                    echo "<button class='botao botao--secundario' type='submit'>Entrar</button>";
                    echo "</div>";

                    echo "<div class='auth__registro'>";
                    echo "<span>Não tem conta?</span>";
                    echo "<a href='registro.php'>Criar Conta</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</form>";
                    ?>
                </div>
            </div>
        </section>
    </main>

    <script src="script.js"></script>
</body>

</html>