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

    $sql = "delete from Filmes
    WHERE id = :id";

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
    $data = $_POST['data'];

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

    $estreiaQuebrada = explode("/", $estreia);
    $estreiaFormatada = $estreiaQuebrada[2] . '-' . $estreiaQuebrada[1] . '-' . $estreiaQuebrada[0];

    adicionarFilme($titulo, $sinopse, $estreiaFormatada, $classificacao, $duracao, $foto);

    $id_filme = idFilme($pdo, $titulo);

    adicionarDiretor($id_filme, $diretor);

    $atores = explode(", ", $elenco);
    foreach ($atores as $ator) {
        adicionarElenco($id_filme, $ator);
    }

    $generosArr = explode(", ", $generos);
    foreach ($generosArr as $genero) {
        adicionarGenero($id_filme, $genero);
    }

    $dataQuebrada = explode("/", $data);
    $dataFormatada = $dataQuebrada[2] . '-' . $dataQuebrada[1] . '-' . $dataQuebrada[0];

    adicionarPremiacao($id_filme, $premiacao, $dataFormatada, $categoria);

    $filmes = listarFilmes($pdo);
}

if (isset($_POST['atualizar'])) {
    $pdo = getPDO();
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
    $data_premiacao = $_POST['data'];
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

    $estreiaQuebrada = explode("-", $estreia);
    $estreiaFormatada = $estreiaQuebrada[2] . '-' . $estreiaQuebrada[1] . '-' . $estreiaQuebrada[0];

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

    // $sql = "update filmes_diretor SET nome = :diretor
    // WHERE id_filme = :id;";

    // $resultado = $pdo->prepare($sql);
    // $resultado->bindParam(':diretor', $diretor);
    // $resultado->bindParam(':id', $id);

    // $resultado->execute();

    // $atores = explode(", ", $elenco);
    // foreach ($atores as $ator) {
    //     $sql = "update filmes_elenco SET nome_ator = :ator
    //     WHERE id_filme = :id;";

    //     $resultado = $pdo->prepare($sql);
    //     $resultado->bindParam(':ator', $ator);
    //     $resultado->bindParam(':id', $id);

    //     $resultado->execute();
    // }

    // $generosArr = explode(", ", $generos);
    // foreach ($generosArr as $genero) {
    //     $sql = "update filmes_genero SET genero = :genero
    //     WHERE id_filme = :id;";

    //     $resultado = $pdo->prepare($sql);
    //     $resultado->bindParam(':genero', $genero);
    //     $resultado->bindParam(':id', $id);

    //     $resultado->execute();
    // }

    // $sql = "update filmes_premiacao SET nome = :premio, data_premiacao = :data_premiacao, categoria = :categoria
    // WHERE id_filme = :id;";

    // $resultado = $pdo->prepare($sql);
    // $resultado->bindParam(':premio', $premiacao);
    // $resultado->bindParam(':data_premiacao', $data_premiacao);
    // $resultado->bindParam(':categoria', $categoria);
    // $resultado->bindParam(':id', $id);

    // $resultado->execute();

    // $enderecos = listarFilmes($pdo);
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
    <a href="colaboradores.php" class="colaboradores">Colaboradores</a>
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
                    <hr>
                    <h3>Diretor: <?= $filme['diretor'] ?></h3>
                    <hr>
                    <h4>Elenco: <?php
                                $sql = "call atorFilme('$titulo');";

                                $resultado = $pdo->query($sql);

                                $elenco = $resultado->fetchAll(PDO::FETCH_ASSOC);
                                $resultado->closeCursor();

                                foreach ($elenco as $idx => $ator) {
                                    if ($idx == count($elenco) - 1) {
                                        echo $ator['elenco'];
                                    } else {
                                        echo $ator['elenco'] . ", ";
                                    }
                                } ?></h4>
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
                    <hr>
                    <?php
                    $sql = "call premiacaoFilme('$titulo')";

                    $resultado = $pdo->query($sql);

                    $premios = $resultado->fetchAll(PDO::FETCH_ASSOC);
                    $resultado->closeCursor();

                    foreach ($premios as $idx => $premio) {
                        echo '<p>Premiações: ' . $premio['nome'] . ', ' . $premio['categoria'] . ', ' . $premio['data_premiacao'] . "</p>";
                    }
                    ?>
                    <?php if (estaAutenticado()) { ?>
                        <div>
                            <button class="button red" name="acao" value="excluir <?= $filme['id'] ?>">Excluir</button>
                            <button class="button blue" name="acao" value="editar <?= $filme['id'] ?>">Editar</button>
                        </div>
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
    <?php } ?>
</main>

<div class="modal modalCreate">
    <div class="modalContent">
        <p>Adicionar</p>
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label for="titulo">Título: </label>
                <input type="text" name="titulo" placeholder="Digite o título (texto)">
            </div>
            <div>
                <label for="sinopse">Sinopse: </label>
                <input type="text" name="sinopse" placeholder="Digite a sinopse (texto)">
            </div>
            <div>
                <label for="estreia">Estreia: </label>
                <input type="text" name="estreia" placeholder="(dd/mm/aaaa)">
            </div>
            <div>
                <label for="classificacao">Classificação: </label>
                <input type="text" name="classificacao" placeholder="(L, 10, 12, 14, 16, 18)">
            </div>
            <div>
                <label for="duracao">Duração: </label>
                <input type="text" name="duracao" placeholder="(em minutos)">
            </div>
            <div>
                <label for="poster">Poster do filme (2:3): </label>
                <input type="file" name="poster">
            </div>
            <button class="button" name="adicionar" value="adicionar">Adicionar</button>
        </form>
    </div>
</div>
</body>

</html>