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

    $sql2 = "SELECT `codReserva` FROM `tcc`.`reservas` WHERE `Aprovado`='AGUARDANDO APROVAÇÃO' ORDER BY `codReserva` desc;";
    $resultado = $conn->query($sql2);
    $totalreservas = 0;
    while($row = mysqli_fetch_assoc($resultado)) {
        $totalreservas ++;    
    }
    
    $sql2 = "SELECT `codReserva` FROM `tcc`.`reservas` WHERE `Aprovado`='PAGAMENTO APROVADO' ORDER BY `codReserva` desc;";
    $resultado = $conn->query($sql2);
    $totalreservas2 = 0;
    while($row = mysqli_fetch_assoc($resultado)) {
        $totalreservas2 ++;    
    }
    $sql2 = "SELECT * FROM `tcc`.`reservas` WHERE `Aprovado`='AGUARDANDO APROVAÇÃO' ORDER BY `codReserva` desc;";
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
    <link rel="stylesheet" href="reserva.css">
    <title>Divulgarça - Minha conta</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
    }
    ul {
        list-style-type: none;
    }
    main {
        height: 80%;
        display: flex;
        margin: 20px;
        gap: 20px;
    }
    .opcoes, .content {
        background-color: white;
        border-radius: 8px;
        padding: 20px;
        height: 100%;
        -webkit-box-shadow: 7px 7px 69px -37px rgba(0,0,0,0.26);
        -moz-box-shadow: 7px 7px 69px -37px rgba(0,0,0,0.26);
        box-shadow: 7px 7px 69px -37px rgba(0,0,0,0.26);
    }
    .content {
        width: 80%;
    }
    .opcoes {
        height: 100%;
        width: 10%;
    }
    table {
        max-width: 100%;
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
    
    td a {
        text-decoration: underline;
        cursor: pointer;
    }

    .btnpagar {
        text-decoration: none;
        background-color: green;
        color: white;
        border-radius: 5px;
        padding: 5px;
        margin-left: 5px;
    }

    .aprovar {
        background-color: green;
        padding: 5px 10px;
        border-radius: 5px;
        color: white;
        text-decoration: none;
        word-wrap:none;
    }

    .opcoes li {
        width: 100%;
        margin-bottom: 15px;
    }

    .opcoes a {
        display: block;
        width: auto;
        text-decoration: none;
        text-align: center;
    }

    /* Estilo do botão que mostra a imagem */
    .show-button {
        display: block;
        padding: 7px 10px;
        background-color: green;
        border-radius: 5px;
        color: white;
        border: none;
        cursor: pointer;
        word-wrap:none;
    }

    /* Estilo da div que contém a imagem */
    .image-container {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4); /* Preto com opacidade de 40% */
        padding: 10px; /* Espaçamento de 10px */
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    /* Estilo da imagem que ocupa toda a área da div */
    .image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        text-align: center;
        margin: auto;
    }

    /* Estilo do botão de X que oculta a imagem */
    .close-button {
        position: absolute;
        top: 10%;
        right: 10%;
        width: 50px;
        height: 50px;
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 30px;
        cursor: pointer;
        background-color: none;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .close-button:hover {
        background-color: rgba(0, 0, 0, 0.50);
    }

    @media (max-width: 868px) {
        main {
            flex-direction: column;
        }
        .opcoes, .content  {
            width: 80%;
        }
        .opcoes {
            height: 110px;
        }
        table {
            width: 40%;
        }
    }

</style>
<body>
<?php CriarHeader(3, $DadosLogin); ?>
    <main>
        <div class="opcoes">
            <ul style="width: 100%;">
                <li><a href="insert.php" class="opc-btn">Adicionar evento</a></li>           
                <li><button id="aprovacoesBtn" class="opc-btn">Aprovar Reserva</button></li> 
                <li><button id="aprovadosBtn" class="opc-btn">Consultar Reserva</button></li>     
            </ul>
        </div>
        
        <div class="content">
            <div class="ingressos" id="aprovacoes" style="display: none;">
            <?php if($totalreservas == 0) { ?>
                <div class="alerta" style="padding: 15px; background-color: #FFD6D6; border-color: #FF4D4D;">
                    <svg width="20px" height="20px" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.8809 16.15C10.8809 16.0021 10.9101 15.8556 10.967 15.7191C11.024 15.5825 11.1073 15.4586 11.2124 15.3545C11.3175 15.2504 11.4422 15.1681 11.5792 15.1124C11.7163 15.0567 11.8629 15.0287 12.0109 15.03C12.2291 15.034 12.4413 15.1021 12.621 15.226C12.8006 15.3499 12.9399 15.5241 13.0211 15.7266C13.1024 15.9292 13.122 16.1512 13.0778 16.3649C13.0335 16.5786 12.9272 16.7745 12.7722 16.9282C12.6172 17.0818 12.4204 17.1863 12.2063 17.2287C11.9922 17.2711 11.7703 17.2494 11.5685 17.1663C11.3666 17.0833 11.1938 16.9426 11.0715 16.7618C10.9492 16.5811 10.8829 16.3683 10.8809 16.15ZM11.2408 13.42L11.1008 8.20001C11.0875 8.07453 11.1008 7.94766 11.1398 7.82764C11.1787 7.70761 11.2424 7.5971 11.3268 7.5033C11.4112 7.40949 11.5144 7.33449 11.6296 7.28314C11.7449 7.2318 11.8697 7.20526 11.9958 7.20526C12.122 7.20526 12.2468 7.2318 12.3621 7.28314C12.4773 7.33449 12.5805 7.40949 12.6649 7.5033C12.7493 7.5971 12.813 7.70761 12.8519 7.82764C12.8909 7.94766 12.9042 8.07453 12.8909 8.20001L12.7609 13.42C12.7609 13.6215 12.6809 13.8149 12.5383 13.9574C12.3958 14.0999 12.2024 14.18 12.0009 14.18C11.7993 14.18 11.606 14.0999 11.4635 13.9574C11.321 13.8149 11.2408 13.6215 11.2408 13.42Z" fill="#000000"/>
                        <path d="M12 21.5C17.1086 21.5 21.25 17.3586 21.25 12.25C21.25 7.14137 17.1086 3 12 3C6.89137 3 2.75 7.14137 2.75 12.25C2.75 17.3586 6.89137 21.5 12 21.5Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p>Não há reservas a serem aprovadas!</p>
                </div>
                <?php } ?>              
                <div id="reserva">
                <?php if($totalreservas != 0) { ?>
                <h1>Reservas a serem aprovadas</h1>
                    <table class="reserva_individual">
                    <tr><th>#Codigo</th> <th>Valor R$</th> <th>Data e hora da reserva</th> <th>Comprovante</th> <th>Aprovar</th> <th>Reprovar</th></tr>
                    <?php $pos = 0; while($row = mysqli_fetch_assoc($resultado)) { $codr = $row["codReserva"]; ?>
                        <tr><td>#<?php echo $row["codReserva"]; ?></td> <th><?php echo formatarDinheiro($row["Valor"]); ?> </th> <td><?php echo converterData($row["DataReserva"])." às ".removerSegundos($row["HoraReserva"]); ?></td>
                        <?php $formato = $row['Formato']; ?>
                        <td><button class="show-button" onclick="showImage(<?php echo $row['codReserva']; ?>, '<?php echo $formato ?>')">Mostrar imagem</button></td>
                        <td><a class="aprovar" href="aprovar.php?reserva=<?php echo $row["codReserva"]; ?>">Aprovar compra</a></td>
                        <td><a class="aprovar" href="aprovar.php?reserva=<?php echo $row["codReserva"]; ?>&reprovar=true" style="background-color: red;">Reprovar compra</a></td>
                        </tr>
                    <?php $pos ++; } ?>
                    </table>
                    <?php } ?>
                        <div class="image-container" onclick="hideImage()">
                            <img class="image" src="" alt="Imagem">
                            <button class="close-button" onclick="hideImage()">X</button>
                        </div>

                    <script>
                        function showImage(reserva, ext) {
                            var imageContainer = document.querySelector(".image-container");
                            var image = document.querySelector(".image");
                            var closeButton = document.querySelector(".close-button");

                            image.src = "uploads/Comprovante_"+reserva+"."+ext;

                            imageContainer.style.display = "block";
                            image.style.display = "block";
                            closeButton.style.display = "block";
                        }

                        function hideImage() {
                            var imageContainer = document.querySelector(".image-container");
                            var image = document.querySelector(".image");
                            var closeButton = document.querySelector(".close-button");

                            imageContainer.style.display = "none";
                            image.style.display = "none";
                            closeButton.style.display = "none";
                        }
                    </script>
                </div>
            </div>
            
            <div class="ingressos" id="aprovados" style="display: none;">
            <?php if($totalreservas2 == 0) { ?>
                <div class="alerta" style="padding: 15px; background-color: #FFD6D6; border-color: #FF4D4D;">
                    <svg width="20px" height="20px" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.8809 16.15C10.8809 16.0021 10.9101 15.8556 10.967 15.7191C11.024 15.5825 11.1073 15.4586 11.2124 15.3545C11.3175 15.2504 11.4422 15.1681 11.5792 15.1124C11.7163 15.0567 11.8629 15.0287 12.0109 15.03C12.2291 15.034 12.4413 15.1021 12.621 15.226C12.8006 15.3499 12.9399 15.5241 13.0211 15.7266C13.1024 15.9292 13.122 16.1512 13.0778 16.3649C13.0335 16.5786 12.9272 16.7745 12.7722 16.9282C12.6172 17.0818 12.4204 17.1863 12.2063 17.2287C11.9922 17.2711 11.7703 17.2494 11.5685 17.1663C11.3666 17.0833 11.1938 16.9426 11.0715 16.7618C10.9492 16.5811 10.8829 16.3683 10.8809 16.15ZM11.2408 13.42L11.1008 8.20001C11.0875 8.07453 11.1008 7.94766 11.1398 7.82764C11.1787 7.70761 11.2424 7.5971 11.3268 7.5033C11.4112 7.40949 11.5144 7.33449 11.6296 7.28314C11.7449 7.2318 11.8697 7.20526 11.9958 7.20526C12.122 7.20526 12.2468 7.2318 12.3621 7.28314C12.4773 7.33449 12.5805 7.40949 12.6649 7.5033C12.7493 7.5971 12.813 7.70761 12.8519 7.82764C12.8909 7.94766 12.9042 8.07453 12.8909 8.20001L12.7609 13.42C12.7609 13.6215 12.6809 13.8149 12.5383 13.9574C12.3958 14.0999 12.2024 14.18 12.0009 14.18C11.7993 14.18 11.606 14.0999 11.4635 13.9574C11.321 13.8149 11.2408 13.6215 11.2408 13.42Z" fill="#000000"/>
                        <path d="M12 21.5C17.1086 21.5 21.25 17.3586 21.25 12.25C21.25 7.14137 17.1086 3 12 3C6.89137 3 2.75 7.14137 2.75 12.25C2.75 17.3586 6.89137 21.5 12 21.5Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p>Não há reservas aprovadas!</p>
                </div>
                <?php } else { 
                    $sql2 = "SELECT * FROM `tcc`.`reservas` WHERE `Aprovado`='PAGAMENTO APROVADO' ORDER BY `codReserva` desc;";
                    $resultado = $conn->query($sql2);
                ?>              
                <div id="reserva">
                <h1>Reservas aprovadas</h1>
                    <table class="reserva_individual">
                    <tr><th>#Codigo</th> <th>Valor R$</th> <th>Data e hora da reserva</th> <th>Ingressos</th></tr>
                    <?php $pos = 0; while($row = mysqli_fetch_assoc($resultado)) { 
                        $codr = $row["codReserva"]; 
                        $result_s = $conn->query("SELECT COUNT(*) as count  FROM `ingressos` WHERE `codReserva` = '$codr';");
                        $ingressos = mysqli_fetch_object($result_s)->count;
                        ?>
                        <tr><td>#<?php echo $row["codReserva"]; ?></td> <th style="color: #19B519;"><?php echo formatarDinheiro($row["Valor"]); ?> </th> <td><?php echo converterData($row["DataReserva"])." às ".removerSegundos($row["HoraReserva"]); ?></td>
                        <td><?php echo $ingressos . " unidades"; ?></td>
                        </tr>
                    <?php $pos ++; } ?>
                    </table>
                    <?php } ?>
                        <div class="image-container" onclick="hideImage()">
                            <img class="image" src="" alt="Imagem">
                            <button class="close-button" onclick="hideImage()">X</button>
                        </div>        
                </div>
            </div>
        </div>                      
    </main>
</body>
</html>
<script>
    document.getElementById("aprovacoesBtn").addEventListener("click", function() {
        document.getElementById("aprovacoes").style.display = "block"; 
        document.getElementById("aprovados").style.display = "none";
    });
    document.getElementById("aprovadosBtn").addEventListener("click", function() {
        document.getElementById("aprovados").style.display = "block"; 
        document.getElementById("aprovacoes").style.display = "none";
    });
</script>