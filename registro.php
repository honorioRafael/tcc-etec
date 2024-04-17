<?php 
    require("conexao.php");
    global $ip, $username, $senha, $db;
    $con =  new mysqli($ip, $username, $senha, $db);

    $mensagem = 0;
    if(isset($_POST["Nome"])) {
        $email = mysqli_real_escape_string($con, $_POST["Email"]); 
        $query = "SELECT Email FROM usuario WHERE Email = '$email'";
        $consulta = mysqli_query($con, $query);
        if($consulta){
            $num_rows = mysqli_num_rows($consulta); 
            if ($num_rows > 0) $mensagem = 1;
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
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css">
    <title>Registro</title>
</head>
<body>
    <?php CriarHeader(5, null); ?>  
    <div class="centro">
        <div class="login">
            <h1>REGISTRO</h1>
            <form action="registro.php" method="post" name="REGISTRO">
                <?php if(isset($_POST["Nome"])) { ?>
                    <input type="email" name="Email" placeholder="E-Mail" size="60" autocomplete="on" value=<?php echo $_POST["Email"]; ?> required>
                    <input type="text" name="Nome" placeholder="Nome" size="60" autocomplete="on" value=<?php echo $_POST["Nome"]; ?> required>
                    <input type="text" name="Senha" placeholder="Senha" size="60" autocomplete="on" value=<?php echo $_POST["Senha"]; ?> required>
                    <input type="text" name="Telefone" placeholder="Telefone" size="60" autocomplete="on" value=<?php echo $_POST["Telefone"]; ?> required>
                <?php } else { ?>
                    <input type="email" name="Email" placeholder="E-Mail" size="60" autocomplete="on" required>
                    <input type="text" name="Nome" placeholder="Nome" size="60" autocomplete="on" required>
                    <input type="text" name="Senha" placeholder="Senha" size="60" autocomplete="on" required>
                    <input type="text" name="Telefone" placeholder="Telefone" size="60" autocomplete="on" required>
                <?php } ?>
                <input type="submit" value="REGISTRAR" name="Registrar">
            </form>
            <p><a href="login.php">Já tem uma conta? Faça login</a></p>
            <?php if($mensagem == 1) {?>
            <div class="alerta" style="background-color: #FFD6D6; border-color: #FF4D4D; padding: 0px 10px;">
                <svg width="20px" height="20px" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.8809 16.15C10.8809 16.0021 10.9101 15.8556 10.967 15.7191C11.024 15.5825 11.1073 15.4586 11.2124 15.3545C11.3175 15.2504 11.4422 15.1681 11.5792 15.1124C11.7163 15.0567 11.8629 15.0287 12.0109 15.03C12.2291 15.034 12.4413 15.1021 12.621 15.226C12.8006 15.3499 12.9399 15.5241 13.0211 15.7266C13.1024 15.9292 13.122 16.1512 13.0778 16.3649C13.0335 16.5786 12.9272 16.7745 12.7722 16.9282C12.6172 17.0818 12.4204 17.1863 12.2063 17.2287C11.9922 17.2711 11.7703 17.2494 11.5685 17.1663C11.3666 17.0833 11.1938 16.9426 11.0715 16.7618C10.9492 16.5811 10.8829 16.3683 10.8809 16.15ZM11.2408 13.42L11.1008 8.20001C11.0875 8.07453 11.1008 7.94766 11.1398 7.82764C11.1787 7.70761 11.2424 7.5971 11.3268 7.5033C11.4112 7.40949 11.5144 7.33449 11.6296 7.28314C11.7449 7.2318 11.8697 7.20526 11.9958 7.20526C12.122 7.20526 12.2468 7.2318 12.3621 7.28314C12.4773 7.33449 12.5805 7.40949 12.6649 7.5033C12.7493 7.5971 12.813 7.70761 12.8519 7.82764C12.8909 7.94766 12.9042 8.07453 12.8909 8.20001L12.7609 13.42C12.7609 13.6215 12.6809 13.8149 12.5383 13.9574C12.3958 14.0999 12.2024 14.18 12.0009 14.18C11.7993 14.18 11.606 14.0999 11.4635 13.9574C11.321 13.8149 11.2408 13.6215 11.2408 13.42Z" fill="#000000"/>
                    <path d="M12 21.5C17.1086 21.5 21.25 17.3586 21.25 12.25C21.25 7.14137 17.1086 3 12 3C6.89137 3 2.75 7.14137 2.75 12.25C2.75 17.3586 6.89137 21.5 12 21.5Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p>Esse e-mail ja está em uso!</p>
            </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>