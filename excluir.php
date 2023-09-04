<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->filename)) {
        $filename = $data->filename;
        $path = "uploads/" . $filename;

        if (file_exists($path)) {
            if (unlink($path)) {
                echo json_encode(["success" => true]);
                exit;
            }
        }
    }
}

echo json_encode(["success" => false, "message" => "Falha ao excluir a imagem."]);
?>
