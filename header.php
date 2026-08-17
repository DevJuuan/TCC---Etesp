<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$logado = !empty($_SESSION['logado']);
$nome = $_SESSION['nome_cliente'] ?? '';
$nome = mb_convert_case($nome, MB_CASE_TITLE, 'UTF-8');

$botaoLogin = "<a class='botao botao--primario' href='login.php'>Entrar</a>";
$botaoRegistro = "<a class='botao botao--secundario' href='registro.php'>Registrar</a>";

$botaoUser = '';
if ($logado) {
  $botaoLogin = '';
  $botaoRegistro = '';
  $nomeEscapado = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
  $botaoUser = '<a class="botao" href="dados.php"  style=text-decoration:none;>' . $nomeEscapado . '</a>';

}

?>
<script>window.usuarioLogado = <?php echo $logado ? 'true' : 'false'; ?>;</script>


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
      <?php echo $botaoLogin; ?>
      <?php echo $botaoRegistro; ?>
      <?php echo $botaoUser; ?>
      <?php if ($logado) { ?>
        <a class="botao--sair" href="logout.php" style="text-decoration:none;" onclick="limparCarrinho();">Sair</a>

      <?php } ?>
      <button class="botao" type="button" id="carrinho__abrir" aria-label="Abrir carrinho">🛒</button>
    </div>

    <!-- Menu mobile -->
    <div class="menu-mobile__overlay" id="menu-mobile__overlay" aria-hidden="true">
      <aside class="menu-mobile__painel" role="dialog" aria-modal="true" aria-label="Menu">
        <div class="menu-mobile__cabecalho">
          <h2 class="menu-mobile__titulo">Menu</h2>
          <button type="button" class="menu-mobile__botao-fechar" id="menu-mobile__fechar" aria-label="Fechar menu">
            ✕
          </button>
        </div>

        <nav class="menu-mobile__links" aria-label="Links do menu">
          <?php if ($logado) { ?>
            <a class="menu-mobile__link" href="dados.php">
              <?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?></a>
          <?php } else { ?>
            <a class="menu-mobile__link" href="login.php">Entrar</a>
            <a class="menu-mobile__link" href="registro.php">Registrar</a>
          <?php } ?>

          <a class="menu-mobile__link" href="mercearia.php">Mercearia</a>
          <a class="menu-mobile__link" href="drogaria.php">Drogaria</a>
          <a class="menu-mobile__link" href="contato.php">Contato</a>
          <a class="menu-mobile__link" href="sobre.php">Sobre Nós</a>


        </nav>
      </aside>
    </div>

  </div>
</header>