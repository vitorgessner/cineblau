let classificacoes = document.querySelectorAll(".classificacao");
for (classificacao of classificacoes) {
    if (classificacao.textContent == "L") {
        classificacao.classList.add("l");
    }

    if (classificacao.textContent == '10') {
        classificacao.classList.add("dez")
    }

    if (classificacao.textContent == '12') {
        classificacao.classList.add("doze")
    }

    if (classificacao.textContent == '14') {
        classificacao.classList.add("catorze")
    }

    if (classificacao.textContent == '16') {
        classificacao.classList.add("dezesseis")
    }

    if (classificacao.textContent == '18') {
        classificacao.classList.add("dezoito")
    }
}