<?php

require 'config.php';

$cidadeURL = $_GET['cidade'];

$pdo = getPDO();

$sql = "select * from view_sessoes";

$resultado = $pdo->query($sql);

$sessoes = $resultado->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
    $data = $_REQUEST['data'] ?? date("Y-m-d");
    if ($data != ""){
        $dataQuebrada = explode("-", $data);
        $dataFormatada = $dataQuebrada[2] . '/' . $dataQuebrada[1] . '/' . $dataQuebrada[0];
    }

    $sql = "call sessoesData('$data');";

    if ($data == "") {
        $sql = "select * from view_sessoes";
    }

    $resultado = $pdo->query($sql);

    $sessoes = $resultado->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="script/teste.js" defer></script>
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
            <li><a href="cartaz.php?cidade=<?= $cidadeURL ?>">Em cartaz</a></li>
            <li><a href="sessoes.php?cidade=<?= $cidadeURL ?>">Sessões</a></li>
            <li><a href="salas.php?cidade=<?= $cidadeURL ?>">Salas</a></li>
            <li><a href="emBreve.php?cidade=<?= $cidadeURL ?>">Em breve</a></li>
            <li><a href="reprises.php?cidade=<?= $cidadeURL ?>">Reprises</a></li>
            <li><a href="endereco.php?cidade=<?= $cidadeURL ?>">Endereço</a></li>
        </ul>
    </aside>

    <main>
        <section class="filtro">
            <form action="?cidade=<?= $cidadeURL ?>" method="post">
                <input type="date" name="data" id="data" class="inputData input_dark">
                <button class="button">Filtrar</button>
            </form>
            <p class="sessoes"><?php 
            if (isset($data)) {
                if ($data != "") {
                    echo "Sessões do dia: " . $dataFormatada;
                }
            } else {
                echo "Todas as sessões";   
            }?></p>
        </section>

        <table class="tabela">
            <thead>
                <tr>
                    <th>Sala</th>
                    <th>Filme</th>
                    <th>Horário</th>
                    <th>Áudio</th>
                    <th>Gêneros</th>
                    <th>Class.</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($sessoes as $sessao) {
                    $tituloFilme = $sessao['titulo'] ?>
                    <tr>
                        <td><?= $sessao['sala'] ?></td>
                        <td><?= $tituloFilme ?></td>
                        <td><?= $sessao['hora'] ?></td>
                        <td><?= $sessao['idioma'] ?></td>
                        <td><?php
                            $sql = "call generoFilme('$tituloFilme')";

                            $resultado = $pdo->query($sql);

                            $generos = $resultado->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($generos as $idx => $genero) {
                                if ($idx == count($generos) - 1) {
                                    echo $genero['genero'];
                                } else {
                                    echo $genero['genero'] . ", ";
                                }
                            } ?></td>
                        <td><span class="classificacao"><?= $sessao['classificacao'] ?></span></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
</body>

</html>