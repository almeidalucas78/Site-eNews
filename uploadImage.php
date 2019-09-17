<?php

// Configuração pro TinyMCE aceitar imagens dentro do <textarea>

$accepted_origins = array("http://localhost"); 

$folder = "img-noticia/";

reset($_FILES);
$foto = current($_FILES);
if(is_uploaded_file($foto['tmp_name'])){
    if(isset($_SERVER['HTTP_ORIGIN'])){
        if(in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)){
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        }else{
            header("HTTP/1.1 403 Origin Denied");
            return;
        }
    }
  
    if (!preg_match("/\.(GIT|BMP|PNG|JPG|JPEG|SVG|JFIF|WEBP){1}$/i", $foto["name"], $ext)) {
        echo "
            <script>
                alert('Formato não suportado');
                location.href='perfilUsuario.php';
            </script>
            ";
        exit;
    }
  
    preg_match("/\.(GIT|BMP|PNG|JPG|JPEG|SVG|JFIF|WEBP){1}$/i", $foto["name"], $ext);
    $imgName = md5(uniqid(time())) . "." . $ext[1];
    $folder = "img-noticia/" . $imgName;
    move_uploaded_file($foto["tmp_name"], $folder);
  
    echo json_encode(array('location' => $folder));
} else {
    header("HTTP/1.1 500 Server Error");
}

?>