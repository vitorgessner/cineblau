<?php
require 'config.php';
require 'biblioteca_autenticacao.php';

$pdo = getPDO();
$cidadeURL = $_GET['cidade'];
$filmes = listarFilmes($pdo);

function listarFilmes($pdo)
{
    $sql = "CALL infoFilme();";

    $resultado = $pdo->query($sql);

    $filmes = $resultado->fetchAll(PDO::FETCH_ASSOC);
    $resultado->closeCursor();

    return $filmes;
}

function listarElenco($pdo, $id_filme)
{
    $sql = "call elencoFilme($id_filme);";

    $resultado = $pdo->query($sql);

    $atores = $resultado->fetchAll(PDO::FETCH_ASSOC);
    $resultado->closeCursor();

    return $atores;
}

function listarGeneros($pdo, $titulo)
{
    $sql = "call generoFilme('$titulo');";

    $resultado = $pdo->query($sql);

    $generos = $resultado->fetchAll(PDO::FETCH_ASSOC);
    $resultado->closeCursor();

    return $generos;
}

function listarPremiacao($pdo, $titulo)
{
    $sql = "call premiacaoFilme('$titulo');";

    $resultado = $pdo->query($sql);

    $premiacao = $resultado->fetchAll(PDO::FETCH_ASSOC);
    $resultado->closeCursor();

    return $premiacao;
}

function idFilme($pdo, $titulo)
{
    $sql = "CALL idFilme('$titulo');";

    $resultado = $pdo->query($sql);

    $id_filme = $resultado->fetch(PDO::FETCH_ASSOC);
    $resultado->closeCursor();

    return $id_filme['id'];
}

function adicionarFilme($titulo, $sinopse, $estreia, $classificacao, $duracao, $arquivo)
{
    $pdo = getPDO();
    $sql = "insert into filmes (titulo, sinopse, estreia, classificacao, duracao, posterCaminho) values
    (:titulo, :sinopse, :estreia, :classificacao, :duracao, :posterCaminho);";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':titulo', $titulo);
    $resultado->bindParam(':sinopse', $sinopse);
    $resultado->bindParam(':estreia', $estreia);
    $resultado->bindParam(':classificacao', $classificacao);
    $resultado->bindParam(':duracao', $duracao);
    $resultado->bindParam(':posterCaminho', $arquivo);

    $resultado->execute();
};

function adicionarDiretor($id_filme, $diretor)
{
    $pdo = getPDO();
    $sql = "insert into filmes_diretor (id_filme, nome) values
    (:id_filme, :diretor);";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id_filme', $id_filme);
    $resultado->bindParam(':diretor', $diretor);

    $resultado->execute();
}

function editarDiretor($id_diretor, $diretor)
{
    $pdo = getPDO();
    $sql = "update filmes_diretor set nome = :diretor
    where id = :id_diretor;";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id_diretor', $id_diretor);
    $resultado->bindParam(':diretor', $diretor);

    $resultado->execute();
}

function adicionarElenco($id_filme, $ator)
{
    $pdo = getPDO();
    $sql = "insert into filmes_elenco (id_filme, nome_ator) values
    (:id_filme, :ator);";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id_filme', $id_filme);
    $resultado->bindParam(':ator', $ator);

    $resultado->execute();
}

function editarElenco($id_ator, $ator)
{
    $pdo = getPDO();
    $sql = "update filmes_elenco set nome_ator = :ator
    where id = :id_ator;";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id_ator', $id_ator);
    $resultado->bindParam(':ator', $ator);

    $resultado->execute();
}

function adicionarGenero($id_filme, $genero)
{
    $pdo = getPDO();
    $sql = "insert into filmes_genero (id_filme, genero) values
    (:id_filme, :genero);";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id_filme', $id_filme);
    $resultado->bindParam(':genero', $genero);

    $resultado->execute();
}

function editarGenero($id_genero, $genero)
{
    $pdo = getPDO();
    $sql = "update filmes_genero set genero = :genero
    where id = :id_genero;";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id_genero', $id_genero);
    $resultado->bindParam(':genero', $genero);

    $resultado->execute();
}

function adicionarPremiacao($id_filme, $premiacao, $dataFormatada, $categoria)
{
    $pdo = getPDO();
    $sql = "insert into filmes_premiacao (id_filme, nome, data_premiacao, categoria) values
    (:id_filme, :premiacao, :data, :categoria);";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id_filme', $id_filme);
    $resultado->bindParam(':premiacao', $premiacao);
    $resultado->bindParam(':data', $dataFormatada);
    $resultado->bindParam(':categoria', $categoria);

    $resultado->execute();
}

function editarPremiacao($id_premiacao, $premiacao, $data, $categoria)
{
    $pdo = getPDO();
    $sql = "update filmes_premiacao set nome = :premiacao, data_premiacao = :data, categoria = :categoria
    where id = :id_premiacao;";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id_premiacao', $id_premiacao);
    $resultado->bindParam(':premiacao', $premiacao);
    $resultado->bindParam(':data', $data);
    $resultado->bindParam(':categoria', $categoria);

    $resultado->execute();
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

if (isset($_POST['filme'])) {
    if (preg_match("/^excluir/", $_POST['filme'])) {
        $separado = explode(" ", $_POST['filme']);
        $id = end($separado);

        $sql = "delete from Filmes
        WHERE id = :id";
    } else if (preg_match("/^diretorExcluir/", $_POST['filme'])) {
        $separado = explode(" ", $_POST['filme']);
        $id = end($separado);

        $sql = "delete from Filmes_diretor
        WHERE id_filme = :id";
    } else if (preg_match("/^elencoExcluir/", $_POST['filme'])) {
        $separado = explode(" ", $_POST['filme']);
        $id = end($separado);

        $sql = "delete from Filmes_elenco
        WHERE id_filme = :id";
    } else if (preg_match("/^generoExcluir/", $_POST['filme'])) {
        $separado = explode(" ", $_POST['filme']);
        $id = end($separado);

        $sql = "delete from Filmes_genero
        WHERE id_filme = :id";
    } else if (preg_match("/^premiacaoExcluir/", $_POST['filme'])) {
        $separado = explode(" ", $_POST['filme']);
        $id = end($separado);

        $sql = "delete from Filmes_premiacao
        WHERE id_filme = :id";
    }
    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id', $id);

    $resultado->execute();
    header("location: filme.php?cidade='$cidadeURL'");
}

if (isset($_GET['filme'])) {
    $tituloURL = $_GET['filme'];
    $sql = "CALL infoFilmeNome('$tituloURL');";

    $resultado = $pdo->query($sql);

    $filmes = $resultado->fetchAll(PDO::FETCH_ASSOC);
    $resultado->closeCursor();
}

if (isset($_GET['search'])) {
    $pesquisa = $_GET['search'];
    $sql = "CALL pesquisarFilme('$pesquisa');";

    $resultado = $pdo->query($sql);

    $filmes = $resultado->fetchAll(PDO::FETCH_ASSOC);
    $resultado->closeCursor();
}

if (isset($_POST['adicionar'])) {
    $titulo = $_POST['titulo'];
    $diretor = $_POST['diretor'];
    $elenco = $_POST['elenco'];
    $sinopse = $_POST['sinopse'];
    $estreia = $_POST['estreia'];
    $generos = $_POST['generos'];
    $classificacao = $_POST['classificacao'];
    $duracao = $_POST['duracao'];
    $premiacao = $_POST['premiacao'];
    $categoria = $_POST['categoria'];
    $data = $_POST['data_premiacao'];

    if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
        @$nome_original = $_FILES['poster']['name'];


        $temp = explode(".", $nome_original);
        $extensao = end($temp);
        if ($extensao == 'jpg' || $extensao == 'png') {
            $arquivo_destino = uniqid();
            move_uploaded_file($_FILES['poster']['tmp_name'], 'images/' . $arquivo_destino . '.' . $extensao);
            $foto = $arquivo_destino . '.' . $extensao;
        }
    }

    adicionarFilme($titulo, $sinopse, $estreia, $classificacao, $duracao, $foto);

    $id_filme = idFilme($pdo, $titulo);

    if ($diretor != "") {
        adicionarDiretor($id_filme, $diretor);
    }

    if ($elenco != "") {
        $atores = explode(", ", $elenco);
        foreach ($atores as $ator) {
            adicionarElenco($id_filme, $ator);
        }
    }

    if ($generos != "") {
        $generosArr = explode(", ", $generos);
        foreach ($generosArr as $genero) {
            adicionarGenero($id_filme, $genero);
        }
    }

    if ($data !== "") {
        adicionarPremiacao($id_filme, $premiacao, $data, $categoria);
    }


    $filmes = listarFilmes($pdo);
}

if (isset($_POST['adicionarDiretor'])) {
    $diretor = $_POST['diretor'];
    $filme = explode(' ', $_POST['adicionarDiretor']);
    $id_filme = end($filme);

    if ($diretor != '') {
        adicionarDiretor($id_filme, $diretor);
    }
}

if (isset($_POST['editarDiretor'])) {
    $diretores = $_POST['diretor'];
    $ids = $_POST['id'];

    $diretoresId = [];
    for ($i = 0; $i < count($diretores); $i++) {
        $diretoresId[] = [
            'diretor' => $diretores[$i],
            'id' => $ids[$i]
        ];
    }

    for ($i = 0; $i < count($diretores); $i++) {
        if ($diretores[$i] != '') {
            editarDiretor($diretoresId[$i]['id'], $diretoresId[$i]['diretor']);
        }
    }
}

if (isset($_POST['adicionarAtor'])) {
    $ator = $_POST['ator'];
    $filme = explode(' ', $_POST['adicionarAtor']);
    $id_filme = end($filme);

    if ($ator != '') {
        adicionarElenco($id_filme, $ator);
    }
}

if (isset($_POST['editarAtor'])) {
    $atores = $_POST['ator'];
    $ids = $_POST['id'];

    $atoresId = [];
    for ($i = 0; $i < count($atores); $i++) {
        $atoresId[] = [
            'ator' => $atores[$i],
            'id' => $ids[$i]
        ];
    }

    for ($i = 0; $i < count($atores); $i++) {
        if ($atores[$i] != '') {
            editarElenco($atoresId[$i]['id'], $atoresId[$i]['ator']);
        }
    }
}

if (isset($_POST['adicionarGenero'])) {
    $genero = $_POST['genero'];
    $filme = explode(' ', $_POST['adicionarGenero']);
    $id_filme = end($filme);

    if ($genero != '') {
        adicionarGenero($id_filme, $genero);
    }
}

if (isset($_POST['editarGenero'])) {
    $generos = $_POST['genero'];
    $ids = $_POST['id'];

    $generosId = [];
    for ($i = 0; $i < count($generos); $i++) {
        $generosId[] = [
            'genero' => $generos[$i],
            'id' => $ids[$i]
        ];
    }

    for ($i = 0; $i < count($generos); $i++) {
        if ($generos[$i] != '') {
            editarGenero($generosId[$i]['id'], $generosId[$i]['genero']);
        }
    }
}

if (isset($_POST['adicionarPremiacao'])) {
    $premiacao = $_POST['premiacao'];
    $data = $_POST['data'];
    $categoria = $_POST['categoria'];
    $filme = explode(' ', $_POST['adicionarPremiacao']);
    $id_filme = end($filme);

    if ($data != '') {
        adicionarPremiacao($id_filme, $premiacao, $data, $categoria);
    }
}

if (isset($_POST['editarPremiacao'])) {
    $premiacoes = $_POST['premiacao'];
    $datas = $_POST['data'];
    $categorias = $_POST['categoria'];
    $ids = $_POST['id'];

    $premiacoesId = [];
    for ($i = 0; $i < count($premiacoes); $i++) {
        $premiacoesId[] = [
            'premiacao' => $premiacoes[$i],
            'data' => $datas[$i],
            'categoria' => $categorias[$i],
            'id' => $ids[$i]
        ];
    }

    for ($i = 0; $i < count($premiacoes); $i++) {
        if ($premiacoes[$i] != '') {
            editarPremiacao($premiacoesId[$i]['id'], $premiacoesId[$i]['premiacao'], $premiacoesId[$i]['data'], $premiacoesId[$i]['categoria']);
        }
    }
}

if (isset($_POST['atualizar'])) {
    $pdo = getPDO();
    $titulo = $_POST['titulo'];
    $sinopse = $_POST['sinopse'];
    $estreia = $_POST['estreia'];
    $classificacao = $_POST['classificacao'];
    $duracao = $_POST['duracao'];
    $id = $_POST['id'];

    if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
        $nome_original = $_FILES['poster']['name'];


        $temp = explode(".", $nome_original);
        $extensao = end($temp);
        if ($extensao == 'jpg' || $extensao == 'png') {
            $arquivo_destino = uniqid();
            move_uploaded_file($_FILES['poster']['tmp_name'], 'images/' . $arquivo_destino . '.' . $extensao);
            $foto = $arquivo_destino . '.' . $extensao;
        }
    }

    @$estreiaQuebrada = explode("-", $estreia);
    @$estreiaFormatada = $estreiaQuebrada[2] . '-' . $estreiaQuebrada[1] . '-' . $estreiaQuebrada[0];

    $sql = "update filmes SET titulo = :titulo, sinopse = :sinopse, estreia = :estreia, classificacao = :classificacao, duracao = :duracao, posterCaminho = :posterCaminho
    WHERE id = :id";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':titulo', $titulo);
    $resultado->bindParam(':sinopse', $sinopse);
    $resultado->bindParam(':estreia', $estreia);
    $resultado->bindParam(':classificacao', $classificacao);
    $resultado->bindParam(':duracao', $duracao);
    $resultado->bindParam(':posterCaminho', $foto);
    $resultado->bindParam(':id', $id);

    $resultado->execute();

    $filmes = listarFilmes($pdo);

    $enderecos = listarFilmes($pdo);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cineblau</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="script/script.js" defer></script>
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
    <a href="colaboradores.php?cidade=<?= $cidadeURL ?>" class="colaboradores">Colaboradores</a>
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

<?php if (estaAutenticado()) { ?>
    <div class="center">
        <button class="button">Adicionar Filme</button>
    </div>
<?php } ?>

<main>
    <?php foreach ($filmes as $filme) {
        $titulo = $filme['titulo'];
        $atores = listarElenco($pdo, $filme['id']);
        $generos = listarGeneros($pdo, $titulo);
        $premiacao = listarPremiacao($pdo, $titulo);
        $estreia = $filme['estreia'];
        $estreiaQuebrada = explode("-", $estreia);
        $estreiaFormatada = $estreiaQuebrada[2] . '/' . $estreiaQuebrada[1] . '/' . $estreiaQuebrada[0]; ?>
        <form action="" method="post">
            <article class="card_breve">
                <div class="poster card">
                    <a href="" class="img_card"><img src="images/<?= $filme['posterCaminho'] ?>" alt="poster">
                    </a>
                </div>
                <div class="info_breve">
                    <h2>Título: <?= $titulo ?></h2>
                    <?php if (estaAutenticado()) { ?>
                        <div>
                            <button class="button red" name="filme" value="excluir <?= $filme['id'] ?>">Excluir</button>
                            <button class="button blue" name="filme" value="editar <?= $filme['id'] ?>">Editar</button>
                        </div>
                    <?php } ?>
                    <hr>
                    <form action="" method="post">
                        <h3>Diretor: <?php
                        $sql = "call diretorFilme('$titulo');";

                        $resultado = $pdo->query($sql);

                        $diretores = $resultado->fetchAll(PDO::FETCH_ASSOC);
                        $resultado->closeCursor();

                        foreach ($diretores as $idx => $diretor) { ?>
                        <input type="hidden" name="idAtor" value="<?= $diretor['id'] ?>">
                        <?php
                            if ($idx == count($diretores) - 1) {
                                echo $diretor['diretor'];
                            } else {
                                echo $diretor['diretor'] . ", ";
                            }
                            if (isset($diretor)) { ?>

                            <?php }
                                        } ?>
                        </h3>
                        <?php if (estaAutenticado()) { ?>
                            <button class="button red" name="filme" value="diretorExcluir <?= $filme['id'] ?>">Excluir</button>
                            <button class="button blue" name="filme" value="diretorEditar <?= $filme['id'] ?>">Editar</button>
                            <button class="button" name="filme" value="diretorAdicionar <?= $filme['id'] ?>">Adicionar</button>
                        <?php } ?>
                    </form>
                    <hr>
                    <form action="" method="post">
                        <h4>Elenco: <?php
                                    $sql = "call atorFilme('$titulo');";

                                    $resultado = $pdo->query($sql);

                                    $elenco = $resultado->fetchAll(PDO::FETCH_ASSOC);
                                    $resultado->closeCursor();

                                    foreach ($elenco as $idx => $ator) { ?>
                                <input type="hidden" name="idAtor" value="<?= $ator['id'] ?>">
                            <?php
                                        if ($idx == count($elenco) - 1) {
                                            echo $ator['elenco'];
                                        } else {
                                            echo $ator['elenco'] . ", ";
                                        }
                                    } ?>
                        </h4>
                        <?php if (estaAutenticado()) { ?>
                            <button class="button red" name="filme" value="elencoExcluir <?= $filme['id'] ?>">Excluir</button>
                            <button class="button blue" name="filme" value="elencoEditar <?= $filme['id'] ?>">Editar</button>
                            <button class="button" name="filme" value="elencoAdicionar <?= $filme['id'] ?>">Adicionar</button>
                        <?php } ?>
                    </form>

                    <hr>
                    <p>Sinopse: <?= $filme['sinopse'] ?></p>
                    <hr>
                    <p>Estreia: <?= $estreiaFormatada ?> | Classificação: <?= $filme['classificacao'] ?> | Duração: <?= $filme['duracao'] . 'min.' ?></p>
                    <hr>
                    <p>Gêneros: <?php
                                $sql = "call generoFilme('$titulo')";

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
                                ?></p>
                    <?php if (estaAutenticado()) { ?>
                        <button class="button red" name="filme" value="generoExcluir <?= $filme['id'] ?>">Excluir</button>
                        <button class="button blue" name="filme" value="generoEditar <?= $filme['id'] ?>">Editar</button>
                        <button class="button" name="filme" value="generoAdicionar <?= $filme['id'] ?>">Adicionar</button>
                    <?php } ?>
                    <hr>
                    <p>Premiações: <?php
                                    $sql = "call premiacaoFilme('$titulo')";

                                    $resultado = $pdo->query($sql);

                                    $premios = $resultado->fetchAll(PDO::FETCH_ASSOC);
                                    $resultado->closeCursor();

                                    foreach ($premios as $idx => $premio) {
                                        $dataQuebrada = explode("-", $premio['data_premiacao']);
                                        $dataFormatada = $dataQuebrada[2] . '/' . $dataQuebrada[1] . '/' . $dataQuebrada[0];
                                        echo $premio['nome'] . ', ' . $premio['categoria'] . ', ' . $dataFormatada;
                                    } ?></p>
                    <?php if (estaAutenticado()) { ?>
                        <button class="button red" name="filme" value="premiacaoExcluir <?= $filme['id'] ?>">Excluir</button>
                        <button class="button blue" name="filme" value="premiacaoEditar <?= $filme['id'] ?>">Editar</button>
                        <button class="button" name="filme" value="premiacaoAdicionar <?= $filme['id'] ?>">Adicionar</button>
                    <?php } ?>
                </div>
            </article>
        </form>

        <div class="modal modalUpdate" data-id="<?= $filme['id'] ?>">
            <div class="modalContent">
                <p>Editar</p>
                <form action="" method="post" enctype="multipart/form-data">
                    <div>
                        <label for="titulo">Título: </label>
                        <input type="text" name="titulo" value="<?= $filme['titulo'] ?>">
                    </div>
                    <div>
                        <label for="sinopse">Sinopse: </label>
                        <input type="text" name="sinopse" value="<?= $filme['sinopse'] ?>">
                    </div>
                    <div>
                        <label for="estreia">Estreia: </label>
                        <input type="text" name="estreia" value="<?= $filme['estreia'] ?>">
                    </div>
                    <div>
                        <label for="classificacao">Classificação: </label>
                        <input type="text" name="classificacao" value="<?= $filme['classificacao'] ?>">
                    </div>
                    <div>
                        <label for="duracao">Duração: </label>
                        <input type="text" name="duracao" value="<?= $filme['duracao'] ?>">
                    </div>
                    <div>
                        <label for="poster">Poster do filme (2:3): </label>
                        <input type="file" name="poster" value="<?= $foto ?>">
                        <input type="hidden" name="id" value="<?= $filme['id'] ?>">
                    </div>
                    <button class="button blue" name="atualizar">Salvar Alterações</button>
                </form>
            </div>
        </div>
        <div class="modal modalDirector" data-id="<?= $filme['id'] ?>">
            <div class="modalContent">
                <p>Adicionar</p>
                <form action="" method="post">
                    <div>
                        <label for="diretor">Diretor: </label>
                        <input type="text" name="diretor" placeholder="Digite o diretor (texto)">
                    </div>
                    <button class="button" name="adicionarDiretor" value="adicionar <?= $filme['id'] ?>">Adicionar</button>
                </form>
            </div>
        </div>

        <div class="modal modalDirectorUpdate" data-id="<?= $filme['id'] ?>">
            <div class="modalContent">
                <p>Editar</p>
                <form action="" method="post">
                    <div>
                        <label for="diretor">Diretor: </label>
                        <?php foreach ($diretores as $diretor) { ?>
                            <input type="text" name="diretor[]" value="<?= $diretor['diretor'] ?>">
                            <input type="hidden" name="id[]" value="<?= $diretor['id'] ?>">
                        <?php } ?>
                    </div>
                    <button class="button blue" name="editarDiretor" value="atualizar <?= $filme['id'] ?>">Editar</button>
                </form>
            </div>
        </div>

        <div class="modal modalElenco" data-id="<?= $filme['id'] ?>">
            <div class="modalContent">
                <p>Adicionar</p>
                <form action="" method="post">
                    <div>
                        <label for="ator">Ator: </label>
                        <input type="text" name="ator" placeholder="Digite o ator (texto)">
                    </div>
                    <button class="button" name="adicionarAtor" value="adicionar <?= $filme['id'] ?>">Adicionar</button>
                </form>
            </div>
        </div>

        <div class="modal modalElencoUpdate" data-id="<?= $filme['id'] ?>">
            <div class="modalContent">
                <p>Editar</p>
                <form action="" method="post">
                    <div>
                        <label for="ator">Ator: </label>
                        <?php foreach ($atores as $ator) { ?>
                            <input type="text" name="ator[]" value="<?= $ator['nome_ator'] ?>">
                            <input type="hidden" name="id[]" value="<?= $ator['id'] ?>">
                        <?php } ?>
                    </div>
                    <button class="button blue" name="editarAtor" value="atualizar <?= $filme['id'] ?>">Editar</button>
                </form>
            </div>
        </div>

        <div class="modal modalGenero" data-id="<?= $filme['id'] ?>">
            <div class="modalContent">
                <p>Adicionar</p>
                <form action="" method="post">
                    <div>
                        <label for="genero">Gênero: </label>
                        <input type="text" name="genero" placeholder="Digite o genero (texto)">
                    </div>
                    <button class="button" name="adicionarGenero" value="adicionar <?= $filme['id'] ?>">Adicionar</button>
                </form>
            </div>
        </div>

        <div class="modal modalGeneroUpdate" data-id="<?= $filme['id'] ?>">
            <div class="modalContent">
                <p>Editar</p>
                <form action="" method="post">
                    <div>
                        <label for="genero">Gênero: </label>
                        <?php foreach ($generos as $genero) { ?>
                            <input type="text" name="genero[]" value="<?= $genero['genero'] ?>">
                            <input type="hidden" name="id[]" value="<?= $genero['id'] ?>">
                        <?php } ?>
                    </div>
                    <button class="button blue" name="editarGenero" value="atualizar <?= $filme['id'] ?>">Editar</button>
                </form>
            </div>
        </div>

        <div class="modal modalPremiacao" data-id="<?= $filme['id'] ?>">
            <div class="modalContent">
                <p>Adicionar</p>
                <form action="" method="post">
                    <div>
                        <label for="premiacao">Premiação: </label>
                        <input type="text" name="premiacao" placeholder="Digite a premiação (texto)">
                        <input type="text" name="data" placeholder="aaaa-mm-dd">
                        <input type="text" name="categoria" placeholder="Digite a categoria da premiação (texto)">
                    </div>
                    <button class="button" name="adicionarPremiacao" value="adicionar <?= $filme['id'] ?>">Adicionar</button>
                </form>
            </div>
        </div>

        <div class="modal modalPremiacaoUpdate" data-id="<?= $filme['id'] ?>">
            <div class="modalContent">
                <p>Editar</p>
                <form action="" method="post">
                    <div>
                        <label for="premiacao">Premiação: </label>
                        <?php foreach ($premiacao as $premio) { ?>
                            <input type="text" name="premiacao[]" value="<?= $premio['nome'] ?>">
                            <input type="text" name="data[]" value="<?= $premio['data_premiacao'] ?>">
                            <input type="text" name="categoria[]" value="<?= $premio['categoria'] ?>">
                            <input type="hidden" name="id[]" value="<?= $premio['id'] ?>">
                        <?php } ?>
                    </div>
                    <button class="button blue" name="editarPremiacao" value="atualizar <?= $filme['id'] ?>">Editar</button>
                </form>
            </div>
        </div>
    <?php } ?>
</main>

<div class="modal modalCreate">
    <div class="modalContent">
        <p>Adicionar</p>
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label for="titulo">Título*: </label>
                <input type="text" name="titulo" placeholder="Digite o título (texto)">
            </div>
            <div>
                <label for="diretor">Diretor: </label>
                <input type="text" name="diretor" placeholder="Digite o diretor (texto)">
            </div>
            <div>
                <label for="elenco">Elenco: </label>
                <input type="text" name="elenco" placeholder="Digite o elenco (texto)">
            </div>
            <div>
                <label for="sinopse">Sinopse*: </label>
                <input type="text" name="sinopse" placeholder="Digite a sinopse (texto)">
            </div>
            <div>
                <label for="estreia">Estreia*: </label>
                <input type="text" name="estreia" placeholder="aaaa-mm-dd">
            </div>
            <div>
                <label for="classificacao">Classificação*: </label>
                <input type="text" name="classificacao" placeholder="(L, 10, 12, 14, 16, 18)">
            </div>
            <div>
                <label for="duracao">Duração*: </label>
                <input type="text" name="duracao" placeholder="(em minutos)">
            </div>
            <div>
                <label for="generos">Gêneros: </label>
                <input type="text" name="generos" placeholder="Digite os generos (texto)">
            </div>
            <div>
                <label for="premiacao">Premiação: </label>
                <input type="text" name="premiacao" placeholder="Digite a premiação (texto)">
            </div>
            <div>
                <label for="data_premiacao">Data da premiação: </label>
                <input type="text" name="data_premiacao" placeholder="aaaa-mm-dd">
            </div>
            <div>
                <label for="categoria">Categoria da premiação: </label>
                <input type="text" name="categoria" placeholder="Digite a categoria (texto)">
            </div>
            <div>
                <label for="poster">Poster do filme* (2:3): </label>
                <input type="file" name="poster">
            </div>
            <button class="button" name="adicionar" value="adicionar">Adicionar</button>
        </form>
    </div>
</div>
</body>

</html>