<?php 
    require 'config.php';

    $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

    $pdo = new PDO($dsn, $user, $password);

    if ($_POST){
        $titulo = $_POST['titulo'];
        $sinopse = $_POST['sinopse'];
        $estreia = $_POST['estreia'];
        $classificacao = $_POST['classificacao'];
        $duracao = $_POST['duracao'];

        $sql = "insert into Filmes (titulo, sinopse, estreia, classificacao, duracao)
        values (:titulo, :sinopse, :estreia, :classificacao, :duracao)";

        $resultado = $pdo->prepare($sql);

        $resultado->bindParam(':titulo', $titulo);
        $resultado->bindParam(':sinopse', $sinopse);
        $resultado->bindParam(':estreia', $estreia);
        $resultado->bindParam(':classificacao', $classificacao);
        $resultado->bindParam(':duracao', $duracao);

        $resultado->execute();
    }

    if ($pdo) {
        echo "Conectado em $db com sucesso!";
    }

    $sql = "select * from Filmes";

    $resultado = $pdo->query($sql);
    
    $filmes = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Filmes</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="titulo">
        <input type="text" name="sinopse">
        <input type="text" name="estreia" placeholder="aaaa-mm-dd">
        <input type="text" name="classificacao">
        <input type="text" name="duracao" placeholder="em minutos">
        <button>Adicionar filme</button>
    </form>

    <table class="tabela_filmes white">
        <thead>
            <tr>
                <th>Id</th>
                <th>Título</th>
                <th>Sinopse</th>
                <th>Estreia</th>
                <th>Classificação</th>
                <th>Duração</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($filmes as $filme) {?>
            <tr>
                <td><?= $filme['id']?></td>
                <td><?= $filme['titulo']?></td>
                <td><?= $filme['sinopse']?></td>
                <td><?= $filme['estreia']?></td>
                <td><?= $filme['classificacao']?></td>
                <td><?= $filme['duracao']?> min</td>
            </tr> <?php }?>
        </tbody>
    </table>
</body>
</html>