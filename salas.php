<?php
require "config.php";
require_once 'biblioteca_autenticacao.php';

$pdo = getPDO();
$salas = listarSalas($pdo);

$cidadeURL = $_GET['cidade'];

function listarSalas($pdo)
{
    $sql = "select id, tipo, descricao, capacidade, preco from Tipo_sala;";

    $resultado = $pdo->query($sql);

    $salas = $resultado->fetchAll(PDO::FETCH_ASSOC);
    $resultado->closeCursor();

    return $salas;
}

function adicionarSala($tipo, $preco, $capacidade, $descricao)
{
    $pdo = getPDO();
    $sql = "insert into tipo_sala (tipo, preco, capacidade, descricao) values
    (:tipo, :preco, :capacidade, :descricao);";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':tipo', $tipo);
    $resultado->bindParam(':preco', $preco);
    $resultado->bindParam(':capacidade', $capacidade);
    $resultado->bindParam(':descricao', $descricao);

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
    var_dump($_POST['acao']);
    $pattern = "/^excluir/m";
    if (preg_match($pattern, $_POST['acao'])) {
        $separado = explode(" ", $_POST['acao']);
        $id = end($separado);

        $sql = "delete from Tipo_sala
        WHERE id = :id";

        $resultado = $pdo->prepare($sql);
        $resultado->bindParam(':id', $id);

        $resultado->execute();
        $salas = listarSalas($pdo);
    } else {
    }
}

if (isset($_POST['atualizar'])) {
    $pdo = getPDO();
    $tipo = $_POST['tipo'];
    $preco = $_POST['preco'];
    $capacidade = $_POST['capacidade'];
    $descricao = $_POST['descricao'];
    $id = $_POST['id'];

    $sql = "update Tipo_sala SET tipo = :tipo, preco = :preco, capacidade = :capacidade, descricao = :descricao
    WHERE id = :id";

    $resultado = $pdo->prepare($sql);
    $resultado->bindParam(':tipo', $tipo);
    $resultado->bindParam(':preco', $preco);
    $resultado->bindParam(':capacidade', $capacidade);
    $resultado->bindParam(':descricao', $descricao);
    $resultado->bindParam(':id', $id);

    $resultado->execute();
    $salas = listarSalas($pdo);
}

if (isset($_GET['search']) && isset($_GET['cidade'])) {
    $pesquisa = $_GET['search'];
    $cidadeURL = $_GET['cidade'];

    if ($pesquisa == "") {
        $pesquisa = '.';
    }
    header("location: filme.php?cidade=$cidadeURL&search=$pesquisa");
}

if (isset($_POST['adicionar'])) {
    $tipo = $_POST['tipo'];
    $preco = $_POST['preco'];
    $capacidade = $_POST['capacidade'];
    $descricao = $_POST['descricao'];

    adicionarSala($tipo, $preco, $capacidade, $descricao);
    $salas = listarSalas($pdo);
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
<main class="salas">
    <?php foreach ($salas as $sala) { ?>
        <article class="card_salas">
            <form action="" method="post">
                <h2 class="card_title"><?= $sala['tipo'] ?></h2>
                <div class="card_info">
                    <p><?= $sala['descricao'] ?></p>
                    <hr>
                    <p>Capacidade: <?= $sala['capacidade'] ?> assentos</p>
                    <hr>
                    <p>Preço: R$<?= $sala['preco'] ?></p>
                    <?php if (estaAutenticado()) { ?>
                        <div>
                            <button class="button red" name="acao" value="excluir <?= $sala['id'] ?>">Excluir</button>
                            <button class="button blue" name="acao" value="editar <?= $sala['id'] ?>">Editar</button>
                        </div>
                    <?php } ?>
                </div>
            </form>
        </article>
        <div class="modal modalUpdate" data-id="<?= $sala['id'] ?>">
            <div class="modalContent">
                <p>Editar</p>
                <form action="" method="post">
                    <div>
                        <label for="tipo">Tipo: </label>
                        <input type="text" name="tipo" value="<?= $sala['tipo'] ?>">
                    </div>
                    <div>
                        <label for="preco">Preço: </label>
                        <input type="text" name="preco" value="<?= $sala['preco'] ?>">
                    </div>
                    <div>
                        <label for="capacidade">Capacidade: </label>
                        <input type="text" name="capacidade" value="<?= $sala['capacidade'] ?>">
                    </div>
                    <div>
                        <label for="descricao">Descrição: </label>
                        <input type="text" name="descricao" value="<?= $sala['descricao'] ?>">
                        <input type="hidden" name="id" value="<?=$sala['id']?>">
                    </div>
                    <button class="button blue" name="atualizar">Salvar Alterações</button>
                </form>
            </div>
        </div>
    <?php } ?>
    </div>

    <div class="modal modalCreate">
        <div class="modalContent">
            <p>Adicionar</p>
            <form action="" method="post">
                <div>
                    <label for="tipo">Tipo: </label>
                    <input type="text" name="tipo" placeholder="Digite o tipo (texto)">
                </div>
                <div>
                    <label for="preco">Preço: </label>
                    <input type="text" name="preco" placeholder="Digite o preço (float)">
                </div>
                <div>
                    <label for="capacidade">Capacidade: </label>
                    <input type="text" name="capacidade" placeholder="Digite a capacidade (int)">
                </div>
                <div>
                    <label for="descricao">Descrição: </label>
                    <input type="text" name="descricao" placeholder="Digite a descrição (texto)">
                </div>
                <button class="button" name="adicionar" value="adicionar">Adicionar</button>
            </form>
        </div>
    </div>

</main>
</body>

</html>