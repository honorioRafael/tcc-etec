<?php
    require_once("conexao.php");
    session_start();
    if(!isset($_SESSION['__DVC_DadosLogin'])) {  
        header("Location:login.php");
    }

    $msg = 0;
    
    $postid = $_GET['evento'];
    $sql = "SELECT * FROM posts WHERE codPostagem = $postid";
    $todos_produtos = $conn->query($sql);
    
    $AdminLevel = 0;
    $DadosLogin = null;
    
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
    <link rel="stylesheet" href="reserva.css">
    <link rel="stylesheet" href="reserva.css">
    <title><?php echo "Reserva: ".$row["Titulo"]; ?></title>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var Total = document.getElementById('total');
        var ValorTotal = 0.0;
        var inputValorMais = document.getElementById('inteira');
        var buttonInteiraMais = document.getElementById('inteira_mais');
        var buttonInteiraMenos = document.getElementById('inteira_menos');
        var preco = document.getElementById('preco');
        var inteiros = document.getElementById('inteiros');
        var meios = document.getElementById('meios');
        var mais = document.getElementById('mais');

        var meiaValorMais = document.getElementById('meia');
        var buttonMeiaMais = document.getElementById('meia_mais');
        var buttonMeiaMenos = document.getElementById('meia_menos');

        function formatarDinheiro(valor) {
            return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }

        function CalcularTotal(tipo) {
            ValorTotal = 0.0;
            ValorTotal += <?php echo json_encode($row['Valor']); ?>* parseFloat(inputValorMais.value);
            ValorTotal += (<?php echo json_encode($row['Valor']); ?>/2)* parseFloat(meiaValorMais.value);
            Total.innerText = formatarDinheiro(ValorTotal);
            if(ValorTotal > 0) mais.value = 1;
            else mais.value = 0;

            inteiros.value = parseFloat(inputValorMais.value);
            meios.value = parseFloat(meiaValorMais.value);
        }

        // Inteira
        buttonInteiraMais.addEventListener('click', function () {
            var valorAtual = inputValorMais.value;
            if (!isNaN(valorAtual)) {
                valorAtual = parseInt(valorAtual) + 1;
            }
            else {
                valorAtual = 0;
            }
            inputValorMais.value = valorAtual;
            CalcularTotal(1); 
            
        });

        buttonInteiraMenos.addEventListener('click', function () {
            var valorAtual = inputValorMais.value;
            if (!isNaN(valorAtual) && parseInt(valorAtual) > 0)  {
                valorAtual = parseInt(valorAtual) - 1;
            }
            else {
                valorAtual = 0;
            }
            inputValorMais.value = valorAtual;
            CalcularTotal(1); 
        });

        // Meia
        buttonMeiaMais.addEventListener('click', function () {
            var valorAtual = meiaValorMais.value;
            if (!isNaN(valorAtual)) {
                valorAtual = parseInt(valorAtual) + 1;
            }
            else {
                valorAtual = 0;
            }
            meiaValorMais.value = valorAtual;
            CalcularTotal(2); 
        });

        buttonMeiaMenos.addEventListener('click', function () {
            var valorAtual = meiaValorMais.value;
            if (!isNaN(valorAtual) && parseInt(valorAtual) > 0)  {
                valorAtual = parseInt(valorAtual) - 1;
            }
            else {
                valorAtual = 0;
            }
            meiaValorMais.value = valorAtual;
            CalcularTotal(2); 
        });
    });
</script>
</head>
<body>
<?php CriarHeader(2, $DadosLogin); ?>
    <div class="eventos_show" style="margin-bottom: 30px;"> 
        <div class="evento_show">
            <a href="evento.php?evento=<?php echo $postid ?>"><svg class="svgbtn" width="40px" height="40px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 12H18M6 12L11 7M6 12L11 17" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg></a>    
            
            <img src=<?php echo $row["Imagem"]; ?> alt="Imagem" width="300" height="480">
            <div class="conteudo"> 
                <h1 style='font-size: 2em;'><?php echo $row["Titulo"]; ?></h1>
                <p style="margin-top: 20px;"><?php echo $row["Conteudo"]; ?></p>
                <ul style="margin: 20px 0 20px 0;">
                    <li style='text-align:center;'>Data<br><b><?php echo converterData($row['Data']); ?></b></li>
                    <li style='text-align:center;'>Horário<br><b><?php echo removerSegundos($row['Horario']); ?></b></li>
                    <li style='text-align:center;'>Local<br><b><?php echo $row['Local']; ?></b></li>
                    <li style='text-align:center;'>Valor unitário:<br><b style='color: #19B519;'>R$<?php echo $row['Valor']; ?></b></li>
                </ul>
                <?php if($row["qtdIngressos"] > 0) { ?>
                <h2 style='text-align: center;'>INGRESSOS DISPONÍVEIS: <?php echo $row["qtdIngressos"]; ?></h2>
                <div class="botoes">
                    <div class="entrada">   
                        <span>Entrada comum</span>
                        <div class="botao">
                            <button id="inteira_mais">+</button>
                            <input type="text" name="inteira" id="inteira" value="0">
                            <button id="inteira_menos">-</button>
                        </div>
                    </div>
                    <div class="entrada">
                        <span title="A meia entrada é reservada para gestantes, deficientes, idosos e estudantes. Deverá comprovar no local.">Meia entrada <svg style="margin: 0;" width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g  id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M23 12C23 18.0751 18.0751 23 12 23C5.92487 23 1 18.0751 1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12ZM3.00683 12C3.00683 16.9668 7.03321 20.9932 12 20.9932C16.9668 20.9932 20.9932 16.9668 20.9932 12C20.9932 7.03321 16.9668 3.00683 12 3.00683C7.03321 3.00683 3.00683 7.03321 3.00683 12Z" fill="#000000"></path> <path d="M13.5 18C13.5 18.8284 12.8284 19.5 12 19.5C11.1716 19.5 10.5 18.8284 10.5 18C10.5 17.1716 11.1716 16.5 12 16.5C12.8284 16.5 13.5 17.1716 13.5 18Z" fill="#000000"></path> <path d="M11 12V14C11 14 11 15 12 15C13 15 13 14 13 14V12C13 12 13.4792 11.8629 13.6629 11.7883C13.6629 11.7883 13.9969 11.6691 14.2307 11.4896C14.4646 11.3102 14.6761 11.097 14.8654 10.8503C15.0658 10.6035 15.2217 10.3175 15.333 9.99221C15.4443 9.66693 15.5 9.4038 15.5 9C15.5 8.32701 15.3497 7.63675 15.0491 7.132C14.7596 6.61604 14.3476 6.21786 13.8132 5.93745C13.2788 5.64582 12.6553 5.5 11.9427 5.5C11.4974 5.5 11.1021 5.55608 10.757 5.66825C10.4118 5.7692 10.1057 5.9094 9.83844 6.08887C9.58236 6.25712 9.36525 6.4478 9.18711 6.66091C9.02011 6.86281 8.8865 7.0591 8.78629 7.24978C8.68609 7.44046 8.61929 7.6087 8.58589 7.75452C8.51908 7.96763 8.49125 8.14149 8.50238 8.27609C8.52465 8.41069 8.59145 8.52285 8.70279 8.61258C8.81413 8.70231 8.9867 8.79765 9.22051 8.8986C9.46546 8.97712 9.65473 9.00516 9.78834 8.98273C9.93308 8.96029 10.05 8.89299 10.1391 8.78083C10.1391 8.78083 10.6138 8.10569 10.7474 7.97109C10.8922 7.82528 11.0703 7.71312 11.2819 7.6346C11.4934 7.54487 11.7328 7.5 12 7.5C12.579 7.5 13.0076 7.64021 13.286 7.92062C13.5754 8.18982 13.6629 8.41629 13.6629 8.93225C13.6629 9.27996 13.6017 9.56038 13.4792 9.77349C13.3567 9.9866 13.1953 10.1605 12.9949 10.2951C12.9949 10.2951 12.7227 10.3991 12.5 10.5C12.2885 10.5897 11.9001 10.7381 11.6997 10.8503C11.5104 10.9512 11.4043 11.0573 11.2819 11.2144C11.1594 11.3714 11 11.7308 11 12Z" fill="#000000"></path> </g></svg></span>
                        <div class="botao">
                            <button id="meia_mais">+</button>
                            <input type="text" name="meia" id="meia" value="0">
                            <button id="meia_menos">-</button>
                        </div>
                    </div>
                </div> 
              
                <div class="centro">
                    <div class="entrada">
                        <h3 style='font-size: 1.5rem;'>TOTAL</h3>
                        <span id="total" style='font-size: 1.2rem;'>R$ 0,00</span>
                    </div>
                    <form style="margin-top: 10px;" action="conexao.php" method="POST" name="compra">
                        <input type="hidden" name="inteiros" id="inteiros" value="0">
                        <input type="hidden" name="meios" id="meios" value="0">
                        <input type="hidden" name="mais" id="mais" value="0">
                        <input type="hidden" name="evento" value=<?php echo $postid; ?>>
                        <input type="hidden" name="acao" value="COMPRAR">
                        <input type="submit" value="COMPRAR" name="comprar" class="comprar">
                    </form>
                </div>
                <?php 
                    if(isset($_GET['err'])) { 
                        if($_GET['err'] == 1) { ?>
                         <div class="alerta" style="margin-left: 30px; padding: 15px; background-color: #FFD6D6; border-color: #FF4D4D;">
                            <svg width="20px" height="20px" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.8809 16.15C10.8809 16.0021 10.9101 15.8556 10.967 15.7191C11.024 15.5825 11.1073 15.4586 11.2124 15.3545C11.3175 15.2504 11.4422 15.1681 11.5792 15.1124C11.7163 15.0567 11.8629 15.0287 12.0109 15.03C12.2291 15.034 12.4413 15.1021 12.621 15.226C12.8006 15.3499 12.9399 15.5241 13.0211 15.7266C13.1024 15.9292 13.122 16.1512 13.0778 16.3649C13.0335 16.5786 12.9272 16.7745 12.7722 16.9282C12.6172 17.0818 12.4204 17.1863 12.2063 17.2287C11.9922 17.2711 11.7703 17.2494 11.5685 17.1663C11.3666 17.0833 11.1938 16.9426 11.0715 16.7618C10.9492 16.5811 10.8829 16.3683 10.8809 16.15ZM11.2408 13.42L11.1008 8.20001C11.0875 8.07453 11.1008 7.94766 11.1398 7.82764C11.1787 7.70761 11.2424 7.5971 11.3268 7.5033C11.4112 7.40949 11.5144 7.33449 11.6296 7.28314C11.7449 7.2318 11.8697 7.20526 11.9958 7.20526C12.122 7.20526 12.2468 7.2318 12.3621 7.28314C12.4773 7.33449 12.5805 7.40949 12.6649 7.5033C12.7493 7.5971 12.813 7.70761 12.8519 7.82764C12.8909 7.94766 12.9042 8.07453 12.8909 8.20001L12.7609 13.42C12.7609 13.6215 12.6809 13.8149 12.5383 13.9574C12.3958 14.0999 12.2024 14.18 12.0009 14.18C11.7993 14.18 11.606 14.0999 11.4635 13.9574C11.321 13.8149 11.2408 13.6215 11.2408 13.42Z" fill="#000000"/>
                                <path d="M12 21.5C17.1086 21.5 21.25 17.3586 21.25 12.25C21.25 7.14137 17.1086 3 12 3C6.89137 3 2.75 7.14137 2.75 12.25C2.75 17.3586 6.89137 21.5 12 21.5Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <p>Selecione pelo menos 1 ingresso</p>
                        </div>
                        <?php } 
                        if($_GET['err'] == 2) { ?>
                         <div class="alerta" style="margin-left: 30px; padding: 15px; background-color: #FFD6D6; border-color: #FF4D4D;">
                            <svg width="20px" height="20px" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.8809 16.15C10.8809 16.0021 10.9101 15.8556 10.967 15.7191C11.024 15.5825 11.1073 15.4586 11.2124 15.3545C11.3175 15.2504 11.4422 15.1681 11.5792 15.1124C11.7163 15.0567 11.8629 15.0287 12.0109 15.03C12.2291 15.034 12.4413 15.1021 12.621 15.226C12.8006 15.3499 12.9399 15.5241 13.0211 15.7266C13.1024 15.9292 13.122 16.1512 13.0778 16.3649C13.0335 16.5786 12.9272 16.7745 12.7722 16.9282C12.6172 17.0818 12.4204 17.1863 12.2063 17.2287C11.9922 17.2711 11.7703 17.2494 11.5685 17.1663C11.3666 17.0833 11.1938 16.9426 11.0715 16.7618C10.9492 16.5811 10.8829 16.3683 10.8809 16.15ZM11.2408 13.42L11.1008 8.20001C11.0875 8.07453 11.1008 7.94766 11.1398 7.82764C11.1787 7.70761 11.2424 7.5971 11.3268 7.5033C11.4112 7.40949 11.5144 7.33449 11.6296 7.28314C11.7449 7.2318 11.8697 7.20526 11.9958 7.20526C12.122 7.20526 12.2468 7.2318 12.3621 7.28314C12.4773 7.33449 12.5805 7.40949 12.6649 7.5033C12.7493 7.5971 12.813 7.70761 12.8519 7.82764C12.8909 7.94766 12.9042 8.07453 12.8909 8.20001L12.7609 13.42C12.7609 13.6215 12.6809 13.8149 12.5383 13.9574C12.3958 14.0999 12.2024 14.18 12.0009 14.18C11.7993 14.18 11.606 14.0999 11.4635 13.9574C11.321 13.8149 11.2408 13.6215 11.2408 13.42Z" fill="#000000"/>
                                <path d="M12 21.5C17.1086 21.5 21.25 17.3586 21.25 12.25C21.25 7.14137 17.1086 3 12 3C6.89137 3 2.75 7.14137 2.75 12.25C2.75 17.3586 6.89137 21.5 12 21.5Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <p>Não há essa quantia de ingressos disponíveis! Por favor, selecione um número menor.</p>
                        </div>
                        <?php } 
                    } } else { ?>

                <div class='alerta' style='background-color: #FFD6D6; border-color: #FF4D4D; padding: 10px 10px;'>
                    <svg width='20px' height='20px' viewBox='-0.5 0 25 25' fill='none' xmlns='http://www.w3.org/2000/svg'>
                        <path d='M10.8809 16.15C10.8809 16.0021 10.9101 15.8556 10.967 15.7191C11.024 15.5825 11.1073 15.4586 11.2124 15.3545C11.3175 15.2504 11.4422 15.1681 11.5792 15.1124C11.7163 15.0567 11.8629 15.0287 12.0109 15.03C12.2291 15.034 12.4413 15.1021 12.621 15.226C12.8006 15.3499 12.9399 15.5241 13.0211 15.7266C13.1024 15.9292 13.122 16.1512 13.0778 16.3649C13.0335 16.5786 12.9272 16.7745 12.7722 16.9282C12.6172 17.0818 12.4204 17.1863 12.2063 17.2287C11.9922 17.2711 11.7703 17.2494 11.5685 17.1663C11.3666 17.0833 11.1938 16.9426 11.0715 16.7618C10.9492 16.5811 10.8829 16.3683 10.8809 16.15ZM11.2408 13.42L11.1008 8.20001C11.0875 8.07453 11.1008 7.94766 11.1398 7.82764C11.1787 7.70761 11.2424 7.5971 11.3268 7.5033C11.4112 7.40949 11.5144 7.33449 11.6296 7.28314C11.7449 7.2318 11.8697 7.20526 11.9958 7.20526C12.122 7.20526 12.2468 7.2318 12.3621 7.28314C12.4773 7.33449 12.5805 7.40949 12.6649 7.5033C12.7493 7.5971 12.813 7.70761 12.8519 7.82764C12.8909 7.94766 12.9042 8.07453 12.8909 8.20001L12.7609 13.42C12.7609 13.6215 12.6809 13.8149 12.5383 13.9574C12.3958 14.0999 12.2024 14.18 12.0009 14.18C11.7993 14.18 11.606 14.0999 11.4635 13.9574C11.321 13.8149 11.2408 13.6215 11.2408 13.42Z' fill='#000000'/>
                        <path d='M12 21.5C17.1086 21.5 21.25 17.3586 21.25 12.25C21.25 7.14137 17.1086 3 12 3C6.89137 3 2.75 7.14137 2.75 12.25C2.75 17.3586 6.89137 21.5 12 21.5Z' stroke='#000000' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/>
                    </svg>
                    <p>Não há mais ingressos disponíveis! :(</p>
                </div>
                <?php } ?>
            </div>
        </div>

        <?php } 
            if($total == 0) { echo "<font size='10px' color='red'>Não foi possivel localizar o post solicitado :(</font><br><br>"; 
            echo "<font size='6px'><a href='index.php'>Voltar ao Inicio</a>";
            }
        ?>
    </div>
</body>
</html>
