<?php
    require_once("conexao.php");
    session_start();
    if(!isset($_SESSION['__DVC_DadosLogin'])) {  
        header("Location:login.php");
    }   

    $DadosLogin = $_SESSION['__DVC_DadosLogin'];
    $contaId = $DadosLogin['codUsuario'];
    $reservaid = $_GET['reserva'];

    function formatarDinheiro($valor) {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }

    $eventos = array();
    $sql2 = "SELECT `codReserva`, `codPostagem` FROM `tcc`.`reservas` WHERE `codUsuario`='$contaId' AND `Aprovado` = 'AGUARDANDO PAGAMENTO' OR `Aprovado` = 'PAGAMENTO RECUSADO' ORDER BY `codReserva` desc;";
    $resultado = $conn->query($sql2);
    $totalreservas = 0;
    while($row = mysqli_fetch_assoc($resultado)) {
        $totalreservas ++;
        $codReserva = $row["codReserva"];
        $postid = $row["codPostagem"];
        $sql2 = "SELECT `Titulo`,`CodPostagem` FROM `tcc`.`posts` WHERE `codPostagem`='$postid'";
        $resultad = $conn->query($sql2);
        array_push($eventos, mysqli_fetch_assoc($resultad));
    }

    $resultado = $conn->query("SELECT `codPostagem` FROM `tcc`.`reservas` WHERE `codReserva`='$reservaid';");
    $eventoid = mysqli_fetch_object($resultado)->codPostagem;

    $resultado = $conn->query("SELECT `Valor` FROM `tcc`.`reservas` WHERE `codReserva`='$reservaid';");
    $ValorReserva = mysqli_fetch_object($resultado)->Valor;

    $resultado = $conn->query("SELECT COUNT(*) as count FROM ingressos WHERE codReserva = $reservaid;");
    $valorTotal = mysqli_fetch_object($resultado)->count;

    $sql2 = "SELECT * FROM `tcc`.`reservas` WHERE `codUsuario`='$contaId' AND `Aprovado` = 'AGUARDANDO PAGAMENTO' OR `Aprovado` = 'PAGAMENTO RECUSADO' ORDER BY `codReserva` desc;";
    $resultado = $conn->query($sql2);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="reserva.css">
    <link rel="stylesheet" href="pagar.css">
    <title>Divulgarça - Minha conta</title>
</head>
<body>
    <?php CriarHeader(2, $DadosLogin); ?>
    <main>
        <div class="opcoes">
            <ul>
                <li>EVENTOS PARA PAGAR</li>
                <?php $pos = 0; while($row = mysqli_fetch_assoc($resultado)) { ?>
                    <li><a class="btn" href="pagarEvento.php?reserva=<?php echo $row["codReserva"]; ?>">#<?php echo $row["codReserva"]." | ".$eventos[$pos]["Titulo"]; ?></a></li>
                <?php $pos++; } ?>
            </ul>
        </div>
        
        <div class="content">
            <div class="ingressos" id="ingressos">           
                <?php if($totalreservas == 0) { echo"<h2 style='color: red;'>Você ainda não reservou nenhhum evento!</h2>"; } else { ?>                               
                <div id="reserva">
                    <h1>Detalhes do evento:</h1>
                <?php
                    $sql2 = "SELECT * FROM `tcc`.`posts` WHERE `codPostagem` = '$eventoid';";
                    $resultado = $conn->query($sql2); 
                    while($row = mysqli_fetch_assoc($resultado)) { ?>
                        <table>
                            <tr><td>Nome do evento</td><td><?php echo $row['Titulo']; ?></td></tr>
                            <tr><td>Data do evento</td><td><?php echo converterData($row['Data']); ?></td></tr>
                            <tr><td>Horario do evento</td><td><?php echo removerSegundos($row['Horario']); ?></td></tr>
                            <tr><td>Total de ingressos comprados</td><td><?php echo $valorTotal; ?> unidades</td></tr>
                            <tr><td>Valor total</td><td><b style="color: #19B519;"><?php echo formatarDinheiro($ValorReserva); ?></b></td></tr>
                        </table>

                        <button id="verIngressos">Ver ingressos</button>   
                        <?php } 

                        $sql2 = "SELECT * FROM `tcc`.`ingressos` WHERE `codReserva` = '$reservaid';";
                        $ingressos = $conn->query($sql2); ?> 
                        <table id="tabela-ingressos" style="display: none;">
                            <tr><th>#Código</th> <th>Tipo</th> <th>Valor R$</th></tr>
                            <?php while($row = mysqli_fetch_assoc($ingressos)) { ?>
                                <tr><td>#<?php echo $row['codIngresso']; ?></td> <td><?php echo $row['Tipo']; ?></td> <td>R$ <?php echo $row['ValorUnt']; ?></td></tr>
                            <?php } ?>
                        </table>           
                </div>
                <?php } ?>
            </div>
            <br><br>
            <div class="pag-info">
                <h1 style="text-align: center; font-weight: light; text-transform: uppercase;">Efetue o pagamento com <span style="color: green;">pix</span> utilizando a chave: <br><span style="color: #9370DB;">1902f2c4-9c60-4a8f-b653-0d51c35a49c7</span><br> ou use o qr-code abaixo:</h1>
                <img style="align-self: center;" src="imagens/qr_code.png" alt="QR CODE PIX - USE A CHAVE">
                <h1 style="text-align: center; text-transform: uppercase;">Já fez o pagamento? envie o comprovante</h1>
                
                <form action="pagamento.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="reserva" value="<?php echo $reservaid; ?>">
                    <div class="custom-file-input">
                        <input type="file" name="imagem" id="fileInput" class="input-file" accept="image/*" />
                        <label for="fileInput" class="custom-file-button">Escolher arquivo</label>
                        <span id="fileName" class="file-name"></span>
                    </div>
                    <input type="submit" value="Enviar comprovante" />
                </form>
                <?php 
                    if(isset($_GET['msg'])) {
                        if($_GET['msg'] == 1) {
                            echo '<h2 style="color:red;">Por favor, insira uma imagem válida!</h2>';
                        }
                        if($_GET['msg'] == 2) {
                            echo '<h2 style="color:red;">Ocorreu um erro ao fazer o upload do arquivo!</h2>';
                        }
                    }
                ?>
            </div>
        </div>
    </main>
</body>
</html>
<script>
    document.getElementById('fileInput').addEventListener('change', function() {
        var fileName = this.value.split('\\').pop();  // Remove o caminho do arquivo, mantendo apenas o nome do arquivo
        document.getElementById('fileName').textContent = fileName;
    });

    document.getElementById("verIngressos").addEventListener("click", function() {
        var conteudoElement = document.getElementById("tabela-ingressos");
        if(conteudoElement.style.display == "block") {
            conteudoElement.style.display = "none";  
        }
        else {
            conteudoElement.style.display = "block";  
        }
    });
</script>
