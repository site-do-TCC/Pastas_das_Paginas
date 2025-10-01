console.log("JS carregado!");

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



const inputFoto = document.getElementById('fotoPerfil');
const previewFoto = document.getElementById('previewFoto');

if (inputFoto && previewFoto) {
  inputFoto.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        previewFoto.src = e.target.result; // substitui pela foto escolhida
      };
      reader.readAsDataURL(file);
    }
  });
}

function previewImagem(inputId, previewId) {
  const input = document.getElementById(inputId);
  const preview = document.getElementById(previewId);

  input.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        preview.src = e.target.result;
        preview.style.display = "block"; // mostra a imagem
      };
      reader.readAsDataURL(file);
    }
  });
}

// Ativar para os 3 banners
previewImagem("Banner1", "previewBanner1");
previewImagem("Banner2", "previewBanner2");
previewImagem("Banner3", "previewBanner3");

// Apagar imagem ao clicar na lixeira
document.querySelectorAll(".foto .lixeira").forEach((lixeira, index) => {
  lixeira.addEventListener("click", (e) => {
    e.stopPropagation(); // não abrir o input ao clicar
    const preview = document.getElementById(`previewBanner${index+1}`);
    const input = document.getElementById(`Banner${index+1}`);
    preview.src = "";
    preview.style.display = "none";
    input.value = ""; // limpa input
  });
});
    

    