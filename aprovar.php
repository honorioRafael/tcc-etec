<?php
require_once("conexao.php");
if(isset($_GET["reprovar"])) { $sql2 = "UPDATE `tcc`.`reservas` SET `Aprovado`='PAGAMENTO RECUSADO' WHERE `codReserva`='{$_GET['reserva']}';"; }
else $sql2 = "UPDATE `tcc`.`reservas` SET `Aprovado`='PAGAMENTO APROVADO' WHERE `codReserva`='{$_GET['reserva']}';";
$conn->query($sql2);
header("Location:admin.php");
?>