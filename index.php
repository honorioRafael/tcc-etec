<?php
    require_once("conexao.php");
    $esta_pesquisa = false;
    if(isset($_GET["search"])) {
        $pesquisa = $_GET["search"];
        $sql = "SELECT * FROM `posts` WHERE `Titulo` LIKE '%$pesquisa%'"; 
        $esta_pesquisa = true;
    }  
    else {
        $sql = "SELECT * FROM `posts` ORDER BY `codPostagem` DESC;";
    }
    $todos_produtos = $conn->query($sql);

    $total = 0;
    $linha = 0;
    $favoritos = 0;
    $livres = 0;
    $m_18 = 0;
    $m_12 = 0;
    $m_14 = 0;
    $m_16 = 0;
    $acontecer = 0;
    $data = date('Y-m-d');
    $hora = date('H:i:s');

    $result_s = $conn->query("SELECT COUNT(*) as count  FROM `posts` WHERE `Classificacao` = 'L';");
    $livres = mysqli_fetch_object($result_s)->count;

    $result_s = $conn->query("SELECT COUNT(*) as count FROM `posts` WHERE `Classificacao` = '+18';");
    $m_18 = mysqli_fetch_object($result_s)->count;

    $result_s = $conn->query("SELECT COUNT(*) as count  FROM `posts` WHERE `Classificacao` = '+12';");
    $m_12 = mysqli_fetch_object($result_s)->count;

    $result_s = $conn->query("SELECT COUNT(*) as count  FROM `posts` WHERE `Classificacao` = '+14';");
    $m_14 = mysqli_fetch_object($result_s)->count;

    $result_s = $conn->query("SELECT COUNT(*) as count  FROM `posts` WHERE `Classificacao` = '+16';");
    $m_16 = mysqli_fetch_object($result_s)->count;

    $result_s = $conn->query("SELECT COUNT(*) as count FROM `posts` WHERE (`Data` = '$data' AND `Horario` <= '$hora') OR (`Data` > '$data')");
    $acontecer = mysqli_fetch_object($result_s)->count;

    $DadosLogin = null;
    session_start();
    if (isset($_SESSION['__DVC_DadosLogin'])) {
        $DadosLogin = $_SESSION['__DVC_DadosLogin'];
        $AdminLevel = $DadosLogin['Admin'];
        $userId = $DadosLogin["codUsuario"];

        $resultado = $conn->query("SELECT codPostagem FROM favoritos WHERE codUsuario = $userId ORDER BY `codFavorito` DESC;");
        $favs = array();
        while($row = mysqli_fetch_assoc($resultado)) {
            $favoritos ++;
            $postid = $row["codPostagem"];
            $sql2 = "SELECT `Titulo`,`Imagem`,`codPostagem` FROM `tcc`.`posts` WHERE `codPostagem`='$postid'";
            $resultad = $conn->query($sql2);
            array_push($favs, mysqli_fetch_assoc($resultad));        
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
    <title>Divulgarça</title>
</head>
<body>
    <?php CriarHeader(1, $DadosLogin); ?>
    <h1 class="eventos-h1"><?php if($esta_pesquisa == true) { echo"Resultados para \"".$pesquisa."\":"; } ?></h1>
    <?php if($esta_pesquisa == false) { echo "<p class='lista-evento-txt'>Todos os eventos</p>"; } ?>
    <div class="linha-evento">   
        <button onclick="autoScroll(1, 2)" class="seta-1"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 9L8 12M8 12L11 15M8 12H16M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg></button>
        <div class="evento_linha" id="evento_linha-1">
            <?php while($row = mysqli_fetch_assoc($todos_produtos)) {
                $total ++; ?>    
                <a href="evento.php?evento=<?php echo $row['codPostagem']; ?>">
                <div class="evento">
                    <img title="<?php echo $row["Titulo"]; ?>" src=<?php echo $row["Imagem"]; ?> alt="Imagem" width="280" height="480">
                    <h1 style='font-size: 1.2rem;' title="<?php echo $row["Titulo"]; ?>"><?php echo $row["Titulo"]; ?></h1>
                </div></a>
            <?php if($total == 0) { echo "<font size='6px'><p class='eventoerro'>Não há eventos disponíveis no momento.</p></font>"; }
            } ?>
        </div>
        <button onclick="autoScroll(1, 1)" class="seta-2"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M13 15L16 12M16 12L13 9M16 12H8M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg></button>
    </div>

    <?php  
    if($esta_pesquisa == false && $acontecer > 0) { echo "<p class='lista-evento-txt'>Ainda não aconteceram</p>";
    $resultado = $conn->query("SELECT * FROM `posts` WHERE (`Data` = '$data' AND `Horario` <= '$hora') OR (`Data` > '$data') ORDER BY `Data`;"); ?>
    <div class="linha-evento">   
        <button onclick="autoScroll(8, 2)" class="seta-1"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 9L8 12M8 12L11 15M8 12H16M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg></button>
        <div class="evento_linha" id="evento_linha-8">
            <?php $pos = 0; while($row = mysqli_fetch_assoc($resultado)) {
                $total ++; ?>                   
                <a href="evento.php?evento=<?php echo $row['codPostagem']; ?>">
                <div class="evento">
                    <img title="<?php echo $row["Titulo"]; ?>" src=<?php echo $row["Imagem"]; ?> alt="Imagem" width="280" height="480">
                    <h1 style='font-size: 1.2rem;' title="<?php echo $row["Titulo"]; ?>"><?php echo $row["Titulo"]; ?></h1>
                </div></a>
            <?php $pos++; if($total == 0) { echo "<font size='6px'><p class='eventoerro'>Não há eventos disponíveis no momento.</p></font>"; }
            } ?>
        </div>
        <button onclick="autoScroll(8, 1)" class="seta-2"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M13 15L16 12M16 12L13 9M16 12H8M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg></button>
    </div>
    <?php } ?>

    <?php  
    if($esta_pesquisa == false && $favoritos > 0) { echo "<p class='lista-evento-txt'>Seus favoritos</p>";
    $resultado = $conn->query("SELECT * FROM `tcc`.`favoritos` WHERE `codUsuario` = '$userId' ORDER BY `codFavorito` DESC;"); ?>
    <div class="linha-evento">   
        <button onclick="autoScroll(2, 2)" class="seta-1"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 9L8 12M8 12L11 15M8 12H16M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg></button>
        <div class="evento_linha" id="evento_linha-2">
            <?php $pos = 0; while($row = mysqli_fetch_assoc($resultado)) {
                $total ++; ?>                   
                <a href="evento.php?evento=<?php echo $favs[$pos]['codPostagem']; ?>">
                <div class="evento">
                    <img title="<?php echo $favs[$pos]["Titulo"]; ?>" src=<?php echo $favs[$pos]["Imagem"]; ?> alt="Imagem" width="280" height="480">
                    <h1 style='font-size: 1.2rem;' title="<?php echo $favs[$pos]["Titulo"]; ?>"><?php echo $favs[$pos]["Titulo"]; ?></h1>
                </div></a>
            <?php $pos++; if($total == 0) { echo "<font size='6px'><p class='eventoerro'>Não há eventos disponíveis no momento.</p></font>"; }
            } ?>
        </div>
        <button onclick="autoScroll(2, 1)" class="seta-2"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M13 15L16 12M16 12L13 9M16 12H8M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg></button>
    </div>
    <?php } ?>

    <?php  
    if($esta_pesquisa == false && $livres > 0) { echo "<p class='lista-evento-txt'>Livre para todos os públicos</p>";
    $resultado = $conn->query("SELECT * FROM `tcc`.`posts` WHERE `classificacao` = 'L' ORDER BY `codPostagem` DESC;"); ?>
    <div class="linha-evento">   
        <button onclick="autoScroll(3, 2)" class="seta-1"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 9L8 12M8 12L11 15M8 12H16M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg></button>
        <div class="evento_linha" id="evento_linha-3">
            <?php $pos = 0; while($row = mysqli_fetch_assoc($resultado)) {
                $total ++; ?>                   
                <a href="evento.php?evento=<?php echo $row['codPostagem']; ?>">
                <div class="evento">
                    <img title="<?php echo $row["Titulo"]; ?>" src=<?php echo $row["Imagem"]; ?> alt="Imagem" width="280" height="480">
                    <h1 style='font-size: 1.2rem;' title="<?php echo $row["Titulo"]; ?>"><?php echo $row["Titulo"]; ?></h1>
                </div></a>
            <?php $pos++; if($total == 0) { echo "<font size='6px'><p class='eventoerro'>Não há eventos disponíveis no momento.</p></font>"; }
            } ?>
        </div>
        <button onclick="autoScroll(3, 1)" class="seta-2"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M13 15L16 12M16 12L13 9M16 12H8M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg></button>
    </div>
    <?php } ?>

    <?php  
    if($esta_pesquisa == false && $m_18 > 0) { echo "<p class='lista-evento-txt'>Eventos +18</p>";
    $resultado = $conn->query("SELECT * FROM `tcc`.`posts` WHERE `classificacao` = '+18' ORDER BY `codPostagem` DESC;"); ?>
    <div class="linha-evento">   
        <button onclick="autoScroll(4, 2)" class="seta-1"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 9L8 12M8 12L11 15M8 12H16M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg></button>
        <div class="evento_linha" id="evento_linha-4">
            <?php $pos = 0; while($row = mysqli_fetch_assoc($resultado)) {
                $total ++; ?>                   
                <a href="evento.php?evento=<?php echo $row['codPostagem']; ?>">
                <div class="evento">
                    <img title="<?php echo $row["Titulo"]; ?>" src=<?php echo $row["Imagem"]; ?> alt="Imagem" width="280" height="480">
                    <h1 style='font-size: 1.2rem;' title="<?php echo $row["Titulo"]; ?>"><?php echo $row["Titulo"]; ?></h1>
                </div></a>
            <?php $pos++; if($total == 0) { echo "<font size='6px'><p class='eventoerro'>Não há eventos disponíveis no momento.</p></font>"; }
            } ?>
        </div>
        <button onclick="autoScroll(4, 1)" class="seta-2"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M13 15L16 12M16 12L13 9M16 12H8M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg></button>
    </div>
    <?php } ?>

    <?php  
    if($esta_pesquisa == false && $m_12 > 0) { echo "<p class='lista-evento-txt'>Eventos +12</p>";
    $resultado = $conn->query("SELECT * FROM `tcc`.`posts` WHERE `classificacao` = '+12' ORDER BY `codPostagem` DESC;"); ?>
    <div class="linha-evento">   
        <button onclick="autoScroll(5, 2)" class="seta-1"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 9L8 12M8 12L11 15M8 12H16M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg></button>
        <div class="evento_linha" id="evento_linha-5">
            <?php $pos = 0; while($row = mysqli_fetch_assoc($resultado)) {
                $total ++; ?>                   
                <a href="evento.php?evento=<?php echo $row['codPostagem']; ?>">
                <div class="evento">
                    <img title="<?php echo $row["Titulo"]; ?>" src=<?php echo $row["Imagem"]; ?> alt="Imagem" width="280" height="480">
                    <h1 style='font-size: 1.2rem;' title="<?php echo $row["Titulo"]; ?>"><?php echo $row["Titulo"]; ?></h1>
                </div></a>
            <?php $pos++; if($total == 0) { echo "<font size='6px'><p class='eventoerro'>Não há eventos disponíveis no momento.</p></font>"; }
            } ?>
        </div>
        <button onclick="autoScroll(5, 1)" class="seta-2"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M13 15L16 12M16 12L13 9M16 12H8M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg></button>
    </div>
    <?php } ?>

    <?php  
    if($esta_pesquisa == false && $m_14 > 0) { echo "<p class='lista-evento-txt'>Eventos +14</p>";
    $resultado = $conn->query("SELECT * FROM `tcc`.`posts` WHERE `classificacao` = '+14' ORDER BY `codPostagem` DESC;"); ?>
    <div class="linha-evento">   
        <button onclick="autoScroll(6, 2)" class="seta-1"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 9L8 12M8 12L11 15M8 12H16M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg></button>
        <div class="evento_linha" id="evento_linha-6">
            <?php $pos = 0; while($row = mysqli_fetch_assoc($resultado)) {
                $total ++; ?>                   
                <a href="evento.php?evento=<?php echo $row['codPostagem']; ?>">
                <div class="evento">
                    <img title="<?php echo $row["Titulo"]; ?>" src=<?php echo $row["Imagem"]; ?> alt="Imagem" width="280" height="480">
                    <h1 style='font-size: 1.2rem;' title="<?php echo $row["Titulo"]; ?>"><?php echo $row["Titulo"]; ?></h1>
                </div></a>
            <?php $pos++; if($total == 0) { echo "<font size='6px'><p class='eventoerro'>Não há eventos disponíveis no momento.</p></font>"; }
            } ?>
        </div>
        <button onclick="autoScroll(6, 1)" class="seta-2"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M13 15L16 12M16 12L13 9M16 12H8M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg></button>
    </div>
    <?php } ?>

    <?php  
    if($esta_pesquisa == false && $m_16 > 0) { echo "<p class='lista-evento-txt'>Eventos +16</p>";
    $resultado = $conn->query("SELECT * FROM `tcc`.`posts` WHERE `classificacao` = '+16' ORDER BY `codPostagem` DESC;"); ?>
    <div class="linha-evento">   
        <button onclick="autoScroll(7, 2)" class="seta-1"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 9L8 12M8 12L11 15M8 12H16M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg></button>
        <div class="evento_linha" id="evento_linha-7">
            <?php $pos = 0; while($row = mysqli_fetch_assoc($resultado)) {
                $total ++; ?>                   
                <a href="evento.php?evento=<?php echo $row['codPostagem']; ?>">
                <div class="evento">
                    <img title="<?php echo $row["Titulo"]; ?>" src=<?php echo $row["Imagem"]; ?> alt="Imagem" width="280" height="480">
                    <h1 style='font-size: 1.2rem;' title="<?php echo $row["Titulo"]; ?>"><?php echo $row["Titulo"]; ?></h1>
                </div></a>
            <?php $pos++; if($total == 0) { echo "<font size='6px'><p class='eventoerro'>Não há eventos disponíveis no momento.</p></font>"; }
            } ?>
        </div>
        <button onclick="autoScroll(7, 1)" class="seta-2"><svg width="80px" height="80px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M13 15L16 12M16 12L13 9M16 12H8M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg></button>
        <?php } ?>
    </div>
    <br>
    <?php if(!isset($_GET["search"])) { ?>
    <footer>
        <p style="margin: 0; align-self: center; text-align: center;">Desenvolvido por: Maria Fernanda, Rafael & Sofia | ETEC MAM</p>
        <p style="margin: 0; align-self: center; align-items:center; text-align: center;"><span>Divulgarça - 2023</span><span style="margin-left: 20px; align-items:center; text-align: center;"><a style="align-items: center; text-align: center;" href="https://www.instagram.com/divulgarcasp/" target="_blank"><svg width="22px" height="22px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M2 6C2 3.79086 3.79086 2 6 2H18C20.2091 2 22 3.79086 22 6V18C22 20.2091 20.2091 22 18 22H6C3.79086 22 2 20.2091 2 18V6ZM6 4C4.89543 4 4 4.89543 4 6V18C4 19.1046 4.89543 20 6 20H18C19.1046 20 20 19.1046 20 18V6C20 4.89543 19.1046 4 18 4H6ZM12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9ZM7 12C7 9.23858 9.23858 7 12 7C14.7614 7 17 9.23858 17 12C17 14.7614 14.7614 17 12 17C9.23858 17 7 14.7614 7 12ZM17.5 8C18.3284 8 19 7.32843 19 6.5C19 5.67157 18.3284 5 17.5 5C16.6716 5 16 5.67157 16 6.5C16 7.32843 16.6716 8 17.5 8Z" fill="#9370DB"/>
            </svg>
        </a>
        <a style="margin-left: 10px;" href="https://www.facebook.com/profile.php?id=100083023196775" target="_blank"><svg width="20px" height="20px" viewBox="-5 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <defs>
            </defs>
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Dribbble-Light-Preview" transform="translate(-385.000000, -7399.000000)" fill="#9370DB">
                        <g id="icons" transform="translate(56.000000, 160.000000)">
                            <path d="M335.821282,7259 L335.821282,7250 L338.553693,7250 L339,7246 L335.821282,7246 L335.821282,7244.052 C335.821282,7243.022 335.847593,7242 337.286884,7242 L338.744689,7242 L338.744689,7239.14 C338.744689,7239.097 337.492497,7239 336.225687,7239 C333.580004,7239 331.923407,7240.657 331.923407,7243.7 L331.923407,7246 L329,7246 L329,7250 L331.923407,7250 L331.923407,7259 L335.821282,7259 Z" id="facebook-[#176]">
                                </path>
                        </g>
                    </g>
                </g>
            </svg>
        </a></span></p>
    </footer><?php } ?>
<script>
    function autoScroll(linha, side) {
        var div = document.getElementById("evento_linha-"+linha);
        var totalWidth = div.scrollWidth - div.clientWidth;

        if(side == 1) var targetScrollLeft = div.scrollLeft + 0.25 * totalWidth;
        else var targetScrollLeft = div.scrollLeft - 0.25 * totalWidth;

        div.scrollLeft = targetScrollLeft;
    }

</script>
</body>
</html>
