<!DOCTYPE html>

<head>

    <link rel="stylesheet" href="css/materialize.css">
    <link rel="stylesheet" href="css/main.css">

    <!--Conexão externa, precisa de internet_-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img/logoAzulClaroDegrade.png" type="image/png">

</head>

<body>


    <?php

    require_once 'navbar.inc.php';
    $idCategoria = $_GET['idCategoria'];
    $categorias = "SELECT * FROM noticias WHERE idCategoria = $idCategoria";

    $noticia = "SELECT noticias.idUsuario, usuario.nomeUsuario, noticias.idCategoria, categoria.nomeCategoria, idNoticia, tituloNoticia, conteudoNoticia, dataNoticia
        FROM ((noticias
        INNER JOIN usuario ON usuario.idUsuario = noticias.idUsuario)
        INNER JOIN categoria ON categoria.idCategoria = noticias.idCategoria)
        WHERE noticias.idCategoria = $idCategoria
        ORDER BY dataNoticia DESC";
    $noticiaExec = mysqli_query($conexao, $noticia);

    while ($noticias = mysqli_fetch_array($noticiaExec)) {
        ?>
        <div class="container">
            <div class="card grey lighten-5">
                <div class="card-content black-text">
                    <span class="card-title"><?php echo "<h4>$noticias[tituloNoticia]</h4>" ?></span>
                    <p><?php echo "$noticias[conteudoNoticia]" ?></p>
                </div>
                <div class="card-action">
                    <div class="row">

                        <div class="left-align col s6">
                            <div class="left-align col s12">
                                <?php
                                    $data = "$noticias[dataNoticia]";
                                    echo "Publicada em $data" ?>
                            </div>
                            <div class="left-align col s12">
                                <?php
                                    echo "<a href='noticia.php?idNoticia=$noticias[idNoticia]'
                                            ><i class='material-icons'>chat</i></a>";
                                    ?>
                            </div>
                        </div>

                        <div class="right-align col s6">
                            <div class="right-align col s12">
                                Escrito por: <?php echo "$noticias[nomeUsuario]" ?>
                            </div>
                            <div class="right-align col s12">
                                <?php echo utf8_encode("$noticias[nomeCategoria]") ?>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        </div>
</body>

</html>