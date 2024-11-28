<?php 
require 'config.php';

$pdo = getPDO();

$sql = "select * FROM viewCidades;";

$resultado = $pdo->query($sql);

$cidades = $resultado->fetchAll(PDO::FETCH_ASSOC);
$resultado->closeCursor();
// $cidadeURL = $_GET['cidade'];

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
                <?php foreach($cidades as $cidade) {?>
                <li><a href="cartaz.php?cidade=<?=$cidade['nome']?>"><?= $cidade['nome']?></a></li>
                <?php }?>
            </ul>
        </section>
    </main>
</body>
</html>