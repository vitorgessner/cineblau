<?php 
require "config.php";

$pdo = getPDO();

$sql = "Select tipo, descricao, capacidade, preco from Tipo_sala;";

$resultado = $pdo->query($sql);

$salas = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Cineblau</title>
</head>

<body>
    <header class="main_header">
        <a class="identidade" href="index.html">
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
            <li><a href="index.html">Em cartaz</a></li>
            <li><a href="sessoes.php">Sessões</a></li>
            <li><a href="salas.php">Salas</a></li>
            <li><a href="emBreve.php">Em breve</a></li>
            <li><a href="reprises.php">Reprises</a></li>
            <li><a href="contato.php">Contato</a></li>
        </ul>
    </aside>

    <main class="salas">
        <?php foreach($salas as $sala) {?>
        <article class="card_salas">
            <h2 class="card_title"><?= $sala['tipo']?></h2>
            <div class="card_info">
                <p><?= $sala['descricao']?></p>
                <hr>
                <p>Capacidade: <?= $sala['capacidade']?> assentos</p>
                <hr>
                <p>Preço: R$<?= $sala['preco']?></p>
            </div>
        </article>
        <?php }?>
        </div>

    </main>
</body>

</html>