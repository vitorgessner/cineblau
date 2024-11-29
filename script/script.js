const botaoAdicionar = document.querySelector(".center .button");
const modal = document.querySelector(".modal");


window.onclick = function(e) {
    if (e.target == modal){
        modal.style.display = "none";
        document.body.style.overflow = 'initial';
    }
}

botaoAdicionar.addEventListener('click', chamaModal);

function chamaModal(e){
    e.preventDefault();
    modal.style.display = "flex";
    document.body.style.overflow = 'hidden';
}
