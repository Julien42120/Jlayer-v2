var category = document.querySelector("#biblioCat");
var cards = document.querySelectorAll('.card')

category.addEventListener('change', (e) => {
    var id = e.target.value
    selectDrop(id);

})

function selectDrop(id) {
    cards.forEach(card => {
        var idCard = card.getAttribute("id");
        card.style.display = 'none'
        if (id == idCard) {
            card.style.display = 'block'
        }
        if (id == null || id == 'tout') {
            card.style.display = 'block'
        }
    })
}
