
var PouceEnLair = document.querySelector('#likeVoteUser');
var divLike = document.querySelector('.NbrLike');

PouceEnLair.addEventListener('click', () => {
    var idFichier = PouceEnLair.getAttribute('data-fichier')
    ajaxLiked(idFichier);
})

function ajaxLiked(idFichier) {
    var data = new FormData();
    data.append('idFichier', idFichier);

    fetch('http://localhost/jlayers/public/vote/verifLiked', {
        method: 'post',
        body: data
    })
        .then((response) => {
            return response.json()
        })
        .then((data) => {
            if (data.action == 'Delete') {
                PouceEnLair.innerHTML = "<i class='fas fa-heart' style='font-size:30px;color:black'></i>"
            } else {
                PouceEnLair.innerHTML = "<i class='fas fa-heart' style='font-size:30px;color:red'></i>"
            }
            divLike.innerHTML = `<p>${data.voteTotal} Likes</p>`
        })
}
