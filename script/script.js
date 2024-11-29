const botaoAdicionar = document.querySelector(".center .button");
const modal = document.querySelector(".modalCreate");
const modaisUpdate = document.querySelectorAll(".modalUpdate");
const botoesEditar = document.querySelectorAll("[value^='editar']");
const botoesAdicionar = document.querySelectorAll("[value^='adicionar']");
const modalContent = document.querySelectorAll(".modal .modalContent");
const modaisOptions = document.querySelectorAll(".modal .modalOptions");


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

for (botaoAdd of botoesAdicionar) {
    
    botaoAdd.addEventListener('click', (e) => {
        e.preventDefault();
        let data_id = e.target.value.split(" ")[1];
        for (modalOption of modaisOptions) {
            if (modalOption.attributes['data-id'].value == data_id) {
                modalOption.style.display = "flex";
                document.body.style.overflow = 'hidden';
            }
        }
    });
}
