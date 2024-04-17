<?php 
    require_once("conexao.php");
    $AdminLevel = 0;
    session_start();
    if (isset($_SESSION['__DVC_DadosLogin'])) {
        $DadosLogin = $_SESSION['__DVC_DadosLogin'];
        $AdminLevel = $DadosLogin['Admin'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Inserir post</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
    }
    .centro {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 10px;
    }
    
    input[type="text"], input[type="time"], input[type="date"], textarea, select {
        padding: 8px;
        border-radius: 8px;
        border: 2px solid #9370DB;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }

    input[type="time"], select, input[type="date"] {
        max-width: 15%;
    }

    input[type="submit"] {
        border: 2px solid #9370DB;
        padding: 10px;
        background-color: #fff;
        border-radius: 6px;
        font-weight: bold;
        font-size: 1.1rem;
        text-transform: uppercase;
        margin-bottom: 30px;
        margin-top: 10px;
    }

    input[type="submit"]:hover {
        cursor: pointer;
        background-color: #9370DB;
    }

    textarea {
        resize: none;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    input, textarea {
        margin-bottom: 10px;
    }
</style>
<body class="binserir">
    <?php CriarHeader(3, $DadosLogin); ?>
    <div class="centro">
    <?php if($AdminLevel == 1) { ?>
        <form action="conexao.php" method="post" name="INSERIR_POST">
            <span>Titulo</span>
            <input type="text" name="Titulo" placeholder="TITULO" size="123" autocomplete="off" required>
            
            <span>Descrição do evento</span>
            <textarea type="text" name="Conteudo" placeholder="CONTEUDO" cols="120" rows="30" autocomplete="off" required></textarea>
            
            <span>Imagem na vertical</span>
            <input type="text" name="Imagem" placeholder="LINK DA IMAGEM" size="123" autocomplete="off" required>

            <span>Imagem na horizontal (banner)</span>
            <input type="text" name="Banner" placeholder="LINK DA IMAGEM" size="123" autocomplete="off" required>

            <span>Valor R$ (0.00)</span>
            <input type="text" name="Valor" placeholder="PREÇO EM REAIS (ex: 0.00)" size="123" autocomplete="off" required>

            <span>Quantidade de ingressos disponíveis</span>
            <input type="text" name="qtdIngressos" placeholder="QUANTIDADE MÁXIMA DE INGRESSOS" size="40" autocomplete="off" required>

            <span>Local do evento</span>
            <input type="text" name="Local" placeholder="LOCAL DO EVENTO" size="40" autocomplete="off" required>

            <span>Data do evento</span>
            <input type="date" name="Data" placeholder="DATA DO EVENTO" size="40" autocomplete="off" required>

            <span>Horário do evento</span>
            <input type="time" name="Hora" placeholder="HORA DO EVENTO" size="40" autocomplete="off" required>
            
            <span>Classificação indicativa</span>
            <select id="informacoes" name="classificacao">
                <option value="L">Livre (L)</option>
                <option value="+10">+10</option>
                <option value="+12">+12</option>
                <option value="+14">+14</option>
                <option value="+16">+16</option>
                <option value="+18">+18</option>
            </select>  

            <input type="hidden" name="acao" value="INSERIR_POST">
            <input type="submit" name="Enviar" value="Enviar">
            
        </form></div>
    <?php } 
        else { 
            echo "<font size='10px' color='red'>Você não possui permissão</font><br><br>  "; 
            echo "<font size='6px'><a href='index.php'>Voltar ao Inicio</a>";
        } ?>
</body>
</html>