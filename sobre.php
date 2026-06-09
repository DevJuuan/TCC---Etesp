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
    <title>SuperClick - Sobre Nós</title>
    <link rel="icon" type="image/x-icon" href="imagens/Ico-Mercearia.ico">
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <?php include __DIR__ . '/header.php'; ?>

    <main class="container">
        <section id="sobre" class="hero">
            <h1 class="hero__titulo">Sobre o SuperClick</h1>
            <p class="hero__subtitulo">Entenda como o SuperClick nasceu e o que nos guia.</p>
        </section>

        <section class="filtros">
            <div class="filtros__grade">
                <div class="filtro sobre__conteudo">
                    <h2 class="filtro__titulo sobre__texto__compacto">Nosso propósito</h2>

                    <p class="sobre__texto">
                        O SuperClick nasceu com o objetivo de oferecer aos clientes uma experiência de compra prática,
                        agradável e confiável.
                        Trabalhamos para reunir em um só lugar produtos de qualidade, preços acessíveis e um atendimento
                        dedicado às necessidades de cada consumidor.
                    </p>

                    <p class="sobre__texto">
                        Nosso compromisso é facilitar o dia a dia das famílias, oferecendo variedade em alimentos,
                        bebidas, produtos de limpeza,
                        higiene e itens essenciais para o lar. Buscamos sempre manter um ambiente organizado, seguro e
                        acolhedor, tanto em nossa loja quanto em nosso site.
                    </p>

                    <h2 class="filtro__titulo sobre__texto__compacto">Nossos valores</h2>

                    <p class="sobre__texto">
                        Valorizamos a <b>qualidade</b>, oferecendo produtos selecionados para nossos clientes. Prezamos
                        pela <b>confiança</b>, mantendo transparência nos preços e no atendimento. Acreditamos no
                        <b>respeito</b>, tratando cada cliente com atenção e educação.
                        Também buscamos a <b>praticidade</b>, tornando as compras mais rápidas e simples. Além disso,
                        temos compromisso com a <b>responsabilidade</b>, trabalhando de forma ética e pensando no
                        bem-estar da comunidade.
                    </p>

                    <p class="sobre__conclusao">
                        No SuperClick, cada compra é feita com cuidado, compromisso e respeito por você.
                    </p>

                    <div id="contato" style="margin-top: 20px;">
                        <p class="sobre__contato">
                            Fale com a gente para tirar dúvidas, sugerir melhorias ou conhecer mais sobre o nosso
                            mercado.
                        </p>
                    </div>
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