<?php
    require_once("conexao.php");
    $postid = $_GET['evento'];
    $data = date('Y-m-d');
    $hora = date('H:i:s');
    $disp = true;

    function VerDisponibilidade($qtd) {
        global $disp;
        if($qtd <= 0 || $disp == false) { 
            return "<h1 style='color: red; font-size: 2rem;'>INDISPONÍVEL</h1>";
        }
        else return "<h1 style='color: green; font-size: 2rem;'>DISPONÍVEL</h1>";
    }

    function formatarDinheiro($valor) {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }

    function corClassificacao($classificacao) {
        if($classificacao == "L") return "background-color: #00a54f;"; 
        if($classificacao == "+10") return "background-color: #00abf1;"; 
        if($classificacao == "+12") return "background-color: #ffc90a;"; 
        if($classificacao == "+14") return "background-color: #f78320;"; 
        if($classificacao == "+16") return "background-color: #ee1d23;"; 
        if($classificacao == "+18") return "background-color: #231f20;"; 
    }

    $sql = "SELECT * FROM posts WHERE codPostagem = $postid";
    $todos_produtos = $conn->query($sql);

    $favorito = false;
    $AdminLevel = 0;
    $DadosLogin = null;
    session_start();
    if (isset($_SESSION['__DVC_DadosLogin'])) {
        $DadosLogin = $_SESSION['__DVC_DadosLogin'];
        $AdminLevel = $DadosLogin['Admin'];
        $contaid = $DadosLogin['codUsuario'];
        $todos_produto = $conn->query("SELECT `codPostagem` FROM `tcc`.`favoritos` WHERE `codUsuario` = '$contaid'");
        while($row = mysqli_fetch_assoc($todos_produto)) { 
            if($row['codPostagem'] == $postid) {
                $favorito = true;
            }
        }

    }
    
    $total = 0; while($row = mysqli_fetch_assoc($todos_produtos)) { $total ++;
        if($row["Data"] == $data && $row["Horario"] <= $hora) {
            $disp = false;
        } 
        if($row["Data"] < $data) {
            $disp = false;
        }
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
    </style>
</head>
<body>
    <?php CriarHeader(2, $DadosLogin); ?>
    <div class="eventos_show"> 
            <div class="evento_show">
            <div class="botoes-menu">
                <div class="botoes-menu-esquerda">
                    <a href="index.php"><svg class="svg-sel" width="40px" height="40px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 12H18M6 12L11 7M6 12L11 17" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg></a>  


                    <?php if($favorito == false) { ?><a href="favoritar.php?evento=<?php echo $postid; ?>"><svg class="svg-sel" width="40px" height="40px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg></a>
                    <?php } else { ?>
                        <a href="favoritar.php?evento=<?php echo $postid; ?>&remover=t"><svg width="40px" height="40px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 9.1371C2 14 6.01943 16.5914 8.96173 18.9109C10 19.7294 11 20.5 12 20.5C13 20.5 14 19.7294 15.0383 18.9109C17.9806 16.5914 22 14 22 9.1371C22 4.27416 16.4998 0.825464 12 5.50063C7.50016 0.825464 2 4.27416 2 9.1371Z" fill="#1C274C"/>
                        </svg></a>
                    <?php } ?>
                </div>
                <div title="Classificação indicativa" class="classificacao-indicativa" style="<?php echo corClassificacao($row["Classificacao"]); ?>">
                    <span class="classificacao-indicativa-txt"><?php echo $row["Classificacao"]; ?></span>
                </div>
            </div>
                <h1 style='font-size: 2em;'><?php echo $row["Titulo"]; ?></h1><br>
                <div class="conteudo">
                    <img src=<?php echo $row["Imagem"]; ?> alt="Imagem"  width="300" height="480" onclick="showImage()">
                    <p><?php echo $row["Conteudo"]; ?></p>
                </div>
            <?php if($AdminLevel == 1) { ?> <a style="width: 60px;" class="remover" href="editar.php?evento=<?php echo $postid ?>">EDITAR</a> <?php } ?>
            </div>
            <div class="evento_disponibilidade">
                <div class="tabela">
                    <?php if(intval($row["Valor"]) != 0) { ?><div class="linha">
                        <span>Ingressos disponíveis:</span>
                        <span><?php echo $row["qtdIngressos"].' / '.$row["TotalIngressos"]; ?></span>
                    </div><?php } ?>
                    <div class="linha">
                        <span>Data:</span>
                        <span><?php echo converterData($row["Data"]); ?></span>
                    </div>
                    <div class="linha">
                        <span>Horário:</span>
                        <span><?php echo removerSegundos($row["Horario"]); ?></span>
                    </div>
                    <div class="linha">
                        <span>Local:</span>
                        <span><?php echo $row["Local"]; ?></span>
                    </div>
                    <div class="linha">
                        <span>Valor:</span>
                        <span style="color: #19B519; font-weight: bold;"><?php if(intval($row["Valor"]) != 0) { ?><?php echo formatarDinheiro($row["Valor"]); } else { echo "GRÁTIS"; } ?></span>
                    </div>
                </div>
                
                
                <?php echo VerDisponibilidade($row["qtdIngressos"]); ?>
                <?php if($disp == true) { if(intval($row["Valor"]) != 0) { ?>                    
                    <a class="reservar-btn" href="reserva.php?evento=<?php echo $postid ?>">RESERVE JÁ</a>
                <?php } } ?>
            </div>
            <div class="image-container" onclick="hideImage()">
                <img class="image" src="<?php echo $row["Imagem"]; ?>" alt="Imagem">
                <button class="close-button" onclick="hideImage()">X</button>
            </div>
        <?php } 
            if($total == 0) { echo "<font size='10px' color='red'>Não foi possivel localizar o post solicitado :(</font><br><br>"; 
            echo "<font size='6px'><a href='index.php'>Voltar ao Inicio</a>";
            }
        ?>
    </div><br>
    
    <script>
        function showImage(img) {
            var imageContainer = document.querySelector(".image-container");
            var image = document.querySelector(".image");
            var closeButton = document.querySelector(".close-button");

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
</body>
</html>
