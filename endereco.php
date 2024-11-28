<?php
require 'config.php';

$cidadeURL = $_GET['cidade'];

$pdo = getPDO();

$sql = "call enderecoCinema('$cidadeURL')";

$resultado = $pdo->query($sql);

$endereco = $resultado->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cineblau</title>
    <link rel="stylesheet" href="css/style.css">
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
            </form>

            <form action="" method="post">
                <input type="text" name="login" id="login" placeholder="Login..." class="input input_light">
                <input type="password" name="password" id="password" placeholder="Password..." class="input input_light">
                <button class="button">Login</button>
            </form>
        </div>

    </header>

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
        <section class="sectionCidades padd">
            <ul class="listaCidades">
                <li><h2>Cidade: <?= $endereco['nome'] ?></h2></li>
                <li><h3>Bairro: <?= $endereco['bairro'] ?></h3></li>
                <li><h4>Logradouro: <?= $endereco['logradouro'] ?>, <?= $endereco['numero'] ?></h4></li>
                <li><p>Complemento: <?= $endereco['complemento'] ?></p></li>
                <li><p>CEP: <?= $endereco['cep'] ?></p></li>
            </ul>
        </section>
    </main>
</body>

</html>