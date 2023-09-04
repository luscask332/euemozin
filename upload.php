<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = "uploads/";
    $uploadFile = $uploadDir . basename($_FILES["imagem"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

    // Verificar se o arquivo é uma imagem real
    $check = getimagesize($_FILES["imagem"]["tmp_name"]);
    if ($check === false) {
        echo json_encode(["success" => false, "message" => "O arquivo não é uma imagem."]);
        exit;
    }

    // Verificar se o arquivo já existe
    if (file_exists($uploadFile)) {
        echo json_encode(["success" => false, "message" => "Desculpe, o arquivo já existe."]);
        exit;
    }

    // Verificar o tamanho do arquivo (limite de 1 GB)
    if ($_FILES["imagem"]["size"] > 5000000) {
        echo json_encode(["success" => false, "message" => "Desculpe, o arquivo é muito grande."]);
        exit;
    }

    // Permitir apenas formatos de imagem específicos
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo json_encode(["success" => false, "message" => "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos."]);
        exit;
    }

    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $uploadFile)) {
        $descricao = $_POST["descricao"] ?? "";

        echo json_encode(["success" => true, "filename" => basename($_FILES["imagem"]["name"]), "descricao" => $descricao]);
    } else {
        echo json_encode(["success" => false, "message" => "Desculpe, ocorreu um erro ao enviar a imagem."]);
    }
}
?>
