<?php
require 'config.php';

$pdo = getPDO();

$sql = "CALL emCartaz();";

$resultado = $pdo->query($sql);

$emCartaz = $resultado->fetchAll(PDO::FETCH_ASSOC);

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
            <li><a href="index.php">Em cartaz</a></li>
            <li><a href="sessoes.php">Sessões</a></li>
            <li><a href="salas.php">Salas</a></li>
            <li><a href="emBreve.php">Em breve</a></li>
            <li><a href="reprises.php">Reprises</a></li>
            <li><a href="contato.php">Contato</a></li>
        </ul>
    </aside>

    <main>
        <div class="carrossel">
            <?php foreach ($emCartaz as $caminho) { ?>
                <article class="card">
                    <a href="" class="img_card"><img src="images/<?= $caminho['posterCaminho'] ?>" alt="">
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