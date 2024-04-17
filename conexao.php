<?php
require_once("header.php");
$ip  = "localhost";
$username = "root";
$senha = "";
$db = "tcc";

$conn = new mysqli($ip, $username, $senha, $db);


if(isset($_POST["acao"])) {
    if($_POST["acao"]=="INSERIR_POST") {
        inserirPost();
    }
    if($_POST["acao"]=="REMOVER") {
        removerPost();
    }
    if($_POST["acao"]=="EDITAR") {
        editarPost();
    }
    if($_POST["acao"]=="COMPRAR") {
        RealizarCompra(); 
    }
    if($_POST["acao"]=="EDITAR_CONTA") {
        EditarConta(); 
    }
}

function inserirPost() {
    global $ip, $username, $senha, $db;
    $con =  new mysqli($ip, $username, $senha, $db);
    $sql = "INSERT INTO posts(Titulo, Conteudo, Imagem, Banner, qtdIngressos, TotalIngressos, `Data`, Horario, Valor, `Local`, Classificacao) VALUES ('{$_POST["Titulo"]}', '{$_POST["Conteudo"]}', '{$_POST["Imagem"]}', '{$_POST["Banner"]}', '{$_POST["qtdIngressos"]}',  '{$_POST["qtdIngressos"]}', '{$_POST["Data"]}', '{$_POST["Hora"]}', '{$_POST["Valor"]}',  '{$_POST["Local"]}', '{$_POST["classificacao"]}');";
    $con->query($sql);
    $con->close();
    header("Location:index.php");
}

function removerPost() {
    global $ip, $username, $senha, $db;
    $con =  new mysqli($ip, $username, $senha, $db);
    $postid = $_POST['evento'];
    $sql = "DELETE FROM `posts` WHERE `posts`.`codPostagem` = $postid";
    $con->query($sql);
    $con->close();
    header("Location:index.php");
}

function editarPost() {
    $hora = removerSegundos($_POST["Horario"]);
    global $ip, $username, $senha, $db;
    $con =  new mysqli($ip, $username, $senha, $db);
    $sql = "UPDATE `posts` SET `Titulo`='{$_POST["Titulo"]}', `Conteudo`='{$_POST["Conteudo"]}', `Imagem`='{$_POST["Imagem"]}', `qtdIngressos`='{$_POST["TotalIngressos"]}', `TotalIngressos`='{$_POST["TotalIngressos"]}', `Data`='{$_POST["Data"]}', `Horario`='$hora', `Local`='{$_POST["Local"]}', `Valor`='{$_POST["Valor"]}', `Classificacao`='{$_POST["classificacao"]}' WHERE `codPostagem`='{$_POST["postid"]}';";
    $con->query($sql);
    $con->close();
    header("Location:index.php");
}

function editarConta() {
    global $ip, $username, $senha, $db;
    $con =  new mysqli($ip, $username, $senha, $db);
    $sql = "UPDATE `usuario` SET `Nome`='{$_POST["Nome"]}', `Senha`='{$_POST["Senha"]}', `Email`='{$_POST["Email"]}', `Telefone`='{$_POST["Telefone"]}' WHERE `codUsuario`='{$_POST["codUsuario"]}';";
    $con->query($sql);
    $con->close();
    header("Location:conta.php?dados_alterados=true");
}

function RealizarCompra() {
    global $conn;
    session_start();
    if(!isset($_SESSION['__DVC_DadosLogin'])) {  
        header("Location:login.php");
    }   

    try {
        $postid = $_POST['evento'];
        if($_POST['mais'] == 0) {
            header("Location:reserva.php?evento=$postid&err=1");
            return 1;
        }
        // Verificar se da pra compar
        $resultado = $conn->query("SELECT `qtdIngressos` FROM `tcc`.`posts` WHERE `codPostagem` = '$postid';");
        $ingressos_disp = mysqli_fetch_object($resultado)->qtdIngressos;

        if($_POST['inteiros'] + $_POST['meios'] > $ingressos_disp) {
            header("Location:reserva.php?evento=$postid&err=2");
            return 1;
        }
        else {
            $ingressos_desc = $ingressos_disp - ($_POST['inteiros'] + $_POST['meios']);
        }
        // Efetua o desconto de ingressos
        $resultado = $conn->query("UPDATE `tcc`.`posts` SET  `qtdIngressos` = ' $ingressos_desc'  WHERE `codPostagem` = '$postid';");
        
        $DadosLogin = $_SESSION['__DVC_DadosLogin'];
        $contaId = $DadosLogin['codUsuario'];

        $data = date('Y-m-d');
        $hora = date('H:i:s');

        $sql3 = "SELECT `Valor` FROM `tcc`.`posts` WHERE `codPostagem`='$postid';";
        $resultado = $conn->query($sql3);
        $Valor = mysqli_fetch_object($resultado)->Valor;

        $ValorMeio = $Valor / 2;
        $ValorTotal = $Valor * $_POST['inteiros'];
        $ValorTotal += $ValorMeio * $_POST['meios'];

        $sql = "INSERT INTO `tcc`.`reservas`(`codPostagem`, `codUsuario`, `DataReserva`, `HoraReserva`, `Valor`) VALUES ('$postid', '$contaId', '$data', '$hora', '$ValorTotal');";
        $conn->query($sql);
        
        $sql2 = "SELECT `codReserva` FROM `tcc`.`reservas` WHERE `codPostagem`='$postid' AND `codUsuario`='$contaId' ORDER BY `codReserva` desc LIMIT 1;";
        $resultado = $conn->query($sql2);
        $codReserva = mysqli_fetch_object($resultado)->codReserva;

        if($_POST['inteiros'] > 0) {
            for($i = 0; $i < $_POST['inteiros']; $i++) {
                $sql = "INSERT INTO `tcc`.`ingressos`(`codReserva`, `codUsuario`, `Tipo`, `ValorUnt`) VALUES ('$codReserva', '$contaId', 'PADRÃO', '$Valor');";
                $conn->query($sql); 
            }
        }
        if($_POST['meios'] > 0) {
            for($i = 0; $i < $_POST['meios']; $i++) {
                $sql = "INSERT INTO `tcc`.`ingressos`(`codReserva`, `codUsuario`, `Tipo`, `ValorUnt`) VALUES ('$codReserva', '$contaId', 'MEIO', '$ValorMeio');";
                $conn->query($sql); 
            }
        }
        header("Location:confirmarCompra.php?err=0");
    }
    catch (Exception $e) {
        header("Location:confirmarCompra.php?err=1");
    }
}

function converterData($dataNoFormatoAAAAMMDD) {
    // Converte a data para o formato timestamp
    $timestamp = strtotime($dataNoFormatoAAAAMMDD);

    // Formata a data no novo formato
    $novaData = date('d/m/Y', $timestamp);

    return $novaData;
}

function converterDataParaAAAAMMDD($dataNoFormatoDDMMAAAA) {
    // Converte a data para o formato timestamp
    $timestamp = strtotime($dataNoFormatoDDMMAAAA);

    // Formata a data no novo formato
    $novaData = date('Y-m-d', $timestamp);

    return $novaData;
}

function removerSegundos($horaCompleta) {
    // Divide a string em horas, minutos e segundos
    $tempo = explode(':', $horaCompleta);

    // Verifica se os índices esperados estão definidos
    if (isset($tempo[0], $tempo[1])) {
        // Retorna a hora no formato "HH:MM"
        return $tempo[0] . ':' . $tempo[1];
    } else {
        // Se os índices não estiverem definidos, retorna a string original
        return $horaCompleta;
    }
}


/*
function RegisrarUsuario() {
    global $ip, $username, $senha, $db;
    $con =  new mysqli($ip, $username, $senha, $db);

    $email = mysqli_real_escape_string($con, $_POST["Email"]); 
    $query = "SELECT Email FROM usuario WHERE Email = '$email'";
    $consulta = mysqli_query($con, $query);
    if($consulta){
        $num_rows = mysqli_num_rows($consulta); 
        if ($num_rows > 0) {
            echo 'Esse email ja está em uso!<br>';
            echo '<a href="Registro.php">Retornar</a>';
        }
        else {
            $sql = "INSERT INTO usuario(Nome, Email, Senha, Telefone) VALUES ('{$_POST["Nome"]}', '{$_POST["Email"]}', '{$_POST["Senha"]}', '{$_POST["Telefone"]}');";
            $con->query($sql);
            $con->close(); 
            header("Location:Login.php?pr=1");
        }
    } 
    else {
        echo "Erro na consulta: " . mysqli_error($con);
    }  
}*/

/*function LogarUsuario() {
    global $ip, $username, $senha, $db;
    $con =  new mysqli($ip, $username, $senha, $db);

    $email = mysqli_real_escape_string($con, $_POST["email"]); 
    $query = "SELECT Email FROM usuario WHERE Email = '$email'";
    $consulta = mysqli_query($con, $query);
    if($consulta){
        $num_rows = mysqli_num_rows($consulta); 
        $consulta->close();   
        if ($num_rows > 0) {
            $sql = mysqli_query($con, "SELECT Senha FROM usuario WHERE Email = '$email';");     
            $senha = mysqli_fetch_object($sql)->Senha;
            if($_POST["senha"] == $senha) {
                $result = mysqli_query($con, "SELECT codUsuario, Nome, Senha, Email, Admin FROM usuario WHERE Email = '$email'");  
                session_start(); 
                $_SESSION['DadosLogin'] = mysqli_fetch_assoc($result);
                header("Location:index.php");
                exit();
            }
            else {
                echo"Senha inválida!<br>";
                echo '<a href="Login.php">Retornar</a>';
            }
            $sql->close();     
        }
        else {
            echo 'Essa conta não existe!<br>';
            echo '<a href="Login.php">Retornar</a>';
        }
    } 
    else {
        echo "Erro na consulta: " . mysqli_error($con);
    } 
}*/

?>