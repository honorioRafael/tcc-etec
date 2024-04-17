<?php
    require_once("conexao.php");
    session_start();
    if(!isset($_SESSION['__DVC_DadosLogin'])) {  
        header("Location:login.php");
    }   

    function formatarDinheiro($valor) {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }

    $DadosLogin = $_SESSION['__DVC_DadosLogin'];
    $contaId = $DadosLogin['codUsuario'];

    $sql2 = "SELECT `codReserva`, `codPostagem` FROM `tcc`.`reservas` WHERE `codUsuario`='$contaId' ORDER BY `codReserva` desc LIMIT 1;";
    $resultado = $conn->query($sql2);
    while($row = mysqli_fetch_assoc($resultado)) {
        $codReserva = $row["codReserva"];
        $postid = $row["codPostagem"];
    }
    
    $sql = "SELECT * FROM `tcc`.`ingressos` WHERE `codReserva`='$codReserva' AND `codUsuario`='$contaId';";
    $ingressos = $conn->query($sql);
    
    $total = 0;
    $ValorTotal = 0;
    while($row = mysqli_fetch_assoc($ingressos)) { 
        $total ++;
        $ValorTotal += $row['ValorUnt'];
    } 
    
    $sql = "SELECT * FROM `tcc`.`ingressos` WHERE `codReserva`='$codReserva' AND `codUsuario`='$contaId';";
    $ingressos = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="reserva.css">
    <link rel="stylesheet" href="cc.css">
    <title>Confirmar compra</title>
    <style>
        table {
            margin: 20px;
            margin-left: 30px;
            width: 25%;
            border-collapse: collapse; /* Remove o espaçamento entre as células */      
            border-radius: 8px;
        }

        tr {
            background-color: #FFFAFA;
            border: 2px solid #9370DB;
            border-radius: 8px;
            color: black;
        }

        td,th {
            padding: 10px; /* Espaçamento interno dentro das células */
            text-align: left; /* Alinhe o conteúdo das células ao centro */
        }
       
        @media (max-width: 768px) {
            table {
                width: 80%;
            }  
        }
    </style>
</head>

<body>
<?php CriarHeader(2, $DadosLogin); ?>
    <div class="eventos_show"> 
        <div class="evento_show" style="flex-direction: column;">
            <?php if(isset($_GET['err'])) { if($_GET['err'] == 0) { ?>
                <h1 style="font-size: 2em; margin-top: 30px;">Compra de <?php echo $total; ?> ingressos confirmada!</h1>
                <span class="vt">Valor total: <span style="color: #19B519; font-size: 1.3em;"><?php echo formatarDinheiro($ValorTotal); ?></span></span>
                <div class='ingressos'>
                    <h2 style="margin-left: 30px; margin-bottom: 0px;">Ingressos adquiridos:</h2>
                    <table style=" margin-top: 5px;">
                    <tr><th>#Código</th> <th>Tipo</th> <th>Valor R$</th></tr>
                    <?php while($row = mysqli_fetch_assoc($ingressos)) { ?>
                        <tr><td>#<?php echo $row['codIngresso']; ?></td> <td><?php echo $row['Tipo']; ?></td> <td><?php echo formatarDinheiro($row['ValorUnt']); ?></td></tr>
                    <?php } ?>
                    </table>
                </div>
                <span class="txt-pagar">Agora, efetue o pagamento e faça o envio do comprovante clicando no botão abaixo:</span>
                <a class="btn-pagar" href="pagarEvento.php?reserva=<?php echo $codReserva; ?>">Pagar agora</a>
            <?php } else if($_GET['err'] == 1) { echo"<h2>Não foi possivel concluir sua compra :(</h2>"; } } ?>
        </div>
    </div>
</body>
<?php //} ?>
</html>
