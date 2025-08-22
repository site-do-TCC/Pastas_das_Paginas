

const menuBtn = document.getElementById("menu-btn");
const menu = document.getElementById("menu-login");

// Quando clicar no botão, alternar classe
menuBtn.addEventListener("click", () => {
  menu.classList.toggle("show");
});


    const senhaInput = document.getElementById("senha");
    const toggleBtn = document.getElementById("toggleSenha");

    toggleBtn.addEventListener("click", () => {
      const type = senhaInput.getAttribute("type") === "password" ? "text" : "password";
      senhaInput.setAttribute("type", type);
    });
