<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mensagem_erro = $_SESSION['registro_erro'] ?? '';
unset($_SESSION['registro_erro']);
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SuperClick - Registro</title>
    <link rel="icon" type="image/x-icon" href="imagens/Ico-Mercearia.ico">
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <header class="cabecalho">
        <div class="container cabecalho__interno">
            <img class="cabecalho__logo" src="imagens/lg.png" alt="" />
            <button class="cabecalho__hamburguer" type="button" id="menu-hamburguer" aria-label="Abrir menu">
                ☰
            </button>

            <nav class="cabecalho__navegacao">
                <a class="cabecalho__link" href="index.php">Mercearia</a>
                <a class="cabecalho__link" href="drogaria.php">Drogaria</a>
                <a class="cabecalho__link" href="sobre.php">Sobre Nós</a>
                <a class="cabecalho__link" href="contato.php">Contato</a>
            </nav>

            <div class="cabecalho__auth">
                <a class="botao botao--primario" href="login.php">Login</a>
                <a class="botao botao--secundario" href="registro.php">Registro</a>
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
        <section id="registro" class="hero">
            <h1 class="hero__titulo">Registro</h1>
            <p class="hero__subtitulo">Crie sua conta</p>
        </section>

        <section class="filtros">
            <div class="filtros__grade">
                <div class="filtro auth__card">
                    <h2 class="filtro__titulo">Informações da conta</h2>

                    <?php
                    if (!empty($mensagem_erro)) {
                        echo "<div class='alerta-erro'>" . $mensagem_erro . "</div>";
                    }

                    echo "<form class='auth__form' action='dados.php' method='POST'>";
                    echo "<div class='contato__grid'>";
                    echo "<div class='contato__campo'>";
                    echo "<label for='nome'>Nome Completo</label>";
                    echo "<input type='text' id='nome' name='nome' placeholder='Digite seu nome' required />";
                    echo "</div>";

                    echo "<div class='contato__campo'>";
                    echo "<label for='cpf'>CPF</label>";
                    echo "<input type='number' id='cpf' name='cpf' placeholder='Digite seu CPF' required />";
                    echo "</div>";

                    echo "<div class='contato__campo'>";
                    echo "<label for='email'>Email</label>";
                    echo "<input type='email' id='email' name='email' placeholder='Digite seu e-mail' required />";
                    echo "</div>";

                    echo "<div class='contato__campo'>";
                    echo "<label for='endereco'>Endereço</label>";
                    echo "<input type='text' id='endereco' name='endereco' placeholder='Digite seu endereço' required />";
                    echo "</div>";

                    echo "<div class='contato__campo'>";
                    echo "<label for='telefone'>Telefone</label>";
                    echo "<input type='number' id='telefone' name='telefone' placeholder='Digite seu telefone' required />";
                    echo "</div>";

                    echo "<div class='contato__campo'>";
                    echo "<label for='senha'>Senha</label>";
                    echo "<input type='password' id='senha' name='senha' placeholder='Crie uma senha' required />";
                    echo "</div>";

                    echo "<div class='contato__campo'>";
                    echo "<label for='confirmar'>Confirmar Senha</label>";
                    echo "<input type='password' id='confirmar' name='confirmar' placeholder='Repita sua senha'
                                    required />";
                    echo "</div>";

                    echo "<div class='auth__acoes auth__acoes--registro'>";
                    echo "<button class='botao botao--secundario' type='submit' onclick='VerificaSenha()'>Criar Conta</button>";
                    echo "</div>";

                    echo "<div class='auth__registro'>";
                    echo "<span>Já tem conta?</span>";
                    echo "<a href='login.php'>Entrar</a>";
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