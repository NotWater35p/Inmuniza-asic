// Selección de elementos del DOM
const inputs = document.querySelectorAll(".input-field");
const toggle_btn = document.querySelectorAll(".toggle");
const main = document.querySelector("main");
const bullets = document.querySelectorAll(".bullets span");
const images = document.querySelectorAll(".image");

// Variables para el carrusel automático
let currentIndex = 1;
const totalSlides = 4;
let slideInterval;

// Efecto en el formulario 
inputs.forEach((inp) => {
    inp.addEventListener("focus", () => {
        inp.classList.add("active");
    });
    inp.addEventListener("blur", () => {
        if (inp.value != "") return;
        inp.classList.remove("active");
    });
});

// Alternar entre modos de inicio de sesión y registro
// toggle_btn.forEach((btn) => {
//     btn.addEventListener("click", () => {
//         main.classList.toggle("sign-up-mode");
//     });
// });

// Función para mover el carrusel
function moveSlider() {
    let index = this.dataset.value;
    
    let currentImage = document.querySelector(`.img-${index}`);
    images.forEach((img) => img.classList.remove("show"));
    currentImage.classList.add("show");

    const textSlider = document.querySelector(".text-group");
    textSlider.style.transform = `translateY(${-(index - 1) * 2.2}rem)`;

    bullets.forEach(bull => bull.classList.remove("active"));
    this.classList.add("active");
    
    // Actualizar el índice actual
    currentIndex = parseInt(index);
}

bullets.forEach((bullet) => {
    bullet.addEventListener("click", moveSlider);
});

// Función para avanzar al siguiente slide
function nextSlide() {
    currentIndex = currentIndex % totalSlides + 1;
    
    // Encontrar el bullet correspondiente y simular clic
    const nextBullet = document.querySelector(`.bullets span[data-value="${currentIndex}"]`);
    if (nextBullet) {
        nextBullet.click();
    }
}

// Iniciar el carrusel automático
function startAutoSlide() {
    slideInterval = setInterval(nextSlide, 4000); // Cambiar cada 4 segundos
}

// Detener el carrusel automático
function stopAutoSlide() {
    clearInterval(slideInterval);
}

// Reiniciar el carrusel automático
function resetAutoSlide() {
    stopAutoSlide();
    startAutoSlide();
}

// Pausar el carrusel cuando el usuario interactúa con él
document.querySelector('.carousel').addEventListener('mouseenter', stopAutoSlide);
document.querySelector('.carousel').addEventListener('mouseleave', startAutoSlide);

// Pausar el carrusel cuando se hace clic en un bullet
bullets.forEach((bullet) => {
    bullet.addEventListener('click', resetAutoSlide);
});

// Iniciar el carrusel automático al cargar la página
document.addEventListener('DOMContentLoaded', startAutoSlide);

// Funcionalidad para mostrar/ocultar contraseña
let eyeicon = document.getElementById("eyeicon");
let password = document.getElementById("password");

eyeicon.onclick = function(){
    if(password.type == "password"){
        password.type = "text";
        eyeicon.src = "img/svg/ojo-asul.svg";
    }else{
        password.type = "password";
        eyeicon.src = "img/svg/ojo-gri.svg";
    }
}

    document.getElementById('cedula').addEventListener('input', function (e) {
        this.value = this.value.replace(/\D/g, '');
    });