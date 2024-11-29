<?php
require 'config.php';
require_once 'biblioteca_autenticacao.php';

$pdo = getPDO();
$cidadeURL = $_GET['cidade'];
$enderecos = listarEndereco($pdo, $cidadeURL);


function listarEndereco($pdo, $cidadeURL){
$sql = "call enderecoCinema('$cidadeURL');";

$resultado = $pdo->query($sql);

$enderecos = $resultado->fetchAll(PDO::FETCH_ASSOC);
$resultado->closeCursor();

    return $enderecos;
}

function idCidade($cidadeURL){
    $pdo = getPDO();

    $sql = "call idCidade('$cidadeURL');";

    $resultado = $pdo->query($sql);

    $idCidade = $resultado->fetch(PDO::FETCH_ASSOC);
    $resultado->closeCursor();

    return $idCidade['id'];
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

if (isset($_POST['logoff'])){
    logoff();
}

if (isset($_POST['acao'])){
    $separado = explode(" ", $_POST['acao']);
    $id = end($separado);
    
    $sql = "delete from Cinema_endereco
    WHERE id = :id";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id', $id);

    $resultado->execute();
    header('location: index.php');
}

if (isset($_GET['search']) && isset($_GET['cidade'])){
    $pesquisa = $_GET['search'];
    $cidadeURL = $_GET['cidade'];

    if ($pesquisa == ""){
        $pesquisa = '.';
    }
    header("location: filme.php?cidade=$cidadeURL&search=$pesquisa");
}

if (isset($_POST['adicionar'])){
    $bairro = $_POST['bairro'];
    $logradouro = $_POST['logradouro'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $cep = $_POST['cep'];

    if ($complemento == ""){
        $complemento = null;
    }

    $id_cidade = idCidade($cidadeURL);

    $sql = "insert into cinema_endereco (id_cidade, bairro, logradouro, numero, complemento, cep) values
    ($id_cidade, '$bairro', '$logradouro', $numero, '$complemento', '$cep');";

    $resultado = $pdo->prepare($sql);

    $resultado->execute();
    $enderecos = listarEndereco($pdo, $cidadeURL);
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
                    <input type="hidden" name="cidade" value="<?=$cidadeURL?>">
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
    <div class="center"><button class="button" name="acao" value="adicionar">Adicionar sala</button></div>
<?php } ?>

    <main>
        <?php foreach($enderecos as $endereco) {?>
        <section class="sectionCidades padd">
            <form action="" method="post">
            <ul class="listaCidades">
                <li><h2>Cidade: <?= $endereco['nome'] ?></h2></li>
                <li><h3>Bairro: <?= $endereco['bairro'] ?></h3></li>
                <li><h4>Logradouro: <?= $endereco['logradouro'] ?>, <?= $endereco['numero'] ?></h4></li>
                <li><p>Complemento: <?= $endereco['complemento'] ?></p></li>
                <li><p>CEP: <?= $endereco['cep'] ?></p></li>
                <li><?php if (estaAutenticado()) {?>
                    <div>
                        <button class="button red" name="acao" value="excluir <?= $endereco['id']?>">Excluir</button>
                        <button class="button blue" name="acao" value="editar <?= $endereco['id']?>">Editar</button>
                    </div>
                    <?php }?></li>
            </ul>
            </form>
        </section>
        <?php }?>
    </main>

    <div class="modal">
        <div class="modalContent">
            <p>Adicionar</p>
            <form action="" method="post">
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
                    <input type="text" name="cep" placeholder="Digite o CEP (00000-000)">
                </div>
                <button class="button" name="adicionar" value="adicionar">Adicionar</button>
            </form>
        </div>
    </div>
</body>

</html>