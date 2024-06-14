<?php

$allowedDir = dirname(__DIR__);

$images = false;
$animalsSelect = "";
$peopleSelect = "";
if (isset($_POST['category-select'])) {
    $value = $_POST['category-select'];
    if ($value == "animals") {
        $animalsSelect = "selected";
    } else if ($value == "people"){
        $peopleSelect = "selected";
    }
}

$category = $_POST['category-text'] ?? "";
$image = $_POST['image-text'] ?? "";

$file = "images";
if ($category) {
    $file .= "/$category";
}
if ($image) {
    $file .= "/$image";
}
$folder = false;
$build = false;
try{
    if ($category){
        $build = realpath("images/$category");
        if (str_starts_with($build, $allowedDir)){
            $folder = new DirectoryIterator($build);
        }
    }
} catch(UnexpectedValueException $e){
    // ignored
}

$imageBase64 = false;
$fileBuild = realpath($file);
if (file_exists($fileBuild) && is_file($fileBuild) && str_starts_with($fileBuild, $allowedDir)) {
    $imageData = file_get_contents($file);
    $imageBase64 = base64_encode($imageData);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo viewer</title>
    <link rel="stylesheet" href="main.css">
    <script src="main.js" defer></script>
</head>
<body>
    <form action="." method="POST">
        <label for="category-text">Category</label>
        <input type="text" id="category-text" name="category-text" <?php echo "value='$category'" ?>>
        <select id="category-select" name="category-select">
            <option value="">All</option>
            <option value="animals" <?php echo $animalsSelect ?>>Animals</option>
            <option value="people" <?php echo $peopleSelect ?>>People</option>
        </select>

        <label for="image-text">Image</label>
        <input type="text" id="image-text" name="image-text" <?php echo "value='$image'" ?>>
        <select id="image-select" name="image-select">
            <option value="">Select an image</option>
            <?php
            if ($folder) {
                foreach ($folder as $imageFile) {
                    if ($imageFile->isDir()) continue;
                    $name = $imageFile->getBasename();
                    if ($name == ".htaccess") continue;
                    echo "<option value='$name' ";
                    if ($imageFile->getBasename() == $image) {
                        echo "selected";
                    }
                    echo ">$name</options>";
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