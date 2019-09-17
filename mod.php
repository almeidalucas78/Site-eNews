<?php 
require_once 'conexao.php';
$acao = $_POST['btn'];
$id_coment = $_post['idComentario'];

if($acao == 'Aprovar'){
    $query = "UPDATE comentario SET status_comentario = 2 WHERE idComentario = $id_coment;";
    $executa = mysqli_query($conexao, $query);
    if($executa == 1){
        ?>
        <script>
            alert("Comentario aceito");
            location.href='noticia.php';
        </script>
        <?php
    }else {
        ?>
        <script>
            alert("Erro");
            location.href='noticia.php';
        </script>
        <?php
    }else{
        $query2 = "DELETE FROM comentario WHERE idComentario = $id_coment;";
        $executa2 = mysqli_query($conexao, $query);
    if($executa2 == 1){
        ?>
        <script>
            alert("Comentario apagado");
            location.href='noticia.php';
        </script>
        <?php
    }else {
        ?>
        <script>
            alert("Comentario não apagado");
            location.href='noticia.php';
        </script>
        <?php
    }



