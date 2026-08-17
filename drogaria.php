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
  <title>SuperClick - Drogaria</title>
  <link rel="icon" type="image/x-icon" href="imagens/Ico-Mercearia.ico">
  <link rel="stylesheet" href="styles.css" />
</head>

<body>
  <?php include __DIR__ . '/header.php'; ?>

  <main class="container">
    <!-- Carrinho -->
    <div class="carrinho__overlay" id="janela-carrinho" aria-hidden="true">
      <div class="carrinho__painel" role="dialog" aria-modal="true" aria-label="Seu carrinho">
        <div class="carrinho__cabecalho">
          <h2 class="carrinho__titulo">Seu Carrinho</h2>
          <button type="button" class="carrinho__botao-fechar" id="carrinho__fechar"
            aria-label="Fechar carrinho">✕</button>
        </div>

        <div class="carrinho__corpo">
          <p class="carrinho__vazio" id="carrinho__vazio">Seu carrinho está vazio.</p>
          <ul class="carrinho__lista" id="lista-carrinho"></ul>
        </div>

        <div class="carrinho__rodape">
          <div class="carrinho__total">
            <span>Total</span>
            <strong id="valor-total">R$ 0,00</strong>
          </div>
          <div class="carrinho__acoes">
            <button type="button" class="carrinho__btn" onclick="limparCarrinho()">Limpar</button>
            <button type="button" class="carrinho__btn carrinho__btn--primario"
              onclick="finalizarCompra()">Finalizar</button>
          </div>
        </div>
      </div>
    </div>

    <section id="ofertas" class="hero">
      <img class="banner" src="imagens/Drogaria/banner.png" />
    </section>

    <div class="cabecalho__busca" role="search">
      <form class="cabecalho__busca-form" id="form-busca">
        <label class="cabecalho__busca-label" for="cabecalho-busca">Buscar produto</label>
        <input id="cabecalho-busca" class="cabecalho__busca-input" type="search"
          placeholder="Buscar por nome ou marca..." autocomplete="off" />
      </form>
    </div>

    <section class="filtros">
      <div class="filtros__grade">
        <div class="filtro">
          <h2 class="filtro__titulo">Categorias</h2>

          <div class="categorias" role="navigation">
            <a class="categoria__pill" href="#" data-filtro="Todas">Todas</a>
            <a class="categoria__pill" href="#" data-filtro="Medicamentos">Medicamentos</a>
            <a class="categoria__pill" href="#" data-filtro="Higiene Pessoal">Higiene Pessoal</a>
            <a class="categoria__pill" href="#" data-filtro="Cuidados Bucais">Cuidados Bucais</a>
            <a class="categoria__pill" href="#" data-filtro="Primeiros Socorros">Primeiros Socorros</a>
            <a class="categoria__pill" href="#" data-filtro="Acessórios de Saúde">Acessórios de Saúde</a>
            <a class="categoria__pill" href="#" data-filtro="Proteção e Cuidados">Proteção e Cuidados</a>
            <a class="categoria__pill" href="#" data-filtro="Cabelos">Cabelos</a>
          </div>
        </div>
      </div>
    </section>

    <section class="resultados">
      <div class="lista__produtos" id="lista_produtos">
        <article class="produto" data-categoria="Acessórios de Saúde">
          <div class="produto__top">
            <span class="etiqueta">Acessórios de Saúde</span>
            <div class="produto__marca">G-Tech</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/termometro.webp" alt="Termômetro Digital" />
          <h3 class="produto__nome">Termômetro Digital</h3>
          <p class="produto__descricao">
            Medição simples para cuidados em casa
          </p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco">R$ 24,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Cuidados Bucais">
          <div class="produto__top">
            <span class="etiqueta">Cuidados Bucais</span>
            <div class="produto__marca">Colgate</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/Creme-Dental-Colgate.webp" alt="Creme Dental Colgate" />
          <h3 class="produto__nome">Creme Dental 90g</h3>
          <p class="produto__descricao">Hálito fresco e dentes limpos</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco">R$ 4,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Higiene Pessoal">
          <div class="produto__top">
            <span class="etiqueta">Higiene Pessoal</span>
            <div class="produto__marca">Dove</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/Sabonete-Dove.jpg" alt="Sabonete Dove" />
          <h3 class="produto__nome">Sabonete Dove 90g</h3>
          <p class="produto__descricao">Limpeza suave com toque hidratante</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco">R$ 4,49</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Cabelos">
          <div class="produto__top">
            <span class="etiqueta">Cabelos</span>
            <div class="produto__marca">Pantene</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/Condicionador-Pantene.webp" alt="Condicionador Pantene" />
          <h3 class="produto__nome">Condicionador 400ml</h3>
          <p class="produto__descricao">Fios macios e fáceis de pentear</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco">R$ 18,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Medicamentos">
          <div class="produto__top">
            <span class="etiqueta">Medicamentos</span>
            <div class="produto__marca">Prati</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/dipirona.webp" alt="Dipirona Monoidratada" />
          <h3 class="produto__nome">Dipirona Monoidratada 1g</h3>
          <p class="produto__descricao">Analgésico (para dor) e antitérmico (para febre)</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco"><s>R$ 19,70</s> R$ 15,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Primeiros Socorros">
          <div class="produto__top">
            <span class="etiqueta">Primeiros Socorros</span>
            <div class="produto__marca">Farmax</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/Soro-Farmax.jpg" alt="Soro Fisiológico Farmax" />
          <h3 class="produto__nome">Soro Fisiológico 500ml</h3>
          <p class="produto__descricao">
            Ideal para limpeza e higiene diária
          </p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco">R$ 8,49</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Proteção e Cuidados">
          <div class="produto__top">
            <span class="etiqueta">Proteção e Cuidados</span>
            <div class="produto__marca">Neutrogena</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/Protetor-solar-neutrogena.webp"
            alt="Protetor Solar Neutrogena" />
          <h3 class="produto__nome">Protetor Solar FPS 50 120ml</h3>
          <p class="produto__descricao">
            Cuidado diário contra os raios solares
          </p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco">R$ 39,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Higiene Pessoal">
          <div class="produto__top">
            <span class="etiqueta">Higiene Pessoal</span>
            <div class="produto__marca">Rexona</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/Desodorante-Rexona.jpg" alt="Desodorante Rexona" />
          <h3 class="produto__nome">Desodorante Aerosol 150ml</h3>
          <p class="produto__descricao">Proteção para a rotina do dia</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco"><s>R$ 13,49</s> R$ 10,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Primeiros Socorros">
          <div class="produto__top">
            <span class="etiqueta">Primeiros Socorros</span>
            <div class="produto__marca">Band-Aid</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/Band-aid.webp" alt="Curativo Band-Aid" />
          <h3 class="produto__nome">Curativo 40 Unidades</h3>
          <p class="produto__descricao">
            Proteção prática para pequenos machucados
          </p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco">R$ 16,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        
        <article class="produto" data-categoria="Medicamentos">
          <div class="produto__top">
            <span class="etiqueta">Medicamentos</span>
            <div class="produto__marca">Cimegripe</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/cimegripe.png" alt="Antigripal Cimegripe" />
          <h3 class="produto__nome">Antigripal 10 Cápsulas</h3>
          <p class="produto__descricao">Para gripes e resfriados</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco">R$ 8,90</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Cabelos">
          <div class="produto__top">
            <span class="etiqueta">Cabelos</span>
            <div class="produto__marca">Seda</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/Creme-Pentear-Seda.webp" alt="Creme de Pentear Seda" />
          <h3 class="produto__nome">Creme de Pentear 300ml</h3>
          <p class="produto__descricao">Ajuda a desembaraçar e modelar</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco"><s>R$ 10,99</s> R$ 8,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Acessórios de Saúde">
          <div class="produto__top">
            <span class="etiqueta">Acessórios de Saúde</span>
            <div class="produto__marca">Ever Care</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/Mascara-Descartavel.png" alt="Máscara Descartável" />
          <h3 class="produto__nome">Máscara Descartável 10 Unidades</h3>
          <p class="produto__descricao">Uso prático para proteção diária</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco">R$ 11,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Cuidados Bucais">
          <div class="produto__top">
            <span class="etiqueta">Cuidados Bucais</span>
            <div class="produto__marca">Listerine</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/Antisseptico-bucal-listerine.webp"
            alt="Enxaguante Bucal Listerine" />
          <h3 class="produto__nome">Enxaguante Bucal 250ml</h3>
          <p class="produto__descricao">
            Sensação refrescante após a escovação
          </p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco">R$ 14,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Proteção e Cuidados">
          <div class="produto__top">
            <span class="etiqueta">Proteção e Cuidados</span>
            <div class="produto__marca">Asseptgel</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/alcool-gel-asseptgel.webp" alt="Álcool em Gel Asseptgel" />
          <h3 class="produto__nome">Álcool em Gel 70% 500ml</h3>
          <p class="produto__descricao">Higienização rápida para as mãos</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco">R$ 9,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Primeiros Socorros">
          <div class="produto__top">
            <span class="etiqueta">Primeiros Socorros</span>
            <div class="produto__marca">Apolo</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/algodao-apolo.webp" alt="Algodão Needs" />
          <h3 class="produto__nome">Algodão Hidrófilo 50g</h3>
          <p class="produto__descricao">
            Macio para higiene e cuidados diários
          </p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco">R$ 5,49</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Cabelos">
          <div class="produto__top">
            <span class="etiqueta">Cabelos</span>
            <div class="produto__marca">Salon Line</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/Shampoo-salon-line.png" alt="Shampoo Salon Line" />
          <h3 class="produto__nome">Shampoo 300ml</h3>
          <p class="produto__descricao">Cuidado especial para os cabelos</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco"><s>R$ 12,99</s> R$ 9,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Higiene Pessoal">
          <div class="produto__top">
            <span class="etiqueta">Higiene Pessoal</span>
            <div class="produto__marca">Nivea</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/Locao-Hidratante-Corporal-Nivea.webp"
            alt="Hidratante Nivea" />
          <h3 class="produto__nome">Creme Hidratante 200ml</h3>
          <p class="produto__descricao">Pele macia e bem cuidada</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco">R$ 16,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Cuidados Bucais">
          <div class="produto__top">
            <span class="etiqueta">Cuidados Bucais</span>
            <div class="produto__marca">Oral-B</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/escova-dental-oralb.webp" alt="Escova Dental Oral-B" />
          <h3 class="produto__nome">Escova Dental Macia</h3>
          <p class="produto__descricao">Limpeza confortável para os dentes</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco">R$ 7,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Proteção e Cuidados">
          <div class="produto__top">
            <span class="etiqueta">Proteção e Cuidados</span>
            <div class="produto__marca">SBP</div>
          </div>
          <img class="produto__imagem" src="imagens/Drogaria/Repelente-Sbp.webp" alt="Repelente SBP" />
          <h3 class="produto__nome">Repelente Spray 100ml</h3>
          <p class="produto__descricao">Ajuda na proteção contra insetos</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco">R$ 18,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>
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