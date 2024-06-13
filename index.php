<?php

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
$category = str_replace("/", "\\", $category);
$image = $_POST['image-text'] ?? "";
$image = str_replace("/", "\\", $image);

$file = "public\\images";
if ($category) {
    $file .= "\\$category";
}
if ($image) {
    $file .= "\\$image";
}
$folder = false;
try{
    $folder = new DirectoryIterator("public\\images\\" . $category ?? "");
} catch(UnexpectedValueException $e){
    // ignored
}

$imageBase64 = false;
if (file_exists($file) && is_file($file)) {
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
                echo "<img src='public/images/explosion.gif' class='explosion'>";
            }
        ?>
    </div>
</body>

</html>