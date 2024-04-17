<link rel="stylesheet" href="header.css">

<?php
function CriarHeader($pagina, $DadosLogin) {
    $AdminLevel = 0;

    if($DadosLogin != null) {
        $AdminLevel = intval($DadosLogin["Admin"]);
    } ?>
    <header>
    <script>      
        document.addEventListener('DOMContentLoaded', function () {
            var menuBtn = document.getElementById('menu-btn');
            var hOpcs2Div = document.querySelector('.h-opcs2');

            menuBtn.addEventListener('click', function () {
                if(hOpcs2Div.style.display === 'block') {
                    hOpcs2Div.style.display = 'none';
                } 
                else {
                    hOpcs2Div.style.display = 'block';
                }
            });
        });
    </script>
    <a href="index.php" class="none"><img class="logo-maior" src="imagens/logo2.png" alt="Logo" width="120px"><img class="logo-menor" src="imagens/logo_menor.png" alt="Logo" width="60px"></a>
    <?php if($pagina <= 2 || $pagina == 4) { ?>
    <form class="pesquisa" action="index.php" method="GET">
        <input type="search" name="search" placeholder="Pesquisar">
        <button type="submit"><svg width="30px" height="30px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M14.9536 14.9458L21 21M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="#000" stroke-width="1.512" stroke-linecap="round" stroke-linejoin="round"></path></g></svg></button>
    </form>
    <?php } ?>
    <ul class="h-opcs">
        <li class="header-li"> <?php if(isset($DadosLogin)) { ?>
            <a href="conta.php" <?php if($pagina == 4) echo"class='sel'";?>>Minha conta</a>
            <?php } ?>
        </li>
        <li class="header-li"><?php if($AdminLevel == 1) { ?><a href='admin.php' <?php if($pagina == 3) echo"class='sel'"; ?>>Admin</a></li>
        <li class="header-li"> <?php } if(isset($DadosLogin)) { ?>
            <a href="deslogar.php">Logado: <b><?php echo $DadosLogin['Nome']; ?></b></a>
        <?php } else { ?><a href='login.php' <?php if($pagina == 5) echo"class='sel'"; ?>>LOGIN</a> <?php } ?>
        </li>
        
    </ul>     
    <button id="menu-btn"><svg width="25px" height="25px" viewBox="0 -1 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"><title>hamburger</title><desc>Created with Sketch Beta.</desc><defs></defs><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"><g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-210.000000, -887.000000)" fill="#ffffff"><path d="M229,895 L211,895 C210.448,895 210,895.448 210,896 C210,896.553 210.448,897 211,897 L229,897 C229.552,897 230,896.553 230,896 C230,895.448 229.552,895 229,895 L229,895 Z M229,903 L211,903 C210.448,903 210,903.448 210,904 C210,904.553 210.448,905 211,905 L229,905 C229.552,905 230,904.553 230,904 C230,903.448 229.552,903 229,903 L229,903 Z M211,889 L229,889 C229.552,889 230,888.553 230,888 C230,887.448 229.552,887 229,887 L211,887 C210.448,887 210,887.448 210,888 C210,888.553 210.448,889 211,889 L211,889 Z" id="hamburger" sketch:type="MSShapeGroup"></path></g></g>
</svg></button> 
</header>
<nav>
    <ul class="h-opcs2">
        <li class="header-li2"> <?php if(isset($DadosLogin)) { ?>
            <a href="conta.php">Minha conta</a>
            <?php } ?>
        </li>
        <li class="header-li2"><?php if($AdminLevel == 1) { ?><a href='admin.php'>Admin</a></li>
        <li class="header-li2"> <?php } if(isset($DadosLogin)) { ?>
            <a href="deslogar.php">Logado: <b><?php echo $DadosLogin['Nome']; ?></b></a>
        <?php } else { ?><a href='login.php'>LOGIN</a> <?php } ?>
        </li>
        
    </ul> 
</nav>
<?php } ?>