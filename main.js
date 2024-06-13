const categorySelect = document.querySelector("#category-select");
const categoryText = document.querySelector("#category-text");
const imageSelect = document.querySelector("#image-select");
const imageText = document.querySelector("#image-text");
const explosion = document.querySelector(".explosion");
const form = document.querySelector("form");

if (!categoryText.value) categoryText.value = categorySelect.value;
categorySelect.onchange = function () {
    console.log("onchange");
    categoryText.value = categorySelect.value;
    imageText.value = "";
    imageSelect.value = "";
    form.submit();
}

if (!imageText.value) imageText.value = imageSelect.value;
imageSelect.onchange = function () {
    imageText.value = imageSelect.value;
    form.submit();
}

const src = explosion.src;
explosion.src = "";
explosion.src = src;
setTimeout(function (){
    explosion.remove();
}, 1000);