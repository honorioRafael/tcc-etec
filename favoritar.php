<?php
require_once("conexao.php");

session_start();
if (!isset($_SESSION['__DVC_DadosLogin'])) {
    header("Location:login.php");
    return 1;
}
$DadosLogin = $_SESSION['__DVC_DadosLogin'];
$contaid = $DadosLogin['codUsuario'];

global $ip, $username, $senha, $db;
$con =  new mysqli($ip, $username, $senha, $db);
$postid = $_GET['evento'];
if(isset($_GET['remover'])) {
    $sql = "DELETE FROM `tcc`.`favoritos` WHERE `codUsuario` = '$contaid' AND `codPostagem` = '$postid'";
}
else {
    $sql = "INSERT INTO `tcc`.`favoritos`(`codUsuario`, `codPostagem`) VALUES ('$contaid', '$postid')";
}
$con->query($sql);
$con->close();
header("Location:evento.php?evento=$postid");
?>