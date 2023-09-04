<?php
$imagens = [];

$dir = "uploads/";
$files = scandir($dir);

foreach ($files as $file) {
    if ($file !== "." && $file !== "..") {
        $imagens[] = ["filename" => $file, "descricao" => ""];
    }
}

echo json_encode($imagens);
?>
