const form = document.querySelector("form");
const folder = document.querySelector("#folder");
const image = document.querySelector("#image");
const folderSelect = document.querySelector("#folder-select");
const imageSelect = document.querySelector("#image-select");
const explosion = document.querySelector(".explosion");

folderSelect.onchange = function () {
    folder.value = folderSelect.value;
    image.value = "";
    imageSelect.value = "";
    form.submit();
}

imageSelect.onchange = function () {
    image.value = imageSelect.value;
    form.submit();
}

if (explosion !== null) {
    const src = explosion.src;
    explosion.src = "";
    explosion.src = src;
    setTimeout(function () {
        explosion.remove();
    }, 1000);
}