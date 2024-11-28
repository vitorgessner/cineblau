<?php
require 'config.php';

$pdo = getPDO();

$sql = "CALL emBreve();";

$resultado = $pdo->query($sql);

$emBreve = $resultado->fetchAll(PDO::FETCH_ASSOC);
$resultado->closeCursor();
$cidadeURL = $_GET['cidade'];

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
        <li><a href="cartaz.php?cidade=<?= $cidadeURL?>">Em cartaz</a></li>
            <li><a href="sessoes.php?cidade=<?= $cidadeURL?>">Sessões</a></li>
            <li><a href="salas.php?cidade=<?= $cidadeURL?>">Salas</a></li>
            <li><a href="emBreve.php?cidade=<?= $cidadeURL?>">Em breve</a></li>
            <li><a href="reprises.php?cidade=<?= $cidadeURL?>">Reprises</a></li>
            <li><a href="endereco.php?cidade=<?= $cidadeURL?>">Endereço</a></li>
        </ul>
    </aside>

    <main>
        <?php foreach($emBreve as $caminho) {
            $titulo = $caminho['titulo'];
            $estreia = $caminho['estreia'];
            $estreiaQuebrada = explode("-", $estreia);
            $estreiaFormatada = $estreiaQuebrada[2] . '/' . $estreiaQuebrada[1] . '/' . $estreiaQuebrada[0];?>
        <article class="card_breve">
            <div class="poster card">
                <a href="" class="img_card"><img src="images/<?= $caminho['posterCaminho']?>" alt="poster">
            <div class="teste">+</div></a>
            </div>
            <div class="info_breve">
                <h2>Título: <?= $titulo?></h2>
                <hr>
                <h3>Diretor: <?= $caminho['diretor']?></h3>
                <hr>
                <h4>Elenco: <?php 
                
                $sql = "call atorFilme('$titulo');";

                $resultado = $pdo->query($sql);
                
                $elenco = $resultado->fetchAll(PDO::FETCH_ASSOC);
                $resultado->closeCursor();
                
                foreach($elenco as $idx => $ator){
                    if ($idx == count($elenco) - 1) {
                        echo $ator['elenco'];
                    } else {
                        echo $ator['elenco'] . ", ";
                    }
                } ?></h4>
                <hr>
                <p>Sinopse: <?= $caminho['sinopse']?></p>
                <hr>
                <p>Estreia: <?= $estreiaFormatada?></p>
            </div>
        </article>
        <?php }?>
    </main>
</body>

</html>