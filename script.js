<<<<<<< HEAD
//Filtro de busca e categorias

document.addEventListener("DOMContentLoaded", function () {
  const botoes = document.querySelectorAll(".categorias a");
  const cards = document.querySelectorAll(".produto[data-categoria]");

  const inputBusca = document.getElementById("cabecalho-busca");
  const formBusca = document.getElementById("form-busca");
  let filtroCategoriaAtual = "Todas";

  function normalizar(texto) {
    return (texto || "").toString().trim().toLowerCase();
  }

  function setCategoriaAtiva(botaoAtivo) {
    botoes.forEach((b) => b.classList.remove("categoria__pill--ativo"));
    if (botaoAtivo) botaoAtivo.classList.add("categoria__pill--ativo");
  }

  function aplicarFiltros() {
    const termo = normalizar(inputBusca?.value);

    cards.forEach((card) => {
      const categoria = card.getAttribute("data-categoria");
      const nome = normalizar(card.querySelector(".produto__nome")?.innerText);
      const marca = normalizar(
        card.querySelector(".produto__marca")?.innerText,
      );

      const atendeCategoria =
        filtroCategoriaAtual === "Todas" || filtroCategoriaAtual === categoria;
      const atendeBusca =
        termo.length === 0 || nome.includes(termo) || marca.includes(termo);

      card.style.display = atendeCategoria && atendeBusca ? "flex" : "none";
    });
  }

  botoes.forEach((botao) => {
    botao.addEventListener("click", function (e) {
      e.preventDefault();

      const filtro = this.getAttribute("data-filtro");
      filtroCategoriaAtual = filtro;
      setCategoriaAtiva(this);

      aplicarFiltros();
    });
  });

  if (inputBusca && formBusca) {
    formBusca.addEventListener("submit", (e) => {
      e.preventDefault();
      aplicarFiltros();
    });

    inputBusca.addEventListener("input", () => {
      aplicarFiltros();
    });
  }

  const todos = document.querySelector('.categorias a[data-filtro="Todas"]');
  if (todos) {
    filtroCategoriaAtual = "Todas";
    todos.click();
  }
});

// Carrinho
let carrinho = [];

function carregarCarrinho() {
  let salvo = localStorage.getItem("meu_carrinho");
  if (salvo) {
    try {
      carrinho = JSON.parse(salvo);
    } catch (e) {
      carrinho = [];
    }
  }
}

function salvarCarrinho() {
  localStorage.setItem("meu_carrinho", JSON.stringify(carrinho));
}

function formatarDinheiro(valor) {
  return "R$ " + valor.toFixed(2).replace(".", ",");
}

function abrirCarrinho() {
  const overlay = document.getElementById("janela-carrinho");
  overlay.style.display = "flex";
  overlay.setAttribute("aria-hidden", "false");
  overlay.classList.add("carrinho__overlay--aberto");
  desenharCarrinho();
}

function fecharCarrinho() {
  const overlay = document.getElementById("janela-carrinho");
  if (!overlay) return;
  overlay.style.display = "none";
  overlay.setAttribute("aria-hidden", "true");
  overlay.classList.remove("carrinho__overlay--aberto");
}

function desenharCarrinho() {
  let lista = document.getElementById("lista-carrinho");
  let textoTotal = document.getElementById("valor-total");
  const vazio = document.getElementById("carrinho__vazio");

  if (!lista || !textoTotal) return;
  lista.innerHTML = "";
  if (vazio) vazio.style.display = "none";

  let total = 0;

  if (carrinho.length === 0 && vazio) {
    vazio.style.display = "block";
  }

  for (let i = 0; i < carrinho.length; i++) {
    let item = carrinho[i];
    let subtotal = item.preco * item.quantidade;
    total = total + subtotal;

    lista.innerHTML += `
      <li style="margin-bottom: 15px;">
        <strong>${item.nome}</strong> (${item.marca}) <br>
        Preço: ${formatarDinheiro(item.preco)} <br>
        Quantidade: 
        <button onclick="diminuir(${i})"> - </button>
        ${item.quantidade}
        <button onclick="aumentar(${i})"> + </button> <br>
        Subtotal: ${formatarDinheiro(subtotal)}
      </li>
    `;
  }

  textoTotal.innerText = formatarDinheiro(total);
}

function adicionarProduto(nome, marca, precoTexto) {
  // Verifica se o usuário está logado antes de adicionar ao carrinho
  if (!window.usuarioLogado) {
    mostrarToast("Faça login para adicionar itens ao carrinho");
    setTimeout(() => {
      window.location.href = "login.php";
    }, 1700);
    return;
  }

  let precoLimpo = precoTexto.replace("R$", "").replace(",", ".");
  let preco = parseFloat(precoLimpo);

  let achou = false;
  for (let i = 0; i < carrinho.length; i++) {
    if (carrinho[i].nome === nome && carrinho[i].marca === marca) {
      carrinho[i].quantidade += 1;
      achou = true;
      break;
    }
  }

  if (achou === false) {
    let novoItem = {
      nome: nome,
      marca: marca,
      preco: preco,
      quantidade: 1,
    };
    carrinho.push(novoItem);
  }
  salvarCarrinho();

  const overlay = document.getElementById("janela-carrinho");
  if (overlay && overlay.classList.contains("carrinho__overlay--aberto")) {
    desenharCarrinho();
  } else {
    mostrarToast(`${nome} adicionado ao carrinho`);
  }
}

function mostrarToast(mensagem) {
  let toast = document.getElementById("toast__carrinho");
  if (!toast) {
    toast = document.createElement("div");
    toast.id = "toast__carrinho";
    toast.style.position = "fixed";
    toast.style.zIndex = "1000";
    toast.style.left = "50%";
    toast.style.bottom = "24px";
    toast.style.transform = "translateX(-50%)";
    toast.style.background = "rgba(17, 24, 39, 0.95)";
    toast.style.color = "#fff";
    toast.style.padding = "12px 16px";
    toast.style.borderRadius = "14px";
    toast.style.fontWeight = "900";
    toast.style.boxShadow = "0 20px 60px rgba(0,0,0,0.25)";
    toast.style.opacity = "0";
    toast.style.pointerEvents = "none";
    toast.style.transition = "opacity 180ms ease, transform 180ms ease";
    document.body.appendChild(toast);
  }

  toast.textContent = mensagem;
  toast.style.opacity = "1";
  toast.style.transform = "translateX(-50%) translateY(-4px)";

  if (toast._timer) clearTimeout(toast._timer);
  toast._timer = setTimeout(() => {
    toast.style.opacity = "0";
    toast.style.transform = "translateX(-50%) translateY(0)";
  }, 1500);
}

function aumentar(posicao) {
  carrinho[posicao].quantidade += 1;
  salvarCarrinho();
  desenharCarrinho();
}

function diminuir(posicao) {
  carrinho[posicao].quantidade -= 1;
  if (carrinho[posicao].quantidade === 0) {
    carrinho.splice(posicao, 1);
  }

  salvarCarrinho();
  desenharCarrinho();
}

function limparCarrinho() {
  carrinho = [];
  salvarCarrinho();
  desenharCarrinho();
}

function finalizarCompra() {
  if (carrinho.length === 0) return;
  alert("Compra finalizada com sucesso!");
}

carregarCarrinho();

(function () {
  const btnAbrir = document.getElementById("carrinho__abrir");
  if (btnAbrir) {
    btnAbrir.addEventListener("click", () => {
      const el = document.getElementById("janela-carrinho");
      if (el) abrirCarrinho();
    });
  }

  // Botão de fechar
  const btnFechar = document.getElementById("carrinho__fechar");
  if (btnFechar) {
    btnFechar.addEventListener("click", () => fecharCarrinho());
  }

  // Clique em Adicionar ao Carrinho
  document.addEventListener("click", (e) => {
    const alvo =
      e.target && e.target.closest ? e.target.closest(".produto__btn") : null;
    if (!alvo) return;

    const card = alvo.closest(".produto");
    if (!card) return;

    const nome = card.querySelector(".produto__nome")?.innerText?.trim();
    const marca = card.querySelector(".produto__marca")?.innerText?.trim();
    const precoTexto = card.querySelector(".produto__preco")?.innerText?.trim();

    if (!nome || !marca || !precoTexto) {
      return;
    }

    adicionarProduto(nome, marca, precoTexto);
  });
})();

// Menu hamburguer
(function () {
  const overlay = document.getElementById("menu-mobile__overlay");
  const btnAbrir = document.getElementById("menu-hamburguer");
  const btnFechar = document.getElementById("menu-mobile__fechar");
  const btnCarrinho = document.getElementById("menu-mobile__carrinho");

  if (!overlay) return;

  function abrirMenu() {
    overlay.classList.add("menu-mobile__overlay--aberto");
    overlay.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
  }

  function fecharMenu() {
    overlay.classList.remove("menu-mobile__overlay--aberto");
    overlay.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  }

  if (btnAbrir) {
    btnAbrir.addEventListener("click", () => {
      abrirMenu();
    });
  }

  if (btnFechar) {
    btnFechar.addEventListener("click", () => {
      fecharMenu();
    });
  }

  overlay.addEventListener("click", (e) => {
    if (e.target === overlay) {
      fecharMenu();
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      fecharMenu();
    }
  });

  if (btnCarrinho) {
    btnCarrinho.addEventListener("click", () => {
      fecharMenu();
      const el = document.getElementById("janela-carrinho");
      if (el) {
        abrirCarrinho();
      }
    });
  }
})();

// Verificação de senha
function VerificaSenha() {
  var senha = document.getElementById("senha").value;
  var confirmar = document.getElementById("confirmar").value;

  if (senha !== confirmar) {
    alert("Confirmação de senha incorreta");
  }
}

document.querySelectorAll(".vitrine").forEach((vitrine) => {
  const produtos = vitrine.querySelector(".produtos");
  const btnAvancar = vitrine.querySelector(".avancar");
  const btnVoltar = vitrine.querySelector(".voltar");

  btnAvancar.addEventListener("click", () => {
    produtos.scrollBy({
      left: 330,
      behavior: "smooth",
    });
  });

  btnVoltar.addEventListener("click", () => {
    produtos.scrollBy({
      left: -330,
      behavior: "smooth",
    });
  });
});

// Preenchimento automático do endereço
const inputCep = document.getElementById("cep");

  inputCep.addEventListener("blur", function () {
    let cep = inputCep.value;

    // Remove tudo que não for número
    cep = cep.replace(/\D/g, "");

    // Verifica se tem exatamente 8 números
    if (cep.length !== 8) {
      alert("CEP inválido. Digite 8 números.");
      return;
    }

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
      .then(function (resposta) {
        return resposta.json();
      })
      .then(function (dados) {
        if (dados.erro) {
          alert("CEP não encontrado.");
          return;
        }

        document.getElementById("rua").value = dados.logradouro;
        document.getElementById("bairro").value = dados.bairro;
        document.getElementById("cidade").value = dados.localidade;
        document.getElementById("estado").value = dados.uf;
      })
      .catch(function () {
        alert("Erro ao buscar o CEP.");
      });
  });


// MÁSCARA DE CPF
const cpf = document.getElementById("cpf");
if (cpf) {
    cpf.addEventListener("input", function () {
        let valor = this.value.replace(/\D/g, "");
        valor = valor.substring(0, 11);
        valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
        valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
        valor = valor.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
        this.value = valor;
    });

}

// MÁSCARA DE CEP
const cep = document.getElementById("cep");
if (cep) {
    cep.addEventListener("input", function () {
        let valor = this.value.replace(/\D/g, "");
        valor = valor.substring(0, 8);
        valor = valor.replace(/(\d{5})(\d)/, "$1-$2");
        this.value = valor;
    });
}

// MÁSCARA DE TELEFONE
const telefone = document.getElementById("telefone");
if (telefone) {
    telefone.addEventListener("input", function () {
        let valor = this.value.replace(/\D/g, "");
        valor = valor.substring(0, 11);
        if (valor.length <= 10) {
            // Telefone fixo
            valor = valor.replace(/^(\d{2})(\d)/g, "($1) $2");
            valor = valor.replace(/(\d{4})(\d)/, "$1-$2");
        } else {
            // Celular
            valor = valor.replace(/^(\d{2})(\d)/g, "($1) $2");
            valor = valor.replace(/(\d{5})(\d)/, "$1-$2");
        }
        this.value = valor;

    });

=======
//Filtro de busca e categorias

document.addEventListener("DOMContentLoaded", function () {
  const botoes = document.querySelectorAll(".categorias a");
  const cards = document.querySelectorAll(".produto[data-categoria]");

  const inputBusca = document.getElementById("cabecalho-busca");
  const formBusca = document.getElementById("form-busca");
  let filtroCategoriaAtual = "Todas";

  function normalizar(texto) {
    return (texto || "").toString().trim().toLowerCase();
  }

  function setCategoriaAtiva(botaoAtivo) {
    botoes.forEach((b) => b.classList.remove("categoria__pill--ativo"));
    if (botaoAtivo) botaoAtivo.classList.add("categoria__pill--ativo");
  }

  function aplicarFiltros() {
    const termo = normalizar(inputBusca?.value);

    cards.forEach((card) => {
      const categoria = card.getAttribute("data-categoria");
      const nome = normalizar(card.querySelector(".produto__nome")?.innerText);
      const marca = normalizar(
        card.querySelector(".produto__marca")?.innerText,
      );

      const atendeCategoria =
        filtroCategoriaAtual === "Todas" || filtroCategoriaAtual === categoria;
      const atendeBusca =
        termo.length === 0 || nome.includes(termo) || marca.includes(termo);

      card.style.display = atendeCategoria && atendeBusca ? "flex" : "none";
    });
  }

  botoes.forEach((botao) => {
    botao.addEventListener("click", function (e) {
      e.preventDefault();

      const filtro = this.getAttribute("data-filtro");
      filtroCategoriaAtual = filtro;
      setCategoriaAtiva(this);

      aplicarFiltros();
    });
  });

  if (inputBusca && formBusca) {
    formBusca.addEventListener("submit", (e) => {
      e.preventDefault();
      aplicarFiltros();
    });

    inputBusca.addEventListener("input", () => {
      aplicarFiltros();
    });
  }

  const todos = document.querySelector('.categorias a[data-filtro="Todas"]');
  if (todos) {
    filtroCategoriaAtual = "Todas";
    todos.click();
  }
});

// Carrinho
let carrinho = [];

function carregarCarrinho() {
  let salvo = localStorage.getItem("meu_carrinho");
  if (salvo) {
    try {
      carrinho = JSON.parse(salvo);
    } catch (e) {
      carrinho = [];
    }
  }
}

function salvarCarrinho() {
  localStorage.setItem("meu_carrinho", JSON.stringify(carrinho));
}

function formatarDinheiro(valor) {
  return "R$ " + valor.toFixed(2).replace(".", ",");
}

function abrirCarrinho() {
  const overlay = document.getElementById("janela-carrinho");
  overlay.style.display = "flex";
  overlay.setAttribute("aria-hidden", "false");
  overlay.classList.add("carrinho__overlay--aberto");
  desenharCarrinho();
}

function fecharCarrinho() {
  const overlay = document.getElementById("janela-carrinho");
  if (!overlay) return;
  overlay.style.display = "none";
  overlay.setAttribute("aria-hidden", "true");
  overlay.classList.remove("carrinho__overlay--aberto");
}

function desenharCarrinho() {
  let lista = document.getElementById("lista-carrinho");
  let textoTotal = document.getElementById("valor-total");
  const vazio = document.getElementById("carrinho__vazio");

  if (!lista || !textoTotal) return;
  lista.innerHTML = "";
  if (vazio) vazio.style.display = "none";

  let total = 0;

  if (carrinho.length === 0 && vazio) {
    vazio.style.display = "block";
  }

  for (let i = 0; i < carrinho.length; i++) {
    let item = carrinho[i];
    let subtotal = item.preco * item.quantidade;
    total = total + subtotal;

    lista.innerHTML += `
      <li style="margin-bottom: 15px;">
        <strong>${item.nome}</strong> (${item.marca}) <br>
        Preço: ${formatarDinheiro(item.preco)} <br>
        Quantidade: 
        <button onclick="diminuir(${i})"> - </button>
        ${item.quantidade}
        <button onclick="aumentar(${i})"> + </button> <br>
        Subtotal: ${formatarDinheiro(subtotal)}
      </li>
    `;
  }

  textoTotal.innerText = formatarDinheiro(total);
}

function adicionarProduto(nome, marca, precoTexto) {
  // Verifica se o usuário está logado antes de adicionar ao carrinho
  if (!window.usuarioLogado) {
    mostrarToast("Faça login para adicionar itens ao carrinho");
    setTimeout(() => {
      window.location.href = "login.php";
    }, 1700);
    return;
  }

  let precoLimpo = precoTexto.replace("R$", "").replace(",", ".");
  let preco = parseFloat(precoLimpo);

  let achou = false;
  for (let i = 0; i < carrinho.length; i++) {
    if (carrinho[i].nome === nome && carrinho[i].marca === marca) {
      carrinho[i].quantidade += 1;
      achou = true;
      break;
    }
  }

  if (achou === false) {
    let novoItem = {
      nome: nome,
      marca: marca,
      preco: preco,
      quantidade: 1,
    };
    carrinho.push(novoItem);
  }
  salvarCarrinho();

  const overlay = document.getElementById("janela-carrinho");
  if (overlay && overlay.classList.contains("carrinho__overlay--aberto")) {
    desenharCarrinho();
  } else {
    mostrarToast(`${nome} adicionado ao carrinho`);
  }
}

function mostrarToast(mensagem) {
  let toast = document.getElementById("toast__carrinho");
  if (!toast) {
    toast = document.createElement("div");
    toast.id = "toast__carrinho";
    toast.style.position = "fixed";
    toast.style.zIndex = "1000";
    toast.style.left = "50%";
    toast.style.bottom = "24px";
    toast.style.transform = "translateX(-50%)";
    toast.style.background = "rgba(17, 24, 39, 0.95)";
    toast.style.color = "#fff";
    toast.style.padding = "12px 16px";
    toast.style.borderRadius = "14px";
    toast.style.fontWeight = "900";
    toast.style.boxShadow = "0 20px 60px rgba(0,0,0,0.25)";
    toast.style.opacity = "0";
    toast.style.pointerEvents = "none";
    toast.style.transition = "opacity 180ms ease, transform 180ms ease";
    document.body.appendChild(toast);
  }

  toast.textContent = mensagem;
  toast.style.opacity = "1";
  toast.style.transform = "translateX(-50%) translateY(-4px)";

  if (toast._timer) clearTimeout(toast._timer);
  toast._timer = setTimeout(() => {
    toast.style.opacity = "0";
    toast.style.transform = "translateX(-50%) translateY(0)";
  }, 1500);
}

function aumentar(posicao) {
  carrinho[posicao].quantidade += 1;
  salvarCarrinho();
  desenharCarrinho();
}

function diminuir(posicao) {
  carrinho[posicao].quantidade -= 1;
  if (carrinho[posicao].quantidade === 0) {
    carrinho.splice(posicao, 1);
  }

  salvarCarrinho();
  desenharCarrinho();
}

function limparCarrinho() {
  carrinho = [];
  salvarCarrinho();
  desenharCarrinho();
}

function finalizarCompra() {
  if (carrinho.length === 0) return;
  alert("Compra finalizada com sucesso!");
}

carregarCarrinho();

(function () {
  const btnAbrir = document.getElementById("carrinho__abrir");
  if (btnAbrir) {
    btnAbrir.addEventListener("click", () => {
      const el = document.getElementById("janela-carrinho");
      if (el) abrirCarrinho();
    });
  }

  // Botão de fechar
  const btnFechar = document.getElementById("carrinho__fechar");
  if (btnFechar) {
    btnFechar.addEventListener("click", () => fecharCarrinho());
  }

  // Clique em Adicionar ao Carrinho
  document.addEventListener("click", (e) => {
    const alvo =
      e.target && e.target.closest ? e.target.closest(".produto__btn") : null;
    if (!alvo) return;

    const card = alvo.closest(".produto");
    if (!card) return;

    const nome = card.querySelector(".produto__nome")?.innerText?.trim();
    const marca = card.querySelector(".produto__marca")?.innerText?.trim();
    const precoTexto = card.querySelector(".produto__preco")?.innerText?.trim();

    if (!nome || !marca || !precoTexto) {
      return;
    }

    adicionarProduto(nome, marca, precoTexto);
  });
})();

// Menu hamburguer
(function () {
  const overlay = document.getElementById("menu-mobile__overlay");
  const btnAbrir = document.getElementById("menu-hamburguer");
  const btnFechar = document.getElementById("menu-mobile__fechar");
  const btnCarrinho = document.getElementById("menu-mobile__carrinho");

  if (!overlay) return;

  function abrirMenu() {
    overlay.classList.add("menu-mobile__overlay--aberto");
    overlay.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
  }

  function fecharMenu() {
    overlay.classList.remove("menu-mobile__overlay--aberto");
    overlay.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  }

  if (btnAbrir) {
    btnAbrir.addEventListener("click", () => {
      abrirMenu();
    });
  }

  if (btnFechar) {
    btnFechar.addEventListener("click", () => {
      fecharMenu();
    });
  }

  overlay.addEventListener("click", (e) => {
    if (e.target === overlay) {
      fecharMenu();
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      fecharMenu();
    }
  });

  if (btnCarrinho) {
    btnCarrinho.addEventListener("click", () => {
      fecharMenu();
      const el = document.getElementById("janela-carrinho");
      if (el) {
        abrirCarrinho();
      }
    });
  }
})();

// Verificação de senha
function VerificaSenha() {
  var senha = document.getElementById("senha").value;
  var confirmar = document.getElementById("confirmar").value;

  if (senha !== confirmar) {
    alert("Confirmação de senha incorreta");
  }
}

document.querySelectorAll(".vitrine").forEach((vitrine) => {
  const produtos = vitrine.querySelector(".produtos");
  const btnAvancar = vitrine.querySelector(".avancar");
  const btnVoltar = vitrine.querySelector(".voltar");

  btnAvancar.addEventListener("click", () => {
    produtos.scrollBy({
      left: 330,
      behavior: "smooth",
    });
  });

  btnVoltar.addEventListener("click", () => {
    produtos.scrollBy({
      left: -330,
      behavior: "smooth",
    });
  });
});

// Preenchimento automático do endereço
const inputCep = document.getElementById("cep");

  inputCep.addEventListener("blur", function () {
    let cep = inputCep.value;

    // Remove tudo que não for número
    cep = cep.replace(/\D/g, "");

    // Verifica se tem exatamente 8 números
    if (cep.length !== 8) {
      alert("CEP inválido. Digite 8 números.");
      return;
    }

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
      .then(function (resposta) {
        return resposta.json();
      })
      .then(function (dados) {
        if (dados.erro) {
          alert("CEP não encontrado.");
          return;
        }

        document.getElementById("rua").value = dados.logradouro;
        document.getElementById("bairro").value = dados.bairro;
        document.getElementById("cidade").value = dados.localidade;
        document.getElementById("estado").value = dados.uf;
      })
      .catch(function () {
        alert("Erro ao buscar o CEP.");
      });
  });


// MÁSCARA DE CPF
const cpf = document.getElementById("cpf");
if (cpf) {
    cpf.addEventListener("input", function () {
        let valor = this.value.replace(/\D/g, "");
        valor = valor.substring(0, 11);
        valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
        valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
        valor = valor.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
        this.value = valor;
    });

}

// MÁSCARA DE CEP
const cep = document.getElementById("cep");
if (cep) {
    cep.addEventListener("input", function () {
        let valor = this.value.replace(/\D/g, "");
        valor = valor.substring(0, 8);
        valor = valor.replace(/(\d{5})(\d)/, "$1-$2");
        this.value = valor;
    });
}

// MÁSCARA DE TELEFONE
const telefone = document.getElementById("telefone");
if (telefone) {
    telefone.addEventListener("input", function () {
        let valor = this.value.replace(/\D/g, "");
        valor = valor.substring(0, 11);
        if (valor.length <= 10) {
            // Telefone fixo
            valor = valor.replace(/^(\d{2})(\d)/g, "($1) $2");
            valor = valor.replace(/(\d{4})(\d)/, "$1-$2");
        } else {
            // Celular
            valor = valor.replace(/^(\d{2})(\d)/g, "($1) $2");
            valor = valor.replace(/(\d{5})(\d)/, "$1-$2");
        }
        this.value = valor;

    });

>>>>>>> 49e16152607f6b45e123523087d29daafd698325
}