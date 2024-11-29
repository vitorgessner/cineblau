<?php 
require 'config.php';
require_once 'biblioteca_autenticacao.php';

$pdo = getPDO();
$colaboradores = listarColaboradores($pdo);

$cidadeURL = $_GET['cidade'];

function listarColaboradores($pdo){
    $sql = "select * from infoColaboradores;";

    $resultado = $pdo->query($sql);
    $colaboradores = $resultado->fetchAll(PDO::FETCH_ASSOC);
    $resultado->closeCursor();

    return $colaboradores;
}

function idColaborador($pdo, $cpf){
    $sql = "call idColaborador('$cpf');";

    $resultado = $pdo->query($sql);
    $idColaborador = $resultado->fetch(PDO::FETCH_ASSOC);
    return $idColaborador;
}

// function adicionarColaborador($nome, $sobrenome, $cpf, $sexo, $funcao){
//     $pdo = getPDO();

    
// }

function adicionarEmailColaborador($idColaborador, $email){
    $pdo = getPDO();

    $sql = "insert into colaboradores_email (id_colaborador, email) values
    (:id_colaborador, :email);";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id_colaborador', $idColaborador['id']);
    $resultado->bindParam(':email', $email);

    $resultado->execute();
}

function atualizarEmailColaborador($idColaborador, $email){
    $pdo = getPDO();

    $sql = "update colaboradores_email set id_colaborador = :id_colaborador, email = :email
    WHERE id_colaborador = :id_colaborador;";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id_colaborador', $idColaborador['id']);
    $resultado->bindParam(':email', $email);

    $resultado->execute();
}

function adicionarTelefoneColaborador($idColaborador, $telefone, $tipo){
    $pdo = getPDO();

    $sql = "insert into colaboradores_telefone (id_colaborador, telefone, tipo) values
    (:id_colaborador, :telefone, :tipo);";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id_colaborador', $idColaborador['id']);
    $resultado->bindParam(':telefone', $telefone);
    $resultado->bindParam(':tipo', $tipo);

    $resultado->execute();
}

function atualizarTelefoneColaborador($idColaborador, $telefone, $tipo){
    $pdo = getPDO();

    $sql = "update colaboradores_telefone set id_colaborador = :id_colaborador, telefone = :telefone, tipo = :tipo
    WHERE id_colaborador = :id_colaborador;";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id_colaborador', $idColaborador['id']);
    $resultado->bindParam(':telefone', $telefone);
    $resultado->bindParam(':tipo', $tipo);

    $resultado->execute();
}

function adicionarEnderecoColaborador($idColaborador, $estado, $cidade, $bairro, $logradouro, $numero, $complemento, $cep){
    $pdo = getPDO();

    if ($complemento == ''){
        $complemento = null;
    }

    $sql = "insert into colaboradores_endereco (id_colaborador, estado, cidade, bairro, logradouro, numero, complemento, cep) values
    (:id_colaborador, :estado, :cidade, :bairro, :logradouro, :numero, :complemento, :cep);";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id_colaborador', $idColaborador['id']);
    $resultado->bindParam(':estado', $estado);
    $resultado->bindParam(':cidade', $cidade);
    $resultado->bindParam(':bairro', $bairro);
    $resultado->bindParam(':logradouro', $logradouro);
    $resultado->bindParam(':numero', $numero);
    $resultado->bindParam(':complemento', $complemento);
    $resultado->bindParam(':cep', $cep);

    $resultado->execute();
}

function atualizarEnderecoColaborador($idColaborador, $estado, $cidade, $bairro, $logradouro, $numero, $complemento, $cep){
    $pdo = getPDO();

    if ($complemento == ''){
        $complemento = null;
    }

    $sql = "update colaboradores_endereco set id_colaborador = :id_colaborador, estado = :estado, cidade = :cidade, bairro = :bairro, logradouro = :logradouro, numero = :numero, complemento = :complemento, cep = :cep
    WHERE id_colaborador = :id_colaborador;";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id_colaborador', $idColaborador['id']);
    $resultado->bindParam(':estado', $estado);
    $resultado->bindParam(':cidade', $cidade);
    $resultado->bindParam(':bairro', $bairro);
    $resultado->bindParam(':logradouro', $logradouro);
    $resultado->bindParam(':numero', $numero);
    $resultado->bindParam(':complemento', $complemento);
    $resultado->bindParam(':cep', $cep);

    $resultado->execute();
}

if (isset($_GET['search']) && isset($_GET['cidade'])) {
    $pesquisa = $_GET['search'];
    $cidadeURL = $_GET['cidade'];

    if ($pesquisa == "") {
        $pesquisa = '.';
    }
    header("location: filme.php?cidade=$cidadeURL&search=$pesquisa");
}

$sql = "select c.*, ce.email FROM colaboradores as c
    JOIN colaboradores_email as ce on c.id = ce.id_colaborador
    WHERE c.funcao = 'Gerente';";

$resultado = $pdo->query($sql);

$adm = $resultado->fetch(PDO::FETCH_ASSOC);
$resultado->closeCursor();

if (isset($_REQUEST['login']) && isset($_REQUEST['password'])) {
    $login = $_REQUEST['login'];
    $senha = $_REQUEST['password'];

    if ($login === $adm['email']) {
        if ($senha === $adm['cpf']) {
            login($login);
        } else {
            $mensagem = "Senha inválida";
        }
    }
}

if (isset($_POST['logoff'])) {
    logoff();
}

if (isset($_POST['acao'])) {
    if (preg_match("/^excluir/", $_POST['acao'])) {
        $separado = explode(" ", $_POST['acao']);
        $id = end($separado);

        $sql = "delete from colaboradores
        WHERE id = :id";

        $resultado = $pdo->prepare($sql);
        $resultado->bindParam(':id', $id);

        $resultado->execute();
        $colaboradores = listarColaboradores($pdo);
    } else {
        var_dump($_POST);
    }
}

if (isset($_POST['adicionar'])){
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $cpf = $_POST['cpf'];
    $sexo = $_POST['sexo'];
    $funcao = $_POST['funcao'];
    $emails = $_POST['email'];
    $telefone = $_POST['telefone'];
    $tipo = $_POST['tipo'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $logradouro = $_POST['logradouro'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $cep = $_POST['cep'];

    $sql = "insert into colaboradores (nome, sobrenome, cpf, sexo, funcao) values
    (:nome, :sobrenome, :cpf, :sexo, :funcao);";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':nome', $nome);
    $resultado->bindParam(':sobrenome', $sobrenome);
    $resultado->bindParam(':cpf', $cpf);
    $resultado->bindParam(':sexo', $sexo);
    $resultado->bindParam(':funcao', $funcao);

    $resultado->execute();

    $idColaborador = idColaborador($pdo, $cpf);

    if ($emails != ""){
        $emailArr = explode(", ", $emails);
        foreach ($emailArr as $email) {
            adicionarEmailColaborador($idColaborador, $email);
        }
    }

    if ($telefone != "" && $tipo != ""){
        adicionarTelefoneColaborador($idColaborador, $telefone, $tipo);
    }

    if ($estado != "" && $cidade != "" && $bairro != "" && $logradouro != "" && $numero != "" && $cep != ""){
        adicionarEnderecoColaborador($idColaborador, $estado, $cidade, $bairro, $logradouro, $numero, $complemento, $cep);
    }

    $colaboradores = listarColaboradores($pdo);
}

if (isset($_POST['atualizar'])){
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $cpf = $_POST['cpf'];
    $sexo = $_POST['sexo'];
    $funcao = $_POST['funcao'];
    $emails = $_POST['email'];
    $telefone = $_POST['telefone'];
    $tipo = $_POST['tipo'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $logradouro = $_POST['logradouro'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $cep = $_POST['cep'];
    $id = $_POST['id'];

    $sql = "update colaboradores set nome = :nome, sobrenome = :sobrenome, cpf = :cpf, sexo = :sexo, funcao = :funcao
    WHERE id = :id;";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':nome', $nome);
    $resultado->bindParam(':sobrenome', $sobrenome);
    $resultado->bindParam(':cpf', $cpf);
    $resultado->bindParam(':sexo', $sexo);
    $resultado->bindParam(':funcao', $funcao);
    $resultado->bindParam(':id', $id);

    $resultado->execute();

    $idColaborador = idColaborador($pdo, $cpf);

    if ($emails != ""){
        $emailArr = explode(", ", $emails);
        foreach ($emailArr as $email) {
            atualizarEmailColaborador($idColaborador, $email);
        }
    }

    if ($telefone != "" && $tipo != ""){
        atualizarTelefoneColaborador($idColaborador, $telefone, $tipo);
    }

    if ($estado != "" && $cidade != "" && $bairro != "" && $logradouro != "" && $numero != "" && $cep != ""){
        atualizarEnderecoColaborador($idColaborador, $estado, $cidade, $bairro, $logradouro, $numero, $complemento, $cep);
    }

    $colaboradores = listarColaboradores($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="script/script.js" defer></script>
    <title>Cineblau</title>
</head>
<body>
<header class="main_header">
        <a class="identidade" href="index.php">
            <img src="images/logoCinebg.png" alt="logo" class="logo">
            <h1 class="main_title">Cineblau</h1>
        </a>

        <div>
            <form action="" method="get">
                <input type="search" name="search" id="search" placeholder="Pesquisar filmes..." class="input input_dark">
                <input type="hidden" name="cidade" value="<?= $cidadeURL ?>">
                <button hidden></button>
            </form>

            <?php if (!estaAutenticado()) { ?>
                <form action="" method="post">
                    <input type="text" name="login" id="login" placeholder="Email..." class="input input_light">
                    <input type="password" name="password" id="password" placeholder="Senha..." class="input input_light">
                    <button class="button">Login</button>
                </form>
        </div>

    </header>
<?php } else { ?>
    <a href="colaboradores.php?cidade=<?=$cidadeURL?>" class="colaboradores">Colaboradores</a>
    <p class="adm">Olá! <?= $adm['nome'] ?></p>

    <form action="" method="post">
        <button class="button" name="logoff">Logoff</button>
    </form>
    </div>

    </header>
<?php } ?>

<aside class="main_aside">
    <ul class="options">
        <li><a href="cartaz.php?cidade=<?= $cidadeURL ?>">Em cartaz</a></li>
        <li><a href="sessoes.php?cidade=<?= $cidadeURL ?>">Sessões</a></li>
        <li><a href="salas.php?cidade=<?= $cidadeURL ?>">Salas</a></li>
        <li><a href="emBreve.php?cidade=<?= $cidadeURL ?>">Em breve</a></li>
        <li><a href="reprises.php?cidade=<?= $cidadeURL ?>">Reprises</a></li>
        <li><a href="endereco.php?cidade=<?= $cidadeURL ?>">Endereço</a></li>
    </ul>
</aside>

<main>
    <?php if (estaAutenticado()) { ?>
        <div class="center"><button class="button">Adicionar Colaborador</button></div>
    <?php } ?>

    <?php foreach ($colaboradores as $colaborador) {
        $idColaborador = $colaborador['id'];?>
        <form action="" method="post">
            <article class="card_breve card_colaborador">
                <div class="info_breve">
                    <h2>Nome Completo: <?= $colaborador['nome'] . " " .  $colaborador['sobrenome']; ?></h2>
                    <hr>
                    <h3>CPF: <?= $colaborador['cpf'] . " / Sexo: " . $colaborador['sexo'] . ' / Função: ' . $colaborador['funcao']?></h3>
                    <hr>
                    <h4>Emails: <?php
                                $sql = "call emailColaborador('$idColaborador');";

                                $resultado = $pdo->query($sql);

                                $emails = $resultado->fetchAll(PDO::FETCH_ASSOC);
                                $resultado->closeCursor();

                                foreach ($emails as $idx => $email) { ?>
                            <input type="hidden" name="idAtor" value="<?= $ator['id'] ?>">
                        <?php
                                    if ($idx == count($emails) - 1) {
                                        echo $email['email'];
                                    } else {
                                        echo $email['email'] . ", ";
                                    }
                                } ?>
                    </h4>
                    <hr>
                    <p>Telefones: <?php
                                $sql = "call telefoneColaborador('$idColaborador')";

                                $resultado = $pdo->query($sql);

                                $telefones = $resultado->fetchAll(PDO::FETCH_ASSOC);
                                $resultado->closeCursor();

                                foreach ($telefones as $idx => $telefone) {
                                    if ($idx == count($telefones) - 1) {
                                        echo $telefone['tipo'] . ' ' . $telefone['telefone'];
                                    } else {
                                        echo $telefone['tipo'] . ' ' . $telefone['telefone'] . ", ";
                                    }
                                }
                                ?></p>
                    <hr>
                    <p>Endereço: <?php $colaborador['complemento'] == '' ? $mensagem = '' : $mensagem = ', Complemento: '; echo $colaborador['cidade'].', '.$colaborador['logradouro'].' '. $colaborador['numero'].', '.$colaborador['bairro'].', CEP: '.$colaborador['cep']."$mensagem". $colaborador['complemento'];?></p>
                    <?php if (estaAutenticado()) { ?>
                        <div>
                            <button class="button red" name="acao" value="excluir <?= $colaborador['id'] ?>">Excluir</button>
                            <button class="button blue" name="acao" value="editar <?= $colaborador['id'] ?>">Editar</button>
                        </div>
                    <?php } ?>
                </div>
            </article>
        </form>

        <div class="modal modalUpdate" data-id="<?= $colaborador['id'] ?>">
            <div class="modalContent">
                <p>Editar</p>
                <form action="" method="post">
                    <div>
                        <label for="nome">Nome: </label>
                        <input type="text" name="nome" value="<?= $colaborador['nome'] ?>">
                    </div>
                    <div>
                        <label for="sobrenome">Sobrenome: </label>
                        <input type="text" name="sobrenome" value="<?= $colaborador['sobrenome'] ?>">
                    </div>
                    <div>
                        <label for="cpf">CPF: </label>
                        <input type="text" name="cpf" value="<?= $colaborador['cpf'] ?>">
                    </div>
                    <div>
                        <label for="sexo">Sexo: </label>
                        <input type="text" name="sexo" value="<?= $colaborador['sexo'] ?>">
                    </div>
                    <div>
                        <label for="funcao">Função: </label>
                        <input type="text" name="funcao" value="<?= $colaborador['funcao'] ?>">
                    </div>
                    <div>
                        <label for="email">Email: </label>
                        <input type="text" name="email" value="<?php
                                $sql = "call emailColaborador('$idColaborador');";

                                $resultado = $pdo->query($sql);

                                $emails = $resultado->fetchAll(PDO::FETCH_ASSOC);
                                $resultado->closeCursor();

                                foreach ($emails as $idx => $email) {
                                    if ($idx == count($emails) - 1) {
                                        echo $email['email'];
                                    } else {
                                        echo $email['email'] . ", ";
                                    }
                                } ?>">
                    </div>
                    <div>
                        <label for="telefone">Telefone: </label>
                        <input type="text" name="telefone" value="<?php
                                $sql = "call telefoneColaborador('$idColaborador')";

                                $resultado = $pdo->query($sql);

                                $telefones = $resultado->fetchAll(PDO::FETCH_ASSOC);
                                $resultado->closeCursor();

                                foreach ($telefones as $idx => $telefone) {
                                    if ($idx == count($telefones) - 1) {
                                        echo $telefone['telefone'];
                                    } else {
                                        echo $telefone['telefone'] . ", ";
                                    }
                                }
                                ?>">
                    </div>
                    <div>
                        <label for="tipo">Tipo: </label>
                        <input type="text" name="tipo" value="<?php
                                $sql = "call telefoneColaborador('$idColaborador')";

                                $resultado = $pdo->query($sql);

                                $telefones = $resultado->fetchAll(PDO::FETCH_ASSOC);
                                $resultado->closeCursor();

                                foreach ($telefones as $idx => $telefone) {
                                    if ($idx == count($telefones) - 1) {
                                        echo $telefone['tipo'];
                                    } else {
                                        echo $telefone['tipo'] . ", ";
                                    }
                                }
                                ?>">
                    </div>
                    <div>
                        <label for="estado">Estado: </label>
                        <input type="text" name="estado" value="<?= $colaborador['estado'] ?>">
                    </div>
                    <div>
                        <label for="cidade">Cidade: </label>
                        <input type="text" name="cidade" value="<?= $colaborador['cidade'] ?>">
                    </div>
                    <div>
                        <label for="bairro">Bairro: </label>
                        <input type="text" name="bairro" value="<?= $colaborador['bairro'] ?>">
                    </div>
                    <div>
                        <label for="logradouro">Logradouro: </label>
                        <input type="text" name="logradouro" value="<?= $colaborador['logradouro'] ?>">
                    </div>
                    <div>
                        <label for="numero">Número: </label>
                        <input type="text" name="numero" value="<?= $colaborador['numero'] ?>">
                    </div>
                    <div>
                        <label for="complemento">Complemento: </label>
                        <input type="text" name="complemento" value="<?= $colaborador['complemento'] ?>">
                    </div>
                    <div>
                        <label for="cep">CEP: </label>
                        <input type="text" name="cep" value="<?= $colaborador['cep'] ?>">
                        <input type="hidden" name="id" value="<?=$colaborador['id']?>">
                    </div>
                    <button class="button blue" name="atualizar">Salvar Alterações</button>
                </form>
            </div>
        </div>
    <?php } ?>
</main>

<div class="modal modalCreate">
    <div class="modalContent">
        <p>Adicionar</p>
        <form action="" method="post">
            <div>
                <label for="nome">Nome: </label>
                <input type="text" name="nome" placeholder="Digite o nome (texto)">
            </div>
            <div>
                <label for="sobrenome">Sobrenome: </label>
                <input type="text" name="sobrenome" placeholder="Digite o sobrenome (texto)">
            </div>
            <div>
                <label for="cpf">CPF: </label>
                <input type="text" name="cpf" placeholder="Digite o cpf (texto)">
            </div>
            <div>
                <label for="sexo">Sexo: </label>
                <input type="text" name="sexo" placeholder="Digite o sexo (texto)">
            </div>
            <div>
                <label for="funcao">Função: </label>
                <input type="text" name="funcao" placeholder="Digite a funcao (texto)">
            </div>
            <div>
                <label for="email">Email: </label>
                <input type="text" name="email" placeholder="Digite o email (texto)">
            </div>
            <div>
                <label for="telefone">Telefone: </label>
                <input type="text" name="telefone" placeholder="(00) 00000-0000">
            </div>
            <div>
                <label for="tipo">Tipo do telefone: </label>
                <input type="text" name="tipo" placeholder="(residencial, celular)">
            </div>
            <div>
                <label for="estado">Estado: </label>
                <input type="text" name="estado" placeholder="Digite o estado (texto)">
            </div>
            <div>
                <label for="cidade">Cidade: </label>
                <input type="text" name="cidade" placeholder="Digite a cidade (texto)">
            </div>
            <div>
                <label for="bairro">Bairro: </label>
                <input type="text" name="bairro" placeholder="Digite o bairro (texto)">
            </div>
            <div>
                <label for="logradouro">Logradouro: </label>
                <input type="text" name="logradouro" placeholder="Digite o logradouro (texto)">
            </div>
            <div>
                <label for="numero">Número: </label>
                <input type="text" name="numero" placeholder="Digite o número (int)">
            </div>
            <div>
                <label for="complemento">Complemento: </label>
                <input type="text" name="complemento" placeholder="(opcional)">
            </div>
            <div>
                <label for="cep">CEP: </label>
                <input type="text" name="cep" placeholder="(00000-000)">
            </div>
            <button class="button" name="adicionar" value="adicionar">Adicionar</button>
        </form>
    </div>
</div>
</body>
</html>