document.addEventListener("DOMContentLoaded", function () {
  const botoes = document.querySelectorAll(".categorias a");
  const cards = document.querySelectorAll(".produto[data-categoria]");

  function setCategoriaAtiva(botaoAtivo) {
    botoes.forEach((b) => b.classList.remove("categoria__pill--ativo"));
    if (botaoAtivo) botaoAtivo.classList.add("categoria__pill--ativo");
  }

  botoes.forEach((botao) => {
    botao.addEventListener("click", function (e) {
      e.preventDefault();

      const filtro = this.getAttribute("data-filtro");
      setCategoriaAtiva(this);

      cards.forEach((card) => {
        const categoria = card.getAttribute("data-categoria");

        if (filtro === "Todas" || filtro === categoria) {
          card.style.display = "flex";
        } else {
          card.style.display = "none";
        }
      });
    });
  });

  const todos = document.querySelector('.categorias a[data-filtro="Todas"]');
  if (todos) todos.click();
});


function VerificaSenha() {
  var senha = document.getElementById("senha").value;
  var confirmar = document.getElementById("confirmar").value;
  if (senha !== confirmar) {
    alert("Confirmação de senha incorreta");
  }
}