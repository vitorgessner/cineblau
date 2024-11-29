<?php
require 'config.php';
require_once 'biblioteca_autenticacao.php';

$pdo = getPDO();
$sql = "CALL emCartaz();";
$resultado = $pdo->query($sql);
$emCartaz = $resultado->fetchAll(PDO::FETCH_ASSOC);
$resultado->closeCursor();

$cidadeURL = $_GET['cidade'];

if (isset($_GET['search']) && isset($_GET['cidade'])){
    $pesquisa = $_GET['search'];
    $cidadeURL = $_GET['cidade'];

    if ($pesquisa == ""){
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

if (isset($_GET['logoff'])){
    logoff();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/logoCinebg.png">
    <script src="script/carrossel.js" defer></script>
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

    <main>
        <div class="carrossel">
            <?php foreach ($emCartaz as $caminho) { ?>
                <article class="card cardInfo">
                    <a href="filme.php?cidade=<?=$cidadeURL?>&filme=<?=$caminho['titulo']?>" class="img_card"><img src="images/<?= $caminho['posterCaminho'] ?>" alt="">
                        <div class="teste">+</div>
                    </a>
                    <a href="#" class="a_card">Sessões</a>
                </article>
            <?php } ?>
        </div>

        <div class="arrow right">&#10095;</div>
        <div class="arrow left">&#10094;</div>
    </main>
</body>

</html>