<?php
require 'config.php';
require_once 'biblioteca_autenticacao.php';

$pdo = getPDO();
$cidades = listarCidades($pdo);

function listarCidades($pdo)
{
    $sql = "select * FROM viewCidades;";

    $resultado = $pdo->query($sql);

    $cidades = $resultado->fetchAll(PDO::FETCH_ASSOC);
    $resultado->closeCursor();

    return $cidades;
}
// $cidadeURL = $_GET['cidade'];

$sql = "select c.*, ce.email FROM colaboradores as c
    JOIN colaboradores_email as ce on c.id = ce.id_colaborador
    WHERE c.funcao = 'Gerente';";

$resultado = $pdo->query($sql);

$adm = $resultado->fetch(PDO::FETCH_ASSOC);
$resultado->closeCursor();

function adicionarCidade($nome, $estado)
{
    $pdo = getPDO();

    $sql = "insert into cidades (nome, estado) values
    (:nome, :estado);";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':nome', $nome);
    $resultado->bindParam(':estado', $estado);

    $resultado->execute();
}

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

    $sql = "delete from cidades
    WHERE id = :id";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':id', $id);

    $resultado->execute();
    $cidades = listarCidades($pdo);
}

if (isset($_POST['adicionar'])) {
    $nome = $_POST['nome'];
    $estado = $_POST['estado'];

    adicionarCidade($nome, $estado);
    $cidades = listarCidades($pdo);
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
        <li><a href="">Em cartaz</a></li>
        <li><a href="">Sessões</a></li>
        <li><a href="">Salas</a></li>
        <li><a href="">Em breve</a></li>
        <li><a href="">Reprises</a></li>
        <li><a href="">Endereço</a></li>
    </ul>
</aside>

<main>
    <section class="sectionCidades">
        <div class="h2">
            <h2>Escolha uma cidade:</h2>
        </div>
        <ul class="listaCidades">
            <?php foreach ($cidades as $cidade) { ?>
                <form action="" method="post">
                <li><a href="cartaz.php?cidade=<?= $cidade['nome'] ?>"><?= $cidade['nome'] ?></a>
                <?php if (estaAutenticado()) { ?>
                        <div>
                            <button class="button red" name="acao" value="excluir <?= $cidade['id'] ?>">Excluir</button>
                            <button class="button blue" name="acao" value="editar <?= $cidade['id'] ?>">Editar</button>
                        </div>
                    <?php } ?>
            </li>
                </form>
            <?php } ?>
        </ul>
    </section>

    <?php if (estaAutenticado()) { ?>
        <div class="center"><button class="button">Adicionar cidade</button></div>
    <?php } ?>
</main>

<div class="modal">
    <div class="modalContent">
        <p>Adicionar</p>
        <form action="" method="post">
            <div>
                <label for="nome">nome: </label>
                <input type="text" name="nome" placeholder="Digite o nome (texto)">
            </div>
            <div>
                <label for="estado">Estado: </label>
                <input type="text" name="estado" placeholder="Digite o Estado (texto)">
            </div>
            <button class="button" name="adicionar" value="adicionar">Adicionar</button>
        </form>
    </div>
</div>
</body>

</html>