<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/materialize.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img/logoAzulClaroDegrade.png" type="image/png">
    <link rel="stylesheet" href="css/perfilUsuario.css">

    <title>eNews</title>
</head>

<body>
    <?php

    include_once 'navbar.inc.php';

    if (!isset($_SESSION['logadoOk'])) {
        echo "  <div class='center'>
                    <img class='center' src='./img/robo.png'>
                </div>
                <style>
                    body {
                        background-color: #fff;
                    }
                </style>
                ";
    } else {
        $nivel = $_SESSION['dadosUser'][2];
        if ($nivel == 2) {
            ?>
            <div class="container">
                <div class="row">
                    <div class="col s12">
                        <div class="areaPerfil">
                            <!--Foto de perfil do usuario-->
                            <img class="fotoUsuario" src="./img-user/<?php echo $_SESSION['dadosUser'][7]; ?>">
                            <form action="" method="POST" enctype="multipart/form-data">
                            <div class="formatLabel">
                                            <label for='selecao-arquivo'> <i
                                                    class="medium material-icons iconLabel">file_upload
                                                </i>
                                                Atualizar foto</label>
                                        </div>
                                        <input id='selecao-arquivo' type="file" name="foto">

                                <input class="atualizar  btn"type="submit" value="Atualizar" name="atFoto" class="btn">
                            </form>
                            <?php
                            if (isset($_POST['atFoto'])) {
                                $foto = $_FILES['foto'];
                
                                if ($foto["name"]) {
                                    $height = 3000;
                                    $width = 3000;
                                    $kb = 5000000;
                
                                    if (!preg_match("/\.(GIT|BMP|PNG|JPG|JPEG|SVG|JFIF|WEBP|GIF){1}$/i", $foto["name"], $ext)) {
                                        echo "
                                            <script>
                                                alert('Formato não suportado');
                                                location.href = 'perfilUsuario.php';
                                            </script>
                                            ";
                                        exit;
                                    }
                
                                    $WeH = getimagesize($foto["tmp_name"]);
                                    if ($WeH[0] > $height) {
                                        echo "
                                            <script>
                                                alert('A imagem é muito larga!');
                                                location.href = 'perfilUsuario.php';
                                            </script>
                                            ";
                                    } else if ($WeH[0] > $width) {
                                        echo "
                                            <script>
                                                alert('A imagem é muito larga!');
                                                location.href = 'perfilUsuario.php';
                                            </script>
                                            ";
                                    } else if ($foto["size"] > $kb) {
                                        echo "
                                            <script>
                                                alert('A imagem é muito grande!');
                                                location.href = 'perfilUsuario.php';
                                            </script>
                                            ";
                                    } else {
                                        preg_match("/\.(GIT|BMP|PNG|JPG|JPEG|SVG|JFIF|WEBP|GIF){1}$/i", $foto["name"], $ext);
                                        $imgName = md5(uniqid(time())) . "." . $ext[1];
                                        $folder = "img-user/" . $imgName;
                                        move_uploaded_file($foto["tmp_name"], $folder);
                                        $fota = $_SESSION['dadosUser']['foto'];
                                        unlink("img-user/" . $fota);
                
                
                                        $idUsuario = $_SESSION['dadosUser']['idUsuario'];
                                        $novaFoto = "UPDATE usuario SET foto = '$imgName' WHERE idUsuario = '$idUsuario'";
                                        $novaFotoOK = mysqli_query($conexao, $novaFoto);
                
                                        echo "
                                        <script>
                                            alert('Imagem atualizada com sucesso');
                                            alert('É necessário relogar para que a alteração se conclua!');
                                            location.href= 'index.php';
                                        </script>
                                        ";
                                        session_destroy();
                                    }
                                }
                            }
                            ?>
                            <p class="nomeUsuario">Olá <?php echo $_SESSION['dadosUser'][1]; ?></p>
                            <p class="tituloPostarNoticia">Publicar uma Noticia</p>

                            <div class="areaNoticia">

                                <form method="POST" action="#">

                                    <div class="areaTituloNoticia">
                                        <div class="row">
                                            <input class="TituloNoticia" placeholder="Digite o Titulo da Noticia" type="text" name="titulo">
                                            <div>

                                                <select name="categoria" class="browser-default CategoriaNoticialabel">
                                                    <option value="" disabled selected>Selecione a categoria da noticia</option>
                                                    <?php
                                                            $query = "SELECT * FROM categoria";
                                                            $queryexec = mysqli_query($conexao, $query);
                                                            while ($listaCategoria = mysqli_fetch_array($queryexec)) {
                                                                echo "  <option value='$listaCategoria[idCategoria]'>
                                                    " . utf8_encode($listaCategoria['nomeCategoria']) . "</option>";
                                                            }
                                                            ?>
                                                </select>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="tini">
                                        <textarea name="noticia"></textarea>
                                    </div>

                                    <input type="submit" name="enviarNoticia" value="Publicar noticia" class="btn btnPublicar">
                                </form>

                                <?php
                                        if (isset($_POST['enviarNoticia'])) {
                                            $titulo = $_POST['titulo'];
                                            $data = "13/09/2019";
                                            $categoria = $_POST['categoria'];
                                            $idUsuario = $_SESSION['dadosUser']['idUsuario'];
                                            $conteudoNoticia = $_POST['noticia'];

                                            if ($titulo == "") {
                                                echo "
                                                <script>
                                                    alert('Título da noticia em branco');
                                                    location.href='perfilUsuario.php';
                                                </script>
                                                ";
                                                exit;
                                            } else if ($categoria == "") {
                                                echo "
                                                <script>
                                                    alert('Noticia sem categoria');
                                                    location.href='perfilUsuario.php';
                                                </script>
                                                ";
                                                exit;
                                            } else {
                                                $queryNot = "  INSERT INTO noticias (tituloNoticia, dataNoticia, idCategoria, idUsuario, conteudoNoticia)
                                        VALUES ('$titulo' , '$data' , '$categoria' , '$idUsuario', '$conteudoNoticia')";
                                                $queryNotExec = mysqli_query($conexao, $queryNot);
                                            }
                                        }
                                        ?>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        <?php
            } else {
                ?>

            <div class="container">
                <div class="row">
                    <div class="col s12">
                        <div class="areaPerfil">
                            <style>
                                .areaPerfil {
                                    height: 400px;
                                }
                            </style>
                            <!--Foto de perfil do usuario-->
                            <img class="fotoUsuario" src="./img-user/<?php echo $_SESSION['dadosUser'][7]; ?>">
                            <p class="nomeUsuario">Olá <?php echo $_SESSION['dadosUser'][1]; ?></p>

                            <form action="" method="POST" enctype="multipart/form-data">
                            <div class="formatLabel">
                                            <label for='selecao-arquivo'> <i
                                                    class="medium material-icons iconLabel">file_upload
                                                </i>
                                                Atualizar foto</label>
                                        </div>
                                        <input id='selecao-arquivo' type="file" name="foto">

                                <input class="atualizar  btn"type="submit" value="Atualizar" name="atFoto" class="btn">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

    <?php
            if (isset($_POST['atFoto'])) {
                $foto = $_FILES['foto'];

                if ($foto["name"]) {
                    $height = 3000;
                    $width = 3000;
                    $kb = 5000000;

                    if (!preg_match("/\.(GIT|BMP|PNG|JPG|JPEG|SVG|JFIF|WEBP|GIF){1}$/i", $foto["name"], $ext)) {
                        echo "
                            <script>
                                alert('Formato não suportado');
                                location.href = 'perfilUsuario.php';
                            </script>
                            ";
                        exit;
                    }

                    $WeH = getimagesize($foto["tmp_name"]);
                    if ($WeH[0] > $height) {
                        echo "
                            <script>
                                alert('A imagem é muito larga!');
                                location.href = 'perfilUsuario.php';
                            </script>
                            ";
                    } else if ($WeH[0] > $width) {
                        echo "
                            <script>
                                alert('A imagem é muito larga!');
                                location.href = 'perfilUsuario.php';
                            </script>
                            ";
                    } else if ($foto["size"] > $kb) {
                        echo "
                            <script>
                                alert('A imagem é muito grande!');
                                location.href = 'perfilUsuario.php';
                            </script>
                            ";
                    } else {
                        preg_match("/\.(GIT|BMP|PNG|JPG|JPEG|SVG|JFIF|WEBP|GIF){1}$/i", $foto["name"], $ext);
                        $imgName = md5(uniqid(time())) . "." . $ext[1];
                        $folder = "img-user/" . $imgName;
                        move_uploaded_file($foto["tmp_name"], $folder);
                        $fota = $_SESSION['dadosUser']['foto'];
                        unlink("img-user/" . $fota);

                        $idUsuario = $_SESSION['dadosUser']['idUsuario'];
                        $novaFoto = "UPDATE usuario SET foto = '$imgName' WHERE idUsuario = '$idUsuario'";
                        $novaFotoOK = mysqli_query($conexao, $novaFoto);

                        echo "
                        <script>
                            alert('Imagem atualizada com sucesso');
                            alert('É necessário relogar para que a alteração se conclua!');
                            location.href= 'index.php';
                        </script>
                        ";
                        session_destroy();
                    }
                }
            }
        }
    }

    ?>
    <script src="js/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            resize: false,
            height: 700,
            plugins: 'code image',
            toolbar: 'undo redo bold italic underline strikethrough fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
            images_upload_url: 'uploadImage.php',

            images_upload_handler: function(blobInfo, success, failure) {
                var xhr, formData;

                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', 'uploadImage.php');

                xhr.onload = function() {
                    var json;

                    if (xhr.status != 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }

                    json = JSON.parse(xhr.responseText);

                    if (!json || typeof json.location != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }

                    success(json.location);
                };

                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                xhr.send(formData);
            },
        });
    </script>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>

</html>