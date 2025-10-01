console.log("JS carregado");

// Pegar o botão e o menu
const menuBtn = document.getElementById("menu-btn");
const menulogin = document.getElementById("menulogin");

// Quando clicar no botão, alternar classe
menuBtn.addEventListener("click", () => {
  menulogin.classList.toggle("show");
});


