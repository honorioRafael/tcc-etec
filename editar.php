<?php
    require_once("conexao.php");
    $postid = $_GET['evento'];
    
    $sql = "SELECT * FROM posts WHERE codPostagem = $postid";
    $todos_produtos = $conn->query($sql);

    $AdminLevel = 0;
    $DadosLogin = null;
    session_start();
    if (isset($_SESSION['__DVC_DadosLogin'])) {
        $DadosLogin = $_SESSION['__DVC_DadosLogin'];
        $AdminLevel = $DadosLogin['Admin'];
    }

    $total = 0; while($row = mysqli_fetch_assoc($todos_produtos)) { $total ++;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="evento.css">
    <title><?php echo "Divulgarça: ".$row["Titulo"]; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        input[type="text"], input[type="time"], input[type="date"], a.remover, textarea, select {
            padding: 8px;
        }
        .conteudo {
            gap: 10px;
            margin-top: 20px;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: #9370DB;
            border-radius: 5px;
            border: none;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php CriarHeader(2, $DadosLogin); ?>
    <div class="eventos_show"> 
            <div class="evento_show">
            <a href="index.php"><svg width="40px" height="40px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 12H18M6 12L11 7M6 12L11 17" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg></a> 

            <form action="conexao.php" method="POST" name="EDITAR">
            <label style="margin-left:30px">Titulo</label>  
            <input name="Titulo" size="55" style="font-size: 2rem; max-width: 80%;" type="text" value="<?php echo $row["Titulo"]; ?>"><br>
                <div class="conteudo">
                    <label>Imagem</label>   
                    <input name="Imagem" type="text" value="<?php echo $row["Imagem"]; ?>">
                    <label>Conteudo</label> 
                    <textarea name="Conteudo" cols="80" rows="20"><?php echo $row["Conteudo"]; ?></textarea>
                </div>             
            </div>
            <div class="evento_disponibilidade" style="height: auto;">
                <table>
                    <tr><td class="left">Ingressos disponíveis:</td><td class="right"><input name="TotalIngressos" type="text" value=<?php echo $row["qtdIngressos"].' / '.$row["TotalIngressos"]; ?>></tr>
                    <tr><td class="left">Data:</td><td class="right"><input name="Data" type="date" value="<?php echo $row["Data"]; ?>"></tr>
                    <tr><td class="left">Hora:</td><td class="right"><input name="Horario" type="time" value="<?php echo removerSegundos($row["Horario"]); ?>"></tr>
                    <tr><td class="left">Local:</td><td class="right"><input name="Local" type="text" value="<?php echo $row["Local"]; ?>"></tr>
                    <tr><td class="left">Valor:</td><td class="right"><input name="Valor" type="text" value="<?php echo $row["Valor"]; ?>"></tr>
                    <tr><td class="left">Classificação indicativa:</td><td class="right"><select id="informacoes" name="classificacao" autofocus="+10">
                        <option value="L">Livre (L)</option>
                        <option value="+10">+10</option>
                        <option value="+12">+12</option>
                        <option value="+14">+14</option>
                        <option value="+16">+16</option>
                        <option value="+18">+18</option>
                    </select></tr>
                </table>
                <input type="hidden" name="postid" value="<?php echo $postid; ?>">
                <input type="hidden" name="acao" value="EDITAR">
                <input type="submit" value="CONCLUIR EDIÇÃO">
            
        </form>
        <a class="remover" href="remover.php?alsjakshikbdabgdjabd=<?php echo $postid; ?>">REMOVER</a></div>
        <?php } 
            if($total == 0) { echo "<font size='10px' color='red'>Não foi possivel localizar o post solicitado :(</font><br><br>"; 
            echo "<font size='6px'><a href='index.php'>Voltar ao Inicio</a>";
            }
        ?>
    </div>
</body>
</html>
