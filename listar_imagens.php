<?php
$dir = "uploads/";
$files = scandir($dir);

foreach ($files as $file) {
    if ($file !== "." && $file !== "..") {
        echo '<div class="photo-item">';
        echo '<img src="uploads/' . $file . '" alt="' . $file . '">';
        echo '</div>';
    }
}
?>
