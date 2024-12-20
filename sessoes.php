<?php

require 'config.php';
require_once 'biblioteca_autenticacao.php';

$pdo = getPDO();
$sessoes = listarSessoes($pdo);

$cidadeURL = $_GET['cidade'];

if (isset($_GET['search']) && isset($_GET['cidade'])) {
    $pesquisa = $_GET['search'];
    $cidadeURL = $_GET['cidade'];

    if ($pesquisa == "") {
        $pesquisa = '.';
    }
    header("location: filme.php?cidade=$cidadeURL&search=$pesquisa");
}

function listarSessoes($pdo)
{
    $sql = "select * from view_sessoes";

    $resultado = $pdo->query($sql);

    $sessoes = $resultado->fetchAll(PDO::FETCH_ASSOC);
    $resultado->closeCursor();

    return $sessoes;
}

function getIdSala($sala){
    $pdo = getPDO();
    $sql = "call idSala('$sala');";

    $resultado = $pdo->query($sql);
    $id_sala = $resultado->fetch(PDO::FETCH_ASSOC);
    $resultado->closeCursor();
    return $id_sala;
}

function getIdFilme($filme){
    $pdo = getPDO();
    $sql = "call idFilme('$filme');";

    $resultado = $pdo->query($sql);
    $id_filme = $resultado->fetch(PDO::FETCH_ASSOC);
    $resultado->closeCursor();
    return $id_filme;
}

function adicionarSessao($sala, $filme, $data, $hora, $idioma)
{
    $pdo = getPDO();
    $id_sala = getIdSala($sala);
    $id_filme = getIdFilme($filme);

    $sql = "insert into sessoes (id_sala, id_filme, data_sessao, hora, idioma) values
    (:id_sala, :id_filme, :data, :hora, :idioma);";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id_sala', $id_sala['id']);
    $resultado->bindParam(':id_filme', $id_filme['id']);
    $resultado->bindParam(':data', $data);
    $resultado->bindParam(':hora', $hora);
    $resultado->bindParam(':idioma', $idioma);

    $resultado->execute();
}

function atualizarSessao($sala, $filme, $data, $hora, $idioma, $id){
    $pdo = getPDO();
    $id_sala = getIdSala($sala);
    $id_filme = getIdFilme($filme);

    $sql = "update sessoes SET id_sala = :sala, id_filme = :filme, data_sessao = :data, hora = :hora, idioma = :idioma
    WHERE id = :id";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':sala', $id_sala['id']);
    $resultado->bindParam(':filme', $id_filme['id']);
    $resultado->bindParam(':data', $data);
    $resultado->bindParam(':hora', $hora);
    $resultado->bindParam(':idioma', $idioma);
    $resultado->bindParam(':id', $id);

    $resultado->execute();
}

if (isset($_POST['filtrar'])) {
    $data = $_REQUEST['data'] ?? date("Y-m-d");
    if ($data != "") {
        $dataQuebrada = explode("-", $data);
        $dataFormatada = $dataQuebrada[2] . '/' . $dataQuebrada[1] . '/' . $dataQuebrada[0];
    }

    $sql = "call sessoesData('$data');";

    if ($data == "") {
        $sql = "select * from view_sessoes";
    }

    $resultado = $pdo->query($sql);

    $sessoes = $resultado->fetchAll(PDO::FETCH_ASSOC);
    $resultado->closeCursor();
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
    $separado = explode(" ", $_POST['acao']);
    $id = end($separado);

    $sql = "delete from sessoes
    WHERE id = :id";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id', $id);

    $resultado->execute();
    $sessoes = listarSessoes($pdo);
}

if (isset($_POST['adicionar'])) {
    $sala = $_POST['sala'];
    $filme = $_POST['filme'];
    $data_sessao = $_POST['data'];
    $hora = $_POST['hora'];
    $idioma = $_POST['idioma'];

    adicionarSessao($sala, $filme, $data_sessao, $hora, $idioma);
    $sessoes = listarSessoes($pdo);
}

if (isset($_POST['atualizar'])) {
    $sala = $_POST['sala'];
    $titulo = $_POST['titulo'];
    $data_sessao = $_POST['data'];
    $hora = $_POST['hora'];
    $idioma = $_POST['idioma'];
    $id = $_POST['id'];

    atualizarSessao($sala, $titulo, $data_sessao, $hora, $idioma, $id);
    $sessoes = listarSessoes($pdo);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="script/teste.js" defer></script>
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
    <section class="filtro">
        <form action="?cidade=<?= $cidadeURL ?>" method="post">
            <input type="date" name="data" id="data" class="inputData input_dark">
            <button class="button" name="filtrar" value="pesquisar">Filtrar</button>
        </form>
        <p class="sessoes"><?php
                            if (isset($data)) {
                                if ($data != "") {
                                    echo "Sessões do dia: " . $dataFormatada;
                                } else {
                                    echo "Todas as sessões";
                                }
                            } else {
                                echo "Todas as sessões";
                            } ?></p>
    </section>

    <?php if (estaAutenticado()) { ?>
        <div class="center"><button class="button">Adicionar sessão</button></div>
    <?php } ?>

    <table class="tabela">
        <thead>
            <tr>
                <th>Sala</th>
                <th>Tipo</th>
                <th>Filme</th>
                <th>Horário</th>
                <th>Áudio</th>
                <th>Gêneros</th>
                <th>Class.</th>
                <?php if (estaAutenticado()) { ?>
                    <th>Excluir</th>
                    <th>Editar</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($sessoes as $sessao) {
                $tituloFilme = $sessao['titulo'] ?>
                <tr>
                    <form action="" method="post">
                        <td>
                            <?= $sessao['sala'] ?>
                        </td>
                        <td>
                            <?= $sessao['tipo']?>
                        </td>
                        <td>
                            <?= $tituloFilme ?>
                        </td>
                        <td>
                            <?= $sessao['hora'] ?>
                        </td>
                        <td>
                            <?= $sessao['idioma'] ?>
                        </td>
                        <td>
                            <?php
                            $sql = "call generoFilme('$tituloFilme')";

                            $resultado = $pdo->query($sql);

                            $generos = $resultado->fetchAll(PDO::FETCH_ASSOC);
                            $resultado->closeCursor();

                            foreach ($generos as $idx => $genero) {
                                if ($idx == count($generos) - 1) {
                                    echo $genero['genero'];
                                } else {
                                    echo $genero['genero'] . ", ";
                                }
                            }
                            ?>
                        </td>
                        <td><span class="classificacao"><?= $sessao['classificacao'] ?></span></td>
                        <?php if (estaAutenticado()) { ?>
                            <td><button class="button red" name="acao" value="excluir <?= $sessao['id'] ?>">Excluir</button></td>
                            <td><button class="button blue" name="acao" value="editar <?= $sessao['id'] ?>">Editar</button></td>
                        <?php } ?>
                    </form>
                </tr>

                <div class="modal modalUpdate" data-id="<?= $sessao['id'] ?>">
                    <div class="modalContent">
                        <p>Editar</p>
                        <form action="" method="post">
                            <div>
                                <label for="sala">Sala: </label>
                                <input type="text" name="sala" value="<?= $sessao['sala'] ?>">
                            </div>
                            <div>
                                <label for="titulo">Título: </label>
                                <input type="text" name="titulo" value="<?= $sessao['titulo'] ?>">
                            </div>
                            <div>
                                <label for="data">Data: </label>
                                <input type="text" name="data" value="<?= $sessao['data_sessao'] ?>">
                            </div>
                            <div>
                                <label for="hora">Horário: </label>
                                <input type="text" name="hora" value="<?= $sessao['hora'] ?>">
                            </div>
                            <div>
                                <label for="idioma">Idioma: </label>
                                <input type="text" name="idioma" value="<?= $sessao['idioma'] ?>">
                                <input type="hidden" name="id" value="<?= $sessao['id'] ?>">
                            </div>
                            <button class="button blue" name="atualizar">Salvar Alterações</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </tbody>
    </table>
</main>

<div class="modal modalCreate">
    <div class="modalContent">
        <p>Adicionar</p>
        <form action="" method="post">
            <div>
                <label for="sala">Sala: </label>
                <input type="text" name="sala" placeholder="Digite a sala (int)">
            </div>
            <div>
                <label for="filme">Filme: </label>
                <input type="text" name="filme" placeholder="Digite o filme (texto)">
            </div>
            <div>
                <label for="data">Data: </label>
                <input type="text" name="data" placeholder="Digite a data (aaaa-mm-dd)">
            </div>
            <div>
                <label for="hora">Hora: </label>
                <input type="text" name="hora" placeholder="Digite a hora (hh:mm:ss)">
            </div>
            <div>
                <label for="idioma">Idioma: </label>
                <input type="text" name="idioma" placeholder="(Dublado, Legendado)">
            </div>
            <button class="button" name="adicionar" value="adicionar">Adicionar</button>
        </form>
    </div>
</div>


</body>

</html>