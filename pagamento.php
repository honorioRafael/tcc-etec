<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="reserva.css">
    <style>
        .btn21 {
            margin-left: 30px;
            background-color: #9370DB;
            color: white;
            border-radius: 10px;
            padding: 10px 20px;
            width: 110px;
        }
        .btn21:hover {
            background-color: #8155da;
        }
    </style>
</head>
<?php
require_once("conexao.php");
session_start();
if (isset($_FILES['imagem'])) {
    $upload_dir = 'uploads/';
    $file_name = $_FILES['imagem']['name'];
    $file_tmp = $_FILES['imagem']['tmp_name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $reservaid = $_POST["reserva"];

    if (getimagesize($file_tmp) !== false) {
        $new_file_name = 'Comprovante_'.$reservaid.'.'.$file_ext;
        $destination = $upload_dir . $new_file_name;
        
        $sql2 = "UPDATE `tcc`.`reservas` SET `Aprovado`='AGUARDANDO APROVAÇÃO' WHERE `codReserva`='$reservaid';";
        $conn->query($sql2);
        
        $sql2 = "UPDATE `tcc`.`reservas` SET `Formato`='$file_ext' WHERE `codReserva`='$reservaid';";
        $conn->query($sql2);

        if (move_uploaded_file($file_tmp, $destination)) {
            CriarHeader(4, $_SESSION['__DVC_DadosLogin']);
            ?> 
                <div class="eventos_show"> 
                    <div class="evento_show" style="flex-direction: column;">
                        <h1 style="margin-top: 30px;">Comprovante enviado!</h1>
                        <p style="margin-top: 20px;">Agora, aguarde até 24h para seu pagamento ser verificado por um moderador.</p>
                        <p>Agradecemos sua preferência :) 👍</p><br>
                        <a class="btn21" href="index.php">Ir para o início</a>
                    </div>
                </div>
            <?php
        } else {
            header("Location:pagarEvento.php?reserva=$reservaid&msg=2");
        }
    } else {
        echo 'O arquivo selecionado não é uma imagem válida.';
        header("Location:pagarEvento.php?reserva=$reservaid&msg=1");
    }
} else {
    echo 'Nenhum arquivo foi selecionado.';
    header("Location:pagarEvento.php?reserva=$reservaid&msg=1");
}
?>
