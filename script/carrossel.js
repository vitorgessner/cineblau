let carrossel = document.querySelector(".carrossel");
let proximo = document.querySelector(".right");
let anterior = document.querySelector(".left");
let slides = document.getElementsByClassName("card");
let distancia = 0;
let rolagens = slides.length - 4;

proximo.addEventListener("click", incrementa);

anterior.addEventListener("click", decrementa);

function incrementa() {
    distancia -= 20;
    if (distancia < -(20 * rolagens)){
        distancia = 0;
    }
    for (let i = 0; i < slides.length; i++){
        carrossel.children[i].style.transform = `translateX(${distancia}rem)`
    }
    
}

function decrementa() {
    distancia += 20;
    if (distancia > 0){
        distancia = -(20 * rolagens);
    }
    for (let i = 0; i < slides.length; i++){
        carrossel.children[i].style.transform = `translateX(${distancia}rem)`
    }
}

setInterval(incrementa, 4000);
