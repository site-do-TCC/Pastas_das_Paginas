//Informação
function mostrarModal(mensagem) {
  document.getElementById("mensagemErro").innerText = mensagem;
  document.getElementById("modalErro").style.display = "flex"; 
}


function fecharModal() {
  document.getElementById("modalErro").style.display = "none";
}