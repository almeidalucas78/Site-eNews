<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/materialize.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <?php
    require_once 'conexao.php';
    session_start();
    ?>

    <nav class="tamanho-menu">
        <div class="nav-wrapper">
            <div class="container">
                <a href="index.php" class="brand-logo"><img class="logo" src="img/logoAzulClaroDegrade.png"></a>
                <a href="index.php" class="enewsTitle">eNews</a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a class="textMenu" href="index.php">Home</a></li>
                    <li><a href="#">Categorias<i class="material-icons right formateIcon">arrow_drop_down</i></a>
                        <ul>
                            <?php

                            $query = "SELECT * FROM categoria";
                            $queryexec = mysqli_query($conexao, $query);

                            while ($listaCategoria = mysqli_fetch_array($queryexec)) {
                                echo "<li>
                                <a href='busca-categorias.php?idCategoria=" . utf8_encode($listaCategoria['idCategoria']) . "'class='textoDropDown'>" 
                                . utf8_encode($listaCategoria['nomeCategoria']) . "</a>
                                </li>";
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                    if (isset($_SESSION['logadoOk']) == 1) {
                        if ($_SESSION['dadosUser'][2] == 3) {
                            ?>
                    <li><a href="adm-prefs.php"><i class="material-icons green-text">settings_applications</i></a></li>
                    <?php
                            }
                            ?>
                    <li><a href="perfilUsuario.php"><i class="material-icons right modal-trigger"
                                href="#modal1">account_circle</i></a>
                    <li><a href="deslogar.php"><i class="material-icons red-text">exit_to_app</i></a></li>
                    <?php
                        } else {
                            ?>
                    <li><a class=""><i class="material-icons right modal-trigger" href="#modal1">account_circle</i></a>

                        <div id="modal1" class="modal">
                            <div class="modal-content">
                                <div class="login">
                                    <div class="row">
                                        <h3 class="titleLogin">Login</h3>
                                        <div class="col s12">

                                            <form class="formLogin" method="POST" action="#">
                                                <input class="inputLogin" type="email" name="email"
                                                    placeholder="E-mail">
                                                <input class="inputLogin" type="password" name="senha"
                                                    placeholder="Senha">
                                                <input type="submit" value="Entrar" name="login" class="btn btnEntrar">
                                            </form>
                                        </div>
                                    </div>
                                    <input type="submit" value="Cadastrar-se" name="cadastrar"
                                        class="btn btnRegistrar  deep-purple darken-1" onclick="aparece()">

                                    <?php
                                            if (isset($_POST['login'])) {
                                                $email = $_POST['email'];
                                                $senha = md5($_POST['senha']);

                                                if (empty($email)) {
                                                    echo "
                                                    <script> 
                                                        alert('Campo e-mail vazio');
                                                        location.href='index.php';
                                                    </script>
                                                    ";
                                                } else if (empty($senha)) {
                                                    echo "
                                                    <script> 
                                                        alert('Campo senha vazio');
                                                        location.href = 'index.php';
                                                    </script>
                                                    ";
                                                } else {
                                                    $verifica = "SELECT * FROM usuario WHERE email ='$email' 
                                                    AND senha = '$senha'";
                                                    $verificaexec = mysqli_query($conexao, $verifica);

                                                    if (mysqli_num_rows($verificaexec) <= 0) {
                                                        echo "
                                                            <script> 
                                                                alert('Login ou senha incorreto');
                                                                location.href='index.php';
                                                            </script>
                                                    ";
                                                        die();
                                                    } else {
                                                        echo "
                                                            <script>
                                                                alert('Logado com sucesso');
                                                                location.href = 'index.php';
                                                            </script>
                                                        ";
                                                        $idUsuario = "SELECT * FROM usuario WHERE email = '$email'";
                                                        $idUsuario = mysqli_query($conexao, $idUsuario);
                                                        $dados = mysqli_fetch_array($idUsuario);

                                                        $_SESSION['logadoOk'] = 1;
                                                        $_SESSION['dadosUser'] = $dados;
                                                    }
                                                }
                                            }
                                            ?>
                                </div>
                                <div id="cadastro">
                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <hr />
                                        <h3 class="titleLogin">Cadastre-se</h3>
                                        <input type="text" name="nome" placeholder="Insira seu nome">
                                        <select class="browser-default SelectSexo" name="sexo">
                                           <option value=""  disabled selected>Selecione seu Sexo</option>
                                            <option value="m">Masculino</option>
                                            <option value="f">Feminino</option>
                                        </select>
                                        
                                        <input type="email" name="email" placeholder="E-mail">
                                        <input type="password" name="senha" placeholder="Senha">
                                        <div class="formatLabel">
                                            <label for='selecao-arquivo'> <i
                                                    class="large material-icons iconLabel">file_upload
                                                </i>
                                                Adicionar uma foto de
                                                perfil</label>
                                        </div>
                                        <input id='selecao-arquivo' type="file" name="foto">
                                        <input type="submit" value="Finalizar Cadastro" name="fimCadastro"
                                            class="btn btnFinalizar">

                                    </form>

                                    <?php
                                            if (isset($_POST["fimCadastro"])) {

                                                $nome = $_POST['nome'];
                                                $sexo = $_POST['sexo'];
                                                $email = $_POST['email'];
                                                $senha = $_POST['senha'];
                                                $foto = $_FILES['foto'];

                                                $verifEmail = "SELECT email FROM usuario WHERE email = '$email'";
                                                $verifEmail = mysqli_query($conexao, $verifEmail);

                                                if(mysqli_num_rows($verifEmail) >= 1){
                                                    echo "
                                                                <script>
                                                                    alert('E-mail já cadastrado!');
                                                                    location.href='index.php';
                                                                </script>
                                                            ";
                                                            exit;
                                                } else if (strlen($nome) < 2) {
                                                    echo "
                                                                <script>
                                                                    alert('Nome muito pequeno');
                                                                    location.href='index.php';
                                                                </script>
                                                            ";
                                                    exit;
                                                } else if (strlen($nome) > 100) {
                                                    echo "
                                                                <script>
                                                                    alert('Nome muito grande');
                                                                    location.href = 'index.php';
                                                                </script>
                                                            ";
                                                    exit;
                                                } else if (is_numeric($nome)) {
                                                    echo "
                                                                <script>
                                                                    alert('Seu nome não pode conter números');
                                                                    location.href = 'index.php';
                                                                </script>
                                                            ";
                                                    exit;
                                                } else if (strlen($email) < 4) {
                                                    echo "
                                                                <script>
                                                                    alert('E-mail muito curto');
                                                                    location.href='index.php';
                                                                </script>
                                                            ";
                                                    exit;
                                                } else if (strlen($email) > 100) {
                                                    echo "
                                                                <script>
                                                                    alert('E-mail muito grande');
                                                                    location.href = 'index.php';
                                                                </script>
                                                            ";
                                                    exit;
                                                }
                                                if ($foto["error"] == 1) {
                                                    echo "
                                                        <script>
                                                            alert('Foto muito grande');
                                                            location.href = 'index.php';
                                                        </script>
                                                        ";
                                                }

                                                if ($foto["name"]) {
                                                    $height = 3000;
                                                    $width = 3000;
                                                    $kb = 5000000;

                                                    if (!preg_match("/\.(GIT|BMP|PNG|JPG|JPEG|SVG|JFIF|WEBP|GIF){1}$/i", $foto["name"], $ext)) {
                                                        echo "
                                                            <script>
                                                                alert('Formato não suportado');
                                                                location.href = 'index.php';
                                                            </script>
                                                            ";
                                                        exit;
                                                    }

                                                    $WeH = getimagesize($foto["tmp_name"]);
                                                    if ($WeH[0] > $height) {
                                                        echo "
                                                            <script>
                                                                alert('A imagem é muito larga!');
                                                                location.href = 'index.php';
                                                            </script>
                                                            ";
                                                    } else if ($WeH[0] > $width) {
                                                        echo "
                                                            <script>
                                                                alert('A imagem é muito larga!');
                                                                location.href = 'index.php';
                                                            </script>
                                                            ";
                                                    } else if ($foto["size"] > $kb) {
                                                        echo "
                                                            <script>
                                                                alert('A imagem é muito grande!');
                                                                location.href = 'index.php';
                                                            </script>
                                                            ";
                                                    } else {
                                                        preg_match("/\.(GIT|BMP|PNG|JPG|JPEG|SVG|JFIF|WEBP|GIF){1}$/i", $foto["name"], $ext);
                                                        $imgName = md5(uniqid(time())) . "." . $ext[1];
                                                        $folder = "img-user/" . $imgName;
                                                        move_uploaded_file($foto["tmp_name"], $folder);
                                                        $senha = md5($senha);

                                                        $query = "INSERT INTO usuario (nomeUsuario, nivel, statusUsuario, sexo, email, senha, foto) 
                                                            VALUES ('$nome' , 1 , 1 , '$sexo' , '$email' , '$senha' , '$imgName')";
                                                        $queryexec = mysqli_query($conexao, $query);
                                                        echo "
                                                                <script>
                                                                    alert('Cadastro finalizado');
                                                                    location.href = 'index.php';
                                                                </script>
                                                            ";

                                                        $idUsuario = "SELECT * FROM usuario WHERE email = '$email'";
                                                        $idUsuario = mysqli_query($conexao, $idUsuario);
                                                        $dados = mysqli_fetch_array($idUsuario);

                                                        $_SESSION['logadoOk'] = 1;
                                                        $_SESSION['dadosUser'] = $dados;
                                                    }
                                                }
                                            }
                                            ?>

                                </div>


                            </div>

                        </div>
                        <?php
                        }
                        ?>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="./js/navbar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var elems = document.querySelectorAll('.modal');
            var instances = M.Modal.init(elems, options);
        });
        // Or with jQuery
        $(document).ready(function () {
            $('.modal').modal();
        });
    </script>

</body>

</html>