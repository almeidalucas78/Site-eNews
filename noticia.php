<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/noticia.css">

</head>

<body>
    <?php

    require_once 'navbar.inc.php';
    require_once 'conexao.php';

    $idNoticia = $_GET['idNoticia'];

    $noticia = "SELECT noticias.idUsuario, usuario.nomeUsuario, noticias.idCategoria, categoria.nomeCategoria, tituloNoticia, conteudoNoticia, dataNoticia
        FROM ((noticias
        INNER JOIN usuario ON usuario.idUsuario = noticias.idUsuario)
        INNER JOIN categoria ON categoria.idCategoria = noticias.idCategoria) WHERE idNoticia = '$idNoticia'";
    $noticiaExec = mysqli_query($conexao, $noticia);


    while ($noticias = mysqli_fetch_array($noticiaExec)) {
        ?>
        <div class="container">
            <div class="card grey lighten-5">
                <div class="card-content black-text">
                    <span class="card-title"><?php echo "<h4>$noticias[tituloNoticia]</h4>" ?></span>
                    <p>
                        <?php echo "$noticias[conteudoNoticia]" ?>
                    </p>
                </div>
                <div class="card-action">
                    <div class="row">

                        <div class="left-align col s6">
                            <div class="left-align col s12">
                                <?php
                                    $data = "$noticias[dataNoticia]";
                                    echo "Publicada em $data" ?>
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
        <div class="row">
            <div class="card blue lighten-5">
                <div class="card-content white-text">
                    <?php if (isset($_SESSION['logadoOk']) == 1) {
                        ?>
                        <span class="card-title black-text">Publicar um comentário como <?php echo $_SESSION['dadosUser']['nomeUsuario'] ?> ?</span>
                        <p>
                            <form action="#" method="post">
                                <textarea name="conteudocomentario"></textarea>
                                <style>
                                    textarea {
                                        resize: none;
                                        height: 100px;
                                    }
                                </style>
                                <input type="submit" value="Comentar" name="comentario" class="btn btnComentar">
                            </form>
                        <?php
                        }

                        if (isset($_POST['comentario'])) {
                            $comentario = $_POST['conteudocomentario'];
                            if (strlen($comentario) > 250) {
                                echo "<script>alert('Comentário muito grande');</script>";
                                exit;
                            } else {
                                $idUsuario = $_SESSION['dadosUser']['idUsuario'];
                                $foto = $_SESSION['dadosUser'][7];
                                // $comentarioBanco = "INSERT INTO comentario (comentario, dataComentario, idNoticia, idUsuario, foto)
                                // VALUES ('$comentario', '13/09/2019', '$idNoticia', '$idUsuario', '$foto')";

                                $comentarioBanco = "INSERT INTO `esportnews`.`comentario` (`comentario`, `dataComentario`, `idNoticia`, `idUsuario`, `foto`) VALUES ('$comentario', '13/09/2019', '$idNoticia', '$idUsuario', '$foto')";

                                $comentarioBancoExec = mysqli_query($conexao, $comentarioBanco);
                            }
                        }

                    $listaComentario = "SELECT *, comentario.idUsuario, usuario.nomeUsuario
                    FROM comentario 
                    INNER JOIN usuario ON usuario.idUsuario = comentario.idUsuario
                    WHERE idNoticia = '$idNoticia'
                    ORDER BY dataComentario DESC";
                    $listaComentarioExec = mysqli_query($conexao, $listaComentario);

                        while ($listarComents = mysqli_fetch_array($listaComentarioExec)) {
                            ?>
                            <div class="row">
                                <div class="col s12 m12">
                                    <div class="card blue lighten-5">
                                        <div class="card-content">
                                            <?php
                                                if (isset($_SESSION['logadoOk'])) {
                                                    $nivel = $_SESSION['dadosUser'][2];
                                                    if ($nivel == 3) {
                                                        echo "
                                                            <div class='right-align'>
                                                                <form action='#' method='POST'>
                                                                    <input type='submit' value='Excluir' name='delComent' class='btn red lighten-1' >
                                                                    <input type='hidden' value='$listarComents[0]' name='deletarComentario'>
                                                                </form>
                                                            </div>";

                                                        if (isset($_POST['delComent'])) {

                                                            $deletarComentario = $_POST['deletarComentario'];
                                                            $comentarioExcluir = $listarComents['idComentario'];
                                                            $del = "DELETE FROM comentario  WHERE  idComentario = $deletarComentario";
                                                               
                                                            $delOk = mysqli_query($conexao, $del);
                                                            
                                                        }
                                                    }
                                                }

                                                ?>
                                            <img class="fotoUsuario" src="./img-user/<?php echo $listarComents['foto'] ?>">
                                            <span class="card-title black-text nomeNoticia">Comentario de <?php echo $listarComents['nomeUsuario'] ?></span>
                                            <p class="black-text comentarioUsuario">
                                                <?php echo $listarComents['comentario'] ?>
                                            </p>
                                            <p class="right-align black-text">
                                                <?php
                                                    echo "Comentado no dia $listarComents[dataComentario]";
                                                    ?>
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        </p>
                </div>
            </div>
        </div>

</body>

<script>
    $(document).ready(function() {
        $('input#input_text, textarea#textarea2').characterCounter();
    });
</script>

</html>