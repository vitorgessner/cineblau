let classificacoes = document.querySelectorAll(".classificacao");
for (classificacao of classificacoes) {
    classificacao.addEventListener('input', resizeInput);
    resizeInput.call(classificacao);

    if (classificacao.value == "L") {
        classificacao.classList.add("l");
    }

    if (classificacao.value == '10') {
        classificacao.classList.add("dez")
    }

    if (classificacao.value == '12') {
        classificacao.classList.add("doze")
    }

    if (classificacao.value == '14') {
        classificacao.classList.add("catorze")
    }

    if (classificacao.value == '16') {
        classificacao.classList.add("dezesseis")
    }

    if (classificacao.value == '18') {
        classificacao.classList.add("dezoito")
    }
}

let inputs = document.querySelectorAll('.inputEditable');
for (input of inputs) {
    input.addEventListener('input', resizeInput);
    resizeInput.call(input);
}



function resizeInput() {
    this.style.width = this.value.length + 1 + "ch";

    if (this.value == 'L'){
        this.style.width = this.value.length + 2 + "ch";
    }
}