console.log("JS carregado!");

// Pegar o botão e o menu
const menuBtn = document.getElementById("menu-btn");
const menu = document.getElementById("menu");

// Quando clicar no botão, alternar classe
menuBtn.addEventListener("click", () => {
  menu.classList.toggle("show");
});


//Imagens do Perfil
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

//Imagem do banner
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

