<?php

$rootDir = dirname(__DIR__);
$publicDir = __DIR__;

$inputFolder = $_POST['folder'] ?? "";
$inputImage = $_POST['image'] ?? "";

$realFolder = realpath("$inputFolder");
$realImage = realpath("$realFolder/$inputImage");
if (empty($realFolder) || !str_starts_with($realFolder, $rootDir)) {
    $realFolder = false;
}
if (empty($realImage) || !str_starts_with($realImage, $rootDir) || !is_file($realImage)) {
    $realImage = false;
}
$listerAll = new RecursiveDirectoryIterator("images");
$listerAll->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
$listerPath = $realFolder ? new DirectoryIterator($realFolder) : false;

$imageBase64 = false;
if ($realImage) {
    $imageData = file_get_contents($realImage);
    $imageBase64 = base64_encode($imageData);
}

function echoOptionDirectories(DirectoryIterator $it)
{
    global $inputFolder;
    foreach ($it as $key => $item) {
        if (!$item->isDir())
            continue;

        echo "<option ";
        if ($inputFolder == $key)
            echo "selected";
        echo ">$key</option>";

        $newIt = new RecursiveDirectoryIterator($key);
        $newIt->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
        echoOptionDirectories($newIt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="main.css">
    <script src="main.js" defer></script>

    <title>De Plaat</title>
</head>

<body>
    <form action="." method="POST">
        <h1>De Plaat</h1>
        <label for="folder">Folder</label>
        <input type="text" id="folder" name="folder" <?php echo "value='$inputFolder'" ?>>
        <select id="folder-select">
            <option value="">Selecteer een folder</option>
            <?php
            echoOptionDirectories($listerAll);
            ?>
        </select>

        <label for="image">Afbeelding</label>
        <input type="text" id="image" name="image" <?php echo "value='$inputImage'" ?>>
        <select id="image-select">
            <option value="">Selecteer een afbeelding</option>
            <?php
            if ($listerPath) {
                foreach ($listerPath as $value) {
                    if ($value->isDir())
                        continue;
                    $name = $value->getFilename();
                    // if (!str_ends_with($name, ".png")) continue;
                    echo "<option value='$name'>$name</option>";
                }
            }
            ?>
        </select>

        <input type="submit" value="Go">
    </form>

    <div class="container">
        <?php
        if ($imageBase64) {
            echo "<img class='display' src='data:image/png;base64,$imageBase64'>";
            echo "<img src='images/explosion.gif' class='explosion'>";
        }
        ?>
    </div>
</body>

</html>