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
    if(isset($_GET["dados_alterados"])) {
        $result = mysqli_query($conn, "SELECT * FROM `usuario` WHERE `codUsuario` = '{$DadosLogin["codUsuario"]}'");  
        $_SESSION['__DVC_DadosLogin'] = mysqli_fetch_assoc($result);
        $DadosLogin = $_SESSION['__DVC_DadosLogin'];
    } 

    $contaId = $DadosLogin['codUsuario'];

    $con =  new mysqli($ip, $username, $senha, $db);
    $eventos = array();
    $resultado3 = $con->query("SELECT `codPostagem` FROM `tcc`.`reservas` WHERE `codUsuario`='$contaId' ORDER BY `codReserva` desc;");
    $totalreservas = 0;
    while($row = mysqli_fetch_assoc($resultado3)) {
        $totalreservas ++;
        $postid = $row["codPostagem"];
        $sql2 = "SELECT `Titulo`,`CodPostagem` FROM `tcc`.`posts` WHERE `codPostagem`='$postid'";
        $resultad2 = $con->query($sql2);
        array_push($eventos, mysqli_fetch_assoc($resultad2));
    }
    $con->close();
    
    $sql2 = "SELECT `codFavorito`, `codPostagem` FROM `tcc`.`favoritos` WHERE `codUsuario`='$contaId' ORDER BY `codFavorito` desc;";
    $resultado = $conn->query($sql2);
    $totalfavoritos = 0;
    $favoritos = array();
    while($row = mysqli_fetch_assoc($resultado)) {
        $totalfavoritos ++;
        $codFavorito = $row["codFavorito"];
        $postid = $row["codPostagem"];
        $sql2 = "SELECT `Titulo`,`Imagem`,`codPostagem` FROM `tcc`.`posts` WHERE `codPostagem`='$postid'";
        $resultad = $conn->query($sql2);
        array_push($favoritos, mysqli_fetch_assoc($resultad));
    }

    $sql2 = "SELECT * FROM `tcc`.`reservas` WHERE `codUsuario`='$contaId' ORDER BY `codReserva` desc;";
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
        overflow: hidden;
    }
    .content {
        width: 80%;
        height: 100%;
        overflow: auto;
    }
    .opcoes {
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
        
        margin-bottom: 10px;
    }

    td,th {
        padding: 10px; /* Espaçamento interno dentro das células */
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        max-width: 16.66%;
        overflow: hidden;
        white-space: nowrap;
    }
    
    table {
      border-collapse: collapse;
      width: 100%;
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
        white-space: nowrap;
        align-items: center;
        border: none;
        cursor: pointer;
        width: auto;
    }

    .opcoes li {
        margin-bottom: 15px;
    }

    input[type="text"], input[type="email"] {
        font-family: arial;
    }

    .botao-p {
        align-items: center;
        justify-items: center;
    }
    
    .botao-p svg {
        margin: 0 3px;
        margin-top: 3px;
    }

    .interrogacao {
        border-radius: 50%;
        padding: 1px 4px;
        border: 1px solid white;
        color: white;
        line-height: 1px;  
    }

    @media (max-width: 868px) {  
        main {
            flex-direction: column;
            height: 100vh;  
        }
        .opcoes, .content  {
            width: 80%;
            height: 100%;  
        }
        .opcoes {          
            height: 160px;
            max-height: 160px;
            padding: 20px;
        }
        table {
            width: 40%;
        }
    }
</style>
<body>
<?php CriarHeader(4, $DadosLogin); ?>
    <main>
        <div class="opcoes">
            <ul>
                <li><button id="IngressosBtn" class="opc-btn">Minhas Reservas</button></li>
                <li><button id="favoritosBtn" class="opc-btn">Favoritos</button></li>
                <li><button id="configsBtn" class="opc-btn">Configurações</button></li>
            </ul>
        </div>
        
        <div class="content">
            <div class="ingressos" id="ingressos" style="display: none; overflow: auto;">
            <?php if($totalreservas == 0) { echo"<div class='alerta' style='background-color: #FFD6D6; border-color: #FF4D4D; padding: 10px 10px;'>
                <svg width='20px' height='20px' viewBox='-0.5 0 25 25' fill='none' xmlns='http://www.w3.org/2000/svg'>
                    <path d='M10.8809 16.15C10.8809 16.0021 10.9101 15.8556 10.967 15.7191C11.024 15.5825 11.1073 15.4586 11.2124 15.3545C11.3175 15.2504 11.4422 15.1681 11.5792 15.1124C11.7163 15.0567 11.8629 15.0287 12.0109 15.03C12.2291 15.034 12.4413 15.1021 12.621 15.226C12.8006 15.3499 12.9399 15.5241 13.0211 15.7266C13.1024 15.9292 13.122 16.1512 13.0778 16.3649C13.0335 16.5786 12.9272 16.7745 12.7722 16.9282C12.6172 17.0818 12.4204 17.1863 12.2063 17.2287C11.9922 17.2711 11.7703 17.2494 11.5685 17.1663C11.3666 17.0833 11.1938 16.9426 11.0715 16.7618C10.9492 16.5811 10.8829 16.3683 10.8809 16.15ZM11.2408 13.42L11.1008 8.20001C11.0875 8.07453 11.1008 7.94766 11.1398 7.82764C11.1787 7.70761 11.2424 7.5971 11.3268 7.5033C11.4112 7.40949 11.5144 7.33449 11.6296 7.28314C11.7449 7.2318 11.8697 7.20526 11.9958 7.20526C12.122 7.20526 12.2468 7.2318 12.3621 7.28314C12.4773 7.33449 12.5805 7.40949 12.6649 7.5033C12.7493 7.5971 12.813 7.70761 12.8519 7.82764C12.8909 7.94766 12.9042 8.07453 12.8909 8.20001L12.7609 13.42C12.7609 13.6215 12.6809 13.8149 12.5383 13.9574C12.3958 14.0999 12.2024 14.18 12.0009 14.18C11.7993 14.18 11.606 14.0999 11.4635 13.9574C11.321 13.8149 11.2408 13.6215 11.2408 13.42Z' fill='#000000'/>
                    <path d='M12 21.5C17.1086 21.5 21.25 17.3586 21.25 12.25C21.25 7.14137 17.1086 3 12 3C6.89137 3 2.75 7.14137 2.75 12.25C2.75 17.3586 6.89137 21.5 12 21.5Z' stroke='#000000' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/>
                </svg>
                <p>Você ainda não reservou nenhum evento. <a href='index.php'>Clique aqui para reservar o primeiro</a></p>
            </div>"; } else { ?>
                <div id="reserva">
                <h1>Reservas</h1> 
                    <table class="reserva_individual">
                    <tr><th>#Codigo</th><th>Evento</th> <th>Data e hora da reserva</th> <th>Valor R$</th> <th >Situação</th> <th></th></tr>
                    <?php $pos = 0; while($row = mysqli_fetch_assoc($resultado)) { ?>
                        <tr><td>#<?php echo $row["codReserva"]; ?></td> <td style="white-space: wrap;"><a href="evento.php?evento=<?php echo $eventos[$pos]["CodPostagem"]; ?>"><?php echo $eventos[$pos]["Titulo"]; ?></a></td> <td><?php echo converterData($row["DataReserva"])." às ".removerSegundos($row["HoraReserva"]); ?></td> <td><?php echo formatarDinheiro($row["Valor"]); ?></td> 
                        <td><?php echo $row["Aprovado"];?></td><td <?php if($row["Aprovado"] == "PAGAMENTO RECUSADO") echo "title='Seu pagamento foi reprovado por um moderador. Para mais informações, entre em contato em divulgarca.com.br/contato'"; ?> class="botao-p"><?php if($row["Aprovado"] == "AGUARDANDO PAGAMENTO") { ?> <a class="btnpagar" href="pagarEvento.php?reserva=<?php echo $row['codReserva']; ?>">Pagar</a> <?php } 
                        
                        if($row["Aprovado"] == "PAGAMENTO RECUSADO") { ?> <a class="btnpagar" style="background-color: #eead2d;" href="pagarEvento.php?reserva=<?php echo $row['codReserva']; ?>">Pagar <span class="interrogacao">?</span></a> <?php } ?>
                        
                    </td></tr>	
                    <?php $pos ++; } ?>
                    </table>
                    </div>
                    <?php } ?>                  
                </div>
                
                    <div class="ingressos" id="configs" <?php if(isset($_GET["dados_alterados"])) { ?>style="display: block;" <?php } else { ?>style="display: none;" <?php } ?>>
                    <div id="reserva">
                    <h1>Configurações</h1>
                    <div class="eventos"> 
                        <form action="conexao.php" method="POST" name="EDITAR">
                            <div class="config-opc">
                                <label>Nome</label>
                                <input class="dado-opc" type="text" name="Nome" value="<?php echo $DadosLogin["Nome"]; ?>" placeholder="Altere seu nome" required>
                            </div>
                            <div class="config-opc">
                                <label>Senha</label>
                                <div class="senha-opc">
                                    <input type="password" style="max-width:100%; width: 100%; margin: 0;"  id="senha" name="Senha" value="<?php echo $DadosLogin["Senha"]; ?>" placeholder="Altere sua senha" required>
                                    <input type="checkbox" style="width: auto; margin: 5px;" id="passwordToggle" onclick="togglePassword()">
                                </div>
                            </div>
                            <div class="config-opc">
                                <label>Email</label>
                                <input class="dado-opc" type="email" name="Email" value="<?php echo $DadosLogin["Email"]; ?>" placeholder="Altere seu email" required>
                            </div>
                            <div class="config-opc">
                                <label>Telefone</label>
                                <input class="dado-opc" type="text" name ="Telefone" value="<?php echo $DadosLogin["Telefone"]; ?>" placeholder="Altere seu telefone" required>
                            </div>
                            <div class="config-opc">
                                <input type="hidden" name="codUsuario" value="<?php echo $DadosLogin["codUsuario"]; ?>">
                                <input type="hidden" name="acao" value="EDITAR_CONTA">
                                <input type="submit" value="Salvar alterações" class="salvar">
                            </div>
                        </form>
                        <br><br>
                        <?php if(isset($_GET["dados_alterados"])) { ?>
                        <div class="alerta">
                            <svg width="20px" height="20px" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.8809 16.15C10.8809 16.0021 10.9101 15.8556 10.967 15.7191C11.024 15.5825 11.1073 15.4586 11.2124 15.3545C11.3175 15.2504 11.4422 15.1681 11.5792 15.1124C11.7163 15.0567 11.8629 15.0287 12.0109 15.03C12.2291 15.034 12.4413 15.1021 12.621 15.226C12.8006 15.3499 12.9399 15.5241 13.0211 15.7266C13.1024 15.9292 13.122 16.1512 13.0778 16.3649C13.0335 16.5786 12.9272 16.7745 12.7722 16.9282C12.6172 17.0818 12.4204 17.1863 12.2063 17.2287C11.9922 17.2711 11.7703 17.2494 11.5685 17.1663C11.3666 17.0833 11.1938 16.9426 11.0715 16.7618C10.9492 16.5811 10.8829 16.3683 10.8809 16.15ZM11.2408 13.42L11.1008 8.20001C11.0875 8.07453 11.1008 7.94766 11.1398 7.82764C11.1787 7.70761 11.2424 7.5971 11.3268 7.5033C11.4112 7.40949 11.5144 7.33449 11.6296 7.28314C11.7449 7.2318 11.8697 7.20526 11.9958 7.20526C12.122 7.20526 12.2468 7.2318 12.3621 7.28314C12.4773 7.33449 12.5805 7.40949 12.6649 7.5033C12.7493 7.5971 12.813 7.70761 12.8519 7.82764C12.8909 7.94766 12.9042 8.07453 12.8909 8.20001L12.7609 13.42C12.7609 13.6215 12.6809 13.8149 12.5383 13.9574C12.3958 14.0999 12.2024 14.18 12.0009 14.18C11.7993 14.18 11.606 14.0999 11.4635 13.9574C11.321 13.8149 11.2408 13.6215 11.2408 13.42Z" fill="#000000"/>
                                <path d="M12 21.5C17.1086 21.5 21.25 17.3586 21.25 12.25C21.25 7.14137 17.1086 3 12 3C6.89137 3 2.75 7.14137 2.75 12.25C2.75 17.3586 6.89137 21.5 12 21.5Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <p>Dados alterados com sucesso!</p>
                        </div>
                        <?php } ?>
                </div>
            </div>
            </div>
            <div class="ingressos" id="favoritos" style="overflow-y:auto; display: none;">
            <?php if($totalfavoritos == 0) echo"<div class='alerta' style='background-color: #FFD6D6; border-color: #FF4D4D; padding: 10px 10px;'>
                <svg width='20px' height='20px' viewBox='-0.5 0 25 25' fill='none' xmlns='http://www.w3.org/2000/svg'>
                    <path d='M10.8809 16.15C10.8809 16.0021 10.9101 15.8556 10.967 15.7191C11.024 15.5825 11.1073 15.4586 11.2124 15.3545C11.3175 15.2504 11.4422 15.1681 11.5792 15.1124C11.7163 15.0567 11.8629 15.0287 12.0109 15.03C12.2291 15.034 12.4413 15.1021 12.621 15.226C12.8006 15.3499 12.9399 15.5241 13.0211 15.7266C13.1024 15.9292 13.122 16.1512 13.0778 16.3649C13.0335 16.5786 12.9272 16.7745 12.7722 16.9282C12.6172 17.0818 12.4204 17.1863 12.2063 17.2287C11.9922 17.2711 11.7703 17.2494 11.5685 17.1663C11.3666 17.0833 11.1938 16.9426 11.0715 16.7618C10.9492 16.5811 10.8829 16.3683 10.8809 16.15ZM11.2408 13.42L11.1008 8.20001C11.0875 8.07453 11.1008 7.94766 11.1398 7.82764C11.1787 7.70761 11.2424 7.5971 11.3268 7.5033C11.4112 7.40949 11.5144 7.33449 11.6296 7.28314C11.7449 7.2318 11.8697 7.20526 11.9958 7.20526C12.122 7.20526 12.2468 7.2318 12.3621 7.28314C12.4773 7.33449 12.5805 7.40949 12.6649 7.5033C12.7493 7.5971 12.813 7.70761 12.8519 7.82764C12.8909 7.94766 12.9042 8.07453 12.8909 8.20001L12.7609 13.42C12.7609 13.6215 12.6809 13.8149 12.5383 13.9574C12.3958 14.0999 12.2024 14.18 12.0009 14.18C11.7993 14.18 11.606 14.0999 11.4635 13.9574C11.321 13.8149 11.2408 13.6215 11.2408 13.42Z' fill='#000000'/>
                    <path d='M12 21.5C17.1086 21.5 21.25 17.3586 21.25 12.25C21.25 7.14137 17.1086 3 12 3C6.89137 3 2.75 7.14137 2.75 12.25C2.75 17.3586 6.89137 21.5 12 21.5Z' stroke='#000000' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/>
                </svg>
                <p>Você ainda não favoritou nenhum evento.</p>
            </div>"; else { ?>
                <div id="reserva">
                <h1>Favoritos</h1>
                <div class="eventos">  
                <div class="linha-evento">   
                    <button onclick="autoScroll(2)" class="seta-1"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11 9L8 12M8 12L11 15M8 12H16M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg></button>
                    <div class="evento_linha" id="evento_linha">
                        <?php $linha = 0;
                            $todos_p = $conn->query("SELECT * FROM `tcc`.`favoritos` WHERE `codUsuario` = '$contaId';");
                            $pos = 0; while($row = mysqli_fetch_assoc($todos_p)) {
                            ?>    
                            <a href="evento.php?evento=<?php echo $favoritos[$pos]['codPostagem']; ?>">
                            <div class="evento">
                                <img title="<?php echo $favoritos[$pos]["Titulo"]; ?>" src=<?php echo $favoritos[$pos]["Imagem"]; ?> alt="Imagem" width="280" height="480">
                                <h1 style='font-size: 1.2rem;' title="<?php echo $favoritos[$pos]["Titulo"]; ?>"><?php echo $favoritos[$pos]["Titulo"]; ?></h1>
                            </div></a>
                        <?php $pos ++; } } ?>
                    </div>
                    <button onclick="autoScroll(1)" class="seta-2"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13 15L16 12M16 12L13 9M16 12H8M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg></button>
                </div>
            </div> 
        </div>
    </main>
</body>
</html>
<script>
    document.getElementById("IngressosBtn").addEventListener("click", function() {
        var conteudoElement = document.getElementById("ingressos");
        document.getElementById("favoritos").style.display = "none";
        document.getElementById("configs").style.display = "none"; 
        document.getElementById("ingressos").style.display = "block"; 
    });
    document.getElementById("favoritosBtn").addEventListener("click", function() {
        document.getElementById("ingressos").style.display = "none";
        document.getElementById("configs").style.display = "none"; 
        document.getElementById("favoritos").style.display = "block"; 
    });

    document.getElementById("configsBtn").addEventListener("click", function() {
        document.getElementById("ingressos").style.display = "none";
        document.getElementById("favoritos").style.display = "none";
        document.getElementById("configs").style.display = "block"; 

    });

    function togglePassword() {
      var passwordInput = document.getElementById("senha");

      if (passwordInput.type === "password") {
        passwordInput.type = "text";
      } else {
        passwordInput.type = "password";
      }
    }

    function autoScroll(side) {
        // Obter a div scrollable
        var div = document.getElementById("evento_linha");

        // Calcular a largura total da div
        var totalWidth = div.scrollWidth - div.clientWidth;

        // Adicionar 25% à posição atual de rolagem
        if(side == 1) var targetScrollLeft = div.scrollLeft + 0.25 * totalWidth;
        else var targetScrollLeft = div.scrollLeft - 0.25 * totalWidth;

        // Rolar para a nova posição calculada
        div.scrollLeft = targetScrollLeft;
    }

</script>