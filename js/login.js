// Pegar o botão e o menu
const menuBtn = document.getElementById("menu-btn");
const menulogin = document.getElementById("menulogin");

// Quando clicar no botão, alternar classe
menuBtn.addEventListener("click", () => {
  menulogin.classList.toggle("show");
});


//Informação
function mostrarModal(mensagem) {
  document.getElementById("mensagemErro").innerText = mensagem;
  document.getElementById("modalErro").style.display = "flex"; 
}


function fecharModal() {
  document.getElementById("modalErro").style.display = "none";
}