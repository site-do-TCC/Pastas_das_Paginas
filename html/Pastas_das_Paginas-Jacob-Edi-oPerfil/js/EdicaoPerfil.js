// Pegar o botão e o menu
const menuBtn = document.getElementById("menu-btn");
const menu = document.getElementById("menu");

// Quando clicar no botão, alternar classe
menuBtn.addEventListener("click", () => {
  menu.classList.toggle("show");
});

function mudarOlho(img){
 if (img.src.includes("/Pastas_das_Paginas-Jacob-Edi-oPerfil/img/olhoSenhaFechado.png")) {
        img.src = "/Pastas_das_Paginas-Jacob-Edi-oPerfil/img/olhoSenhaAberto.png";
    } else {
        img.src = "/Pastas_das_Paginas-Jacob-Edi-oPerfil/img/olhoSenhaFechado.png";
    }
    img.src = "/Pastas_das_Paginas-Jacob-Edi-oPerfil/img/logoAvena.png"
}