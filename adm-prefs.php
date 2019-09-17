<html>

<head>
    <meta charset="UTF-8">
    <title>Área de administração</title>

    <!--Importação-->
    <link rel="stylesheet" href="css/materialize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/adm-styles.css">

    <!--Conexão externa, precisa de internet_-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img/logoAzulClaroDegrade.png" type="image/png">
</head>

<body>

    <?php

    require_once 'navbar.inc.php';

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
        if ($nivel == 3) {
            ?>
            <div class="container conteudo">
                <h2 class="center">Painel de Administração</h2>
                <h5 class="center">Seja bem vindo <?php echo $_SESSION['dadosUser'][1] ?></h5>
                <br>
                <h6 class="center"> Deseja registrar um novo jornalista? </h6>

                <div class="container">
                    <form method="POST" action="" enctype="multipart/form-data">

                        <input type="text" name="nome" placeholder="Nome do jornalista">
                        <select class="browser-default" name="sexo">
                            <option value="m">Masculino</option>
                            <option value="f">Feminino</option>
                        </select>
                        <input type="email" name="email" placeholder="E-mail do jornalista">
                        <input type="password" name="senha" placeholder="Senha do jornalista">
                        <div class="formatLabel">
                            <label for='selecao-arquivo'> <i class="large material-icons iconLabel">file_upload
                                </i>
                                Adicionar foto de
                                perfil</label>
                        </div>
                        <input id='selecao-arquivo' type="file" name="foto">
                        <input type="submit" value="Finalizar" name="fimCadastroJornalista" class="btn btnFimCadastro">

                    </form>

                    <?php
                            if (isset($_POST["fimCadastroJornalista"])) {

                                $nome = $_POST['nome'];
                                $sexo = $_POST['sexo'];
                                $email = $_POST['email'];
                                $senha = $_POST['senha'];
                                $foto = $_FILES['foto'];

                                if (strlen($nome) < 2) {
                                    echo "
                                        <script>
                                            alert('Nome muito pequeno');
                                            location.href='adm-prefs.php';
                                        </script>
                                        ";
                                    exit;
                                } else if (strlen($nome) > 100) {
                                    echo "
                                        <script>
                                            alert('Nome muito grande');
                                            location.href = 'adm-prefs.php';
                                        </script>
                                        ";
                                    exit;
                                } else if (is_numeric($nome)) {
                                    echo "
                                        <script>
                                            alert('Seu nome não pode conter números');
                                            location.href = 'adm-prefs.php';
                                        </script>
                                        ";
                                    exit;
                                } else if (strlen($email) < 4) {
                                    echo "
                                        <script>
                                            alert('E-mail muito curto');
                                            location.href='adm-prefs.php';
                                        </script>
                                        ";
                                    exit;
                                } else if (strlen($email) > 100) {
                                    echo "
                                        <script>
                                            alert('E-mail muito grande');
                                            location.href = 'adm-prefs.php';
                                        </script>
                                        ";
                                    exit;
                                }
                                if ($foto["error"] == 1) {
                                    echo "
                                        <script>
                                            alert('Foto muito grande');
                                            location.href = 'adm-prefs.php';
                                        </script>
                                        ";
                                }

                                if ($foto["name"]) {
                                    $height = 3000;
                                    $width = 3000;
                                    $kb = 5000000;

                                    if (!preg_match("/\.(GIT|BMP|PNG|JPG|JPEG|SVG|JFIF|WEBP){1}$/i", $foto["name"], $ext)) {
                                        echo "
                                            <script>
                                                alert('Formato não suportado');
                                                location.href = 'adm-prefs.php';
                                            </script>
                                            ";
                                        exit;
                                    }

                                    $WeH = getimagesize($foto["tmp_name"]);
                                    if ($WeH[0] > $height) {
                                        echo "
                                            <script>
                                                alert('A imagem é muito larga!');
                                                location.href = 'adm-prefs.php';
                                            </script>
                                            ";
                                    } else if ($WeH[0] > $width) {
                                        echo "
                                            <script>
                                                alert('A imagem é muito larga!');
                                                location.href = 'adm-prefs.php';
                                            </script>
                                            ";
                                    } else if ($foto["size"] > $kb) {
                                        echo "
                                            <script>
                                                alert('A imagem é muito grande!');
                                                location.href = 'adm-prefs.php';
                                            </script>
                                            ";
                                    } else {
                                        preg_match("/\.(GIT|BMP|PNG|JPG|JPEG|SVG|JFIF|WEBP){1}$/i", $foto["name"], $ext);
                                        $imgName = md5(uniqid(time())) . "." . $ext[1];
                                        $folder = "img-user/" . $imgName;
                                        move_uploaded_file($foto["tmp_name"], $folder);
                                        $senha = md5($senha);

                                        $query = "INSERT INTO usuario (nomeUsuario, nivel, statusUsuario, sexo, email, senha, foto)
                                        VALUES ('$nome' , 2 , 1 , '$sexo' , '$email' , '$senha' , '$imgName')";
                                        $queryexec = mysqli_query($conexao, $query);
                                        echo "
                                            <script>
                                                alert('Cadastro finalizado');
                                                location.href = 'adm-prefs.php';
                                            </script>
                                            ";
                                    }
                                }
                            }
                            ?>
                </div>
            </div>
    <?php
        } else {
            echo "  <div class='center'>
                        <img class='center' src='./img/robo.png'>
                    </div>
                    <style>
                        body {
                            background-color: #fff;
                        }
                    </style>
                ";
        }
    }
    ?>



</body>

</html>