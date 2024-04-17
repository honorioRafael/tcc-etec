<?php
require_once("conexao.php");
global $ip, $username, $senha, $db;
$con =  new mysqli($ip, $username, $senha, $db);
$postid = $_GET['alsjakshikbdabgdjabd'];

$result_s = $conn->query("SELECT `codReserva` FROM `reservas` WHERE `reservas`.`codPostagem` = $postid");
while($row = mysqli_fetch_assoc($result_s)) {
    $reserva = $row["codReserva"];
    $sql = "DELETE FROM `ingressos` WHERE `ingressos`.`codReserva` = $reserva";
    $con->query($sql);
}
$sql = "DELETE FROM `reservas` WHERE `reservas`.`codPostagem` = $postid";
$con->query($sql);
$sql = "DELETE FROM `posts` WHERE `posts`.`codPostagem` = $postid";
$con->query($sql);
$sql = "DELETE FROM `favoritos` WHERE `favoritos`.`codPostagem` = $postid";
$con->query($sql);
$con->close();
header("Location:index.php");
?>