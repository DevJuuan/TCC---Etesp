<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SuperClick - Contato</title>
    <link rel="icon" type="image/x-icon" href="imagens/Ico-Mercearia.ico">
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <?php include __DIR__ . '/header.php'; ?>


    <main class="container">
        <section id="contato" class="hero">
            <h1 class="hero__titulo">Fale com a gente</h1>
            <p class="hero__subtitulo">Envie sua mensagem para a equipe do SuperClick.</p>
        </section>

        <section class="filtros">
            <div class="filtros__grade">
                <div class="filtro contato__card">
                    <h2 class="filtro__titulo contato__titulo">Formulário de Contato</h2>

                    <?php
                    echo "<form class='contato__form' action='dados_contato.php' method='post'>";
                    echo "<div class='contato__grid'>";
                    echo "<div class='contato__campo'>";
                    echo "<label for='nome'>Nome</label>";
                    echo "<input id='nome' name='nome' type='text' required />";
                    echo "</div>";

                    echo "<div class='contato__campo'>";
                    echo "<label for='email'>Email</label>";
                    echo "<input id='email' name='email' type='email' required />";
                    echo "</div>";

                    echo "<div class='contato__campo'>";
                    echo "<label for='mensagem'>Mensagem</label>";
                    echo "<textarea id='mensagem' name='mensagem' rows='5'  maxlength='700' required></textarea>";
                    echo "</div>";

                    echo "<div class='contato__acoes'>";
                    echo "<button class='botao botao--secundario' type='submit'>Enviar Mensagem</button>";
                    echo "</div>";
                    echo "</div>";
                    echo "</form>";
                    ?>
                </div>
            </div>
        </section>
    </main>

    <footer class="rodape">
        <div class="rodape__conteudo">

            <div class="rodape__coluna">
                <h2 class="rodape__logo">SuperClick</h2>
                <p class="rodape__texto">
                    Qualidade, economia e praticidade para sua casa, com produtos de mercado e drogaria em um só lugar.
                </p>
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