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
  <title>SuperClick - Home</title>
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

    <section class="promo-anuncio">
      <div class="overlay"></div>
      <div class="promo-conteudo">
        <span class="anuncio">OFERTAS DA SEMANA</span>
        <h1>Até <span>50% OFF</span></h1>
        <p>Economize mais todos os dias com produtos selecionados para você e sua família.</p>
      </div>

      <div class="floating">
        <img src="imagens/cocacola.png" class="cocacola">
        <img src="imagens/Mercearia/banana-prata.png" class="banana">
        <img src="imagens/Repelente-Sbp.png" class="repelente">
        <img src="imagens/dipirona.png" class="dipirona">
      </div>
    </section>

    <section class="vitrine">

      <h1>Ofertas da Semana</h1>
      <hr>
      <p class="subtitulo">Mercearia</p>

      <button class="btn voltar">&#10094;</button>
      <div class="produtos">
        <article class="produto" data-categoria="Mercearia">
          <div class="produto__top">
            <span class="etiqueta">Mercearia</span>
            <div class="produto__marca">Camil</div>
          </div>
          <img class="produto__imagem" src="imagens/Mercearia/arroz-camil.jpg" alt="Arroz camil" />
          <h3 class="produto__nome">Arroz Tipo 1 5kg</h3>
          <p class="produto__descricao">Grãos selecionados, ideal para o dia a dia</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco desconto"><s>R$ 20,99</s> R$ 15,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Mercearia">
          <div class="produto__top">
            <span class="etiqueta">Mercearia</span>
            <div class="produto__marca">União</div>
          </div>
          <img class="produto__imagem" src="imagens/Mercearia/acucar-uniao.jpg" alt="Açucar União" />
          <h3 class="produto__nome">Açúcar Refinado 1kg</h3>
          <p class="produto__descricao">Versátil para receitas e bebidas</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco desconto"><s>R$ 3,79</s> R$3,19</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Mercearia">
          <div class="produto__top">
            <span class="etiqueta">Mercearia</span>
            <div class="produto__marca">Liza</div>
          </div>
          <img class="produto__imagem" src="imagens/Mercearia/oleo-liza.jpg" alt="Óleo de Soja Liza" />
          <h3 class="produto__nome">Óleo de Soja 900ml</h3>
          <p class="produto__descricao">
            Leve para frituras e preparos diários
          </p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco desconto"><s>R$ 6,99</s> R$ 5,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Mercearia">
          <div class="produto__top">
            <span class="etiqueta">Mercearia</span>
            <div class="produto__marca">Dona Benta</div>
          </div>
          <img class="produto__imagem" src="imagens/Mercearia/Farinha-Dona-Benta.webp"
            alt="Farinha de Trigo Dona Benta" />
          <h3 class="produto__nome">Farinha de Trigo 1kg</h3>
          <p class="produto__descricao">Base ideal para massas fofinhas</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco"><s>R$ 5,29</s> R$ 4,79</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>
      </div>
      <button class="btn avancar">&#10095;</button>
    </section>

    <section class="vitrine">

      <p class="subtitulo">Bebidas</p>

      <button class="btn voltar">&#10094;</button>
      <div class="produtos">
        <article class="produto" data-categoria="Bebidas">
          <div class="produto__top">
            <span class="etiqueta">Bebidas</span>
            <div class="produto__marca">Coca-Cola</div>
          </div>
          <img class="produto__imagem" src="imagens/Mercearia/Coca-Cola-2l.webp" alt="Coca-Cola 2l" />
          <h3 class="produto__nome">Refrigerante Cola 2L</h3>
          <p class="produto__descricao">Gelado, clássico e refrescante</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco"><s>R$ 9,99</s> R$ 7,79</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Bebidas">
          <div class="produto__top">
            <span class="etiqueta">Bebidas</span>
            <div class="produto__marca">Guaraná Antarctica</div>
          </div>
          <img class="produto__imagem" src="imagens/Mercearia/Guarana-Antartica-2l.webp" alt="Guarana Antarctica 2l" />
          <h3 class="produto__nome">Refrigerante Guaraná 2L</h3>
          <p class="produto__descricao">Doce, leve e brasileiro</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco"><s>R$ 7,99</s> R$ 5,79</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>
      </div>
      <button class="btn avancar">&#10095;</button>
    </section>

    <section class="vitrine">

      <p class="subtitulo">Hortifruti</p>

      <button class="btn voltar">&#10094;</button>
      <div class="produtos">
        <article class="produto" data-categoria="Hortifruti">
          <div class="produto__top">
            <span class="etiqueta">Hortifruti</span>
            <div class="produto__marca">Frutas</div>
          </div>
          <img class="produto__imagem" src="imagens/Mercearia/banana-prata.png" alt="Banana Prata" />
          <h3 class="produto__nome">Banana Prata kg</h3>
          <p class="produto__descricao">Maduras na medida certa</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco"><s>R$ 7,49</s> R$ 5,49</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Hortifruti">
          <div class="produto__top">
            <span class="etiqueta">Hortifruti</span>
            <div class="produto__marca">Frutas</div>
          </div>
          <img class="produto__imagem" src="imagens/Mercearia/maca-fuji.webp" alt="Maçã Fuji" />
          <h3 class="produto__nome">Maçã Fuji kg</h3>
          <p class="produto__descricao">Vermelha, crocante e adocicada</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco"><s>R$ 10,99</s> R$ 8,69</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Hortifruti">
          <div class="produto__top">
            <span class="etiqueta">Hortifruti</span>
            <div class="produto__marca">Frutas</div>
          </div>
          <img class="produto__imagem" src="imagens/Mercearia/Tomate-italiano.webp" alt="Tomate Italiano" />
          <h3 class="produto__nome">Tomate Italiano kg</h3>
          <p class="produto__descricao">Vermelho, firme e suculento</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco"><s>R$ 9,49</s> R$ 8,79</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>

        <article class="produto" data-categoria="Hortifruti">
          <div class="produto__top">
            <span class="etiqueta">Hortifruti</span>
            <div class="produto__marca">Legumes</div>
          </div>
          <img class="produto__imagem" src="imagens/Mercearia/Cenoura.jpg" alt="Cenoura" />
          <h3 class="produto__nome">Cenoura kg</h3>
          <p class="produto__descricao">Colorida, fresca e nutritiva</p>
          <div class="produto__linha-preco">
            <div>
              <div class="produto__preco"><s>R$ 5,99</s> R$ 4,99</div>
            </div>
            <button class="produto__btn" type="button">
              Adicionar ao Carrinho
            </button>
          </div>
        </article>
      </div>
      <button class="btn avancar">&#10095;</button>
    </section>

    <section class="promo-anuncio2"></section>

      <section class="vitrine">

        <p class="subtitulo">Drogaria</p>

        <button class="btn voltar">&#10094;</button>
        <div class="produtos">
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
        </div>
        <button class="btn avancar">&#10095;</button>
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