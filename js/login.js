document.addEventListener('DOMContentLoaded', () => {
  console.log('login.js carregado');

  // Menu
  const menuBtn = document.getElementById("menu-btn");
  const menu = document.getElementById("menu-login");
  if (menuBtn && menu) {
    menuBtn.addEventListener("click", () => menu.classList.toggle("show"));
  }

  // Expor funções globalmente (assim o inline também consegue chamar, se houver)
  window.mostrarModal = function(mensagem) {
    const modal = document.getElementById("modalErro");
    const msg = document.getElementById("mensagemErro"); 
    
    if (modal & msg){
    if (!modal || !msg) {
      console.warn('mostrarModal: elementos do modal não encontrados');
      return;
    }
    msg.innerText = mensagem;
    modal.style.display = "flex";
    }
  };


  window.fecharModal = function() {
    const modal = document.getElementById("modalErro");
    if (modal){
      if (modal) modal.style.display = "none";
    }
  };

  // Se a URL vier com erro, mostrar modal automaticamente
  const urlParams = new URLSearchParams(window.location.search);
  const erro = urlParams.get('erro');
  if (erro === '1') {
    window.mostrarModal("Email ou senha inválidos!");
  } else if (erro === '2') {
    window.mostrarModal("Acesso inválido, preencha os campos.");
  }

});
