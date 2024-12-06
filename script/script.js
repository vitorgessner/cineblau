const botaoAdicionar = document.querySelector(".center .button");
const modal = document.querySelector(".modalCreate");
const modaisUpdate = document.querySelectorAll(".modalUpdate");
const modaisDirector = document.querySelectorAll(".modalDirector");
const modaisDirectorUpdate = document.querySelectorAll(".modalDirectorUpdate");
const modaisElenco = document.querySelectorAll(".modalElenco");
const modaisElencoUpdate = document.querySelectorAll(".modalElencoUpdate");
const modaisGenero = document.querySelectorAll(".modalGenero");
const modaisGeneroUpdate = document.querySelectorAll(".modalGeneroUpdate");
const modaisPremiacao = document.querySelectorAll(".modalPremiacao");
const modaisPremiacaoUpdate = document.querySelectorAll(".modalPremiacaoUpdate");
const modaisEmail = document.querySelectorAll(".modalEmail");
const modaisEmailUpdate = document.querySelectorAll(".modalEmailUpdate");
const modaisTelefone = document.querySelectorAll(".modalTelefone");
const modaisTelefoneUpdate = document.querySelectorAll(".modalTelefoneUpdate");
const modaisEndereco = document.querySelectorAll(".modalEndereco");
const modaisEnderecoUpdate = document.querySelectorAll(".modalEnderecoUpdate");

const botoesEditar = document.querySelectorAll("[value^='editar']");
const botoesAdicionar = document.querySelectorAll("[value^='adicionar']");
const botoesAdicionarDiretor = document.querySelectorAll("[value^='diretorAdicionar']");
const botoesEditarDiretor = document.querySelectorAll("[value^='diretorEditar']");
const botoesAdicionarElenco = document.querySelectorAll("[value^='elencoAdicionar']");
const botoesEditarElenco = document.querySelectorAll("[value^='elencoEditar']");
const botoesAdicionarGenero = document.querySelectorAll("[value^='generoAdicionar']");
const botoesEditarGenero = document.querySelectorAll("[value^='generoEditar']");
const botoesAdicionarPremiacao = document.querySelectorAll("[value^='premiacaoAdicionar']");
const botoesEditarPremiacao = document.querySelectorAll("[value^='premiacaoEditar']");
const botoesAdicionarEmail = document.querySelectorAll("[value^='emailAdicionar']");
const botoesEditarEmail = document.querySelectorAll("[value^='emailEditar']");
const botoesAdicionarTelefone = document.querySelectorAll("[value^='telefoneAdicionar']");
const botoesEditarTelefone = document.querySelectorAll("[value^='telefoneEditar']");
const botoesAdicionarEndereco = document.querySelectorAll("[value^='enderecoAdicionar']");
const botoesEditarEndereco = document.querySelectorAll("[value^='enderecoEditar']");


const modalContent = document.querySelectorAll(".modal .modalContent");


window.onclick = function (e) {
    if (e.target == modal) {
        modal.style.display = "none";
        document.body.style.overflow = 'initial';
    } else {
        for (modalUpdate of modaisUpdate){
            if (e.target == modalUpdate) {
                modalUpdate.style.display = "none";
                document.body.style.overflow = 'initial';
            }
        }
        for (modalDirector of modaisDirector){
            if (e.target == modalDirector) {
                modalDirector.style.display = "none";
                document.body.style.overflow = 'initial';
            }
        }
        for (modalDirectorUpdate of modaisDirectorUpdate){
            if (e.target == modalDirectorUpdate) {
                modalDirectorUpdate.style.display = "none";
                document.body.style.overflow = 'initial';
            }
        }
        for (modalElenco of modaisElenco){
            if (e.target == modalElenco) {
                modalElenco.style.display = "none";
                document.body.style.overflow = 'initial';
            }
        }
        for (modalElencoUpdate of modaisElencoUpdate){
            if (e.target == modalElencoUpdate) {
                modalElencoUpdate.style.display = "none";
                document.body.style.overflow = 'initial';
            }
        }
        for (modalGenero of modaisGenero){
            if (e.target == modalGenero) {
                modalGenero.style.display = "none";
                document.body.style.overflow = 'initial';
            }
        }
        for (modalGeneroUpdate of modaisGeneroUpdate){
            if (e.target == modalGeneroUpdate) {
                modalGeneroUpdate.style.display = "none";
                document.body.style.overflow = 'initial';
            }
        }
        for (modalPremiacao of modaisPremiacao){
            if (e.target == modalPremiacao) {
                modalPremiacao.style.display = "none";
                document.body.style.overflow = 'initial';
            }
        }
        for (modalPremiacaoUpdate of modaisPremiacaoUpdate){
            if (e.target == modalPremiacaoUpdate) {
                modalPremiacaoUpdate.style.display = "none";
                document.body.style.overflow = 'initial';
            }
        }
        for (modalEmail of modaisEmail){
            if (e.target == modalEmail) {
                modalEmail.style.display = "none";
                document.body.style.overflow = 'initial';
            }
        }
        for (modalEmailUpdate of modaisEmailUpdate){
            if (e.target == modalEmailUpdate) {
                modalEmailUpdate.style.display = "none";
                document.body.style.overflow = 'initial';
            }
        }
        for (modalTelefone of modaisTelefone){
            if (e.target == modalTelefone) {
                modalTelefone.style.display = "none";
                document.body.style.overflow = 'initial';
            }
        }
        for (modalTelefoneUpdate of modaisTelefoneUpdate){
            if (e.target == modalTelefoneUpdate) {
                modalTelefoneUpdate.style.display = "none";
                document.body.style.overflow = 'initial';
            }
        }
        for (modalEndereco of modaisEndereco){
            if (e.target == modalEndereco) {
                modalEndereco.style.display = "none";
                document.body.style.overflow = 'initial';
            }
        }
        for (modalEnderecoUpdate of modaisEnderecoUpdate){
            if (e.target == modalEnderecoUpdate) {
                modalEnderecoUpdate.style.display = "none";
                document.body.style.overflow = 'initial';
            }
        }
    }
}

botaoAdicionar.addEventListener('click', chamaModal);

function chamaModal(e) {
    e.preventDefault();
    modal.style.display = "flex";
    document.body.style.overflow = 'hidden';
}

for (botaoEditar of botoesEditar) {
    
    botaoEditar.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalUpdate of modaisUpdate) {
            if (modalUpdate.attributes['data-id'].value == data_id) {
                modalUpdate.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}

for (botaoAdicionarDiretor of botoesAdicionarDiretor) {
    
    botaoAdicionarDiretor.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalDirector of modaisDirector) {
            if (modalDirector.attributes['data-id'].value == data_id) {
                modalDirector.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}

for (botaoEditarDiretor of botoesEditarDiretor) {
    
    botaoEditarDiretor.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalDirectorUpdate of modaisDirectorUpdate) {
            if (modalDirectorUpdate.attributes['data-id'].value == data_id) {
                modalDirectorUpdate.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}

for (botaoAdicionarElenco of botoesAdicionarElenco) {
    
    botaoAdicionarElenco.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalElenco of modaisElenco) {
            if (modalElenco.attributes['data-id'].value == data_id) {
                modalElenco.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}

for (botaoEditarElenco of botoesEditarElenco) {
    
    botaoEditarElenco.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalElencoUpdate of modaisElencoUpdate) {
            if (modalElencoUpdate.attributes['data-id'].value == data_id) {
                modalElencoUpdate.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}

for (botaoAdicionarGenero of botoesAdicionarGenero) {
    
    botaoAdicionarGenero.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalGenero of modaisGenero) {
            if (modalGenero.attributes['data-id'].value == data_id) {
                modalGenero.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}

for (botaoEditarGenero of botoesEditarGenero) {
    
    botaoEditarGenero.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalGeneroUpdate of modaisGeneroUpdate) {
            if (modalGeneroUpdate.attributes['data-id'].value == data_id) {
                modalGeneroUpdate.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}

for (botaoAdicionarPremiacao of botoesAdicionarPremiacao) {
    
    botaoAdicionarPremiacao.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalPremiacao of modaisPremiacao) {
            if (modalPremiacao.attributes['data-id'].value == data_id) {
                modalPremiacao.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}

for (botaoEditarPremiacao of botoesEditarPremiacao) {
    
    botaoEditarPremiacao.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalPremiacaoUpdate of modaisPremiacaoUpdate) {
            if (modalPremiacaoUpdate.attributes['data-id'].value == data_id) {
                modalPremiacaoUpdate.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}

for (botaoAdicionarEmail of botoesAdicionarEmail) {
    
    botaoAdicionarEmail.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalEmail of modaisEmail) {
            if (modalEmail.attributes['data-id'].value == data_id) {
                modalEmail.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}

for (botaoEditarEmail of botoesEditarEmail) {
    
    botaoEditarEmail.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalEmailUpdate of modaisEmailUpdate) {
            if (modalEmailUpdate.attributes['data-id'].value == data_id) {
                modalEmailUpdate.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}

for (botaoAdicionarTelefone of botoesAdicionarTelefone) {
    
    botaoAdicionarTelefone.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalTelefone of modaisTelefone) {
            if (modalTelefone.attributes['data-id'].value == data_id) {
                modalTelefone.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}

for (botaoEditarTelefone of botoesEditarTelefone) {
    
    botaoEditarTelefone.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalTelefoneUpdate of modaisTelefoneUpdate) {
            if (modalTelefoneUpdate.attributes['data-id'].value == data_id) {
                modalTelefoneUpdate.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}

for (botaoAdicionarEndereco of botoesAdicionarEndereco) {
    
    botaoAdicionarEndereco.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalEndereco of modaisEndereco) {
            if (modalEndereco.attributes['data-id'].value == data_id) {
                modalEndereco.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}

for (botaoEditarEndereco of botoesEditarEndereco) {
    
    botaoEditarEndereco.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalEnderecoUpdate of modaisEnderecoUpdate) {
            if (modalEnderecoUpdate.attributes['data-id'].value == data_id) {
                modalEnderecoUpdate.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}