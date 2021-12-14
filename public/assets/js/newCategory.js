var iconAdd = document.querySelector("#AddCat");
var formCat = document.querySelector(".newCategory");


formCat.style.display = 'none';

iconAdd.addEventListener('click', (e) => {
    formCat.style.display = 'block'
})