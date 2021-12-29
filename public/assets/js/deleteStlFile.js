console.log('ppl');

async function rewriteList(data, fichierId) {
    if (Array.isArray(data)) {
        data.forEach((element) => {
            console.log(element);
            document.querySelector('.listFile').innerHTML += `	
        <a class="btn mx-2 deleteFileBtn" id="${element}" data-fichier-id="${fichierId}">
            <i class="fas fa-trash-alt" style='font-size:20px;color:red'></i>
            ${element}
            <p class="">Supprimer</p>
        </a>
        `
        })
    } else {
        Object.keys(data).map((element) => {
            document.querySelector('.listFile').innerHTML += `	
        <a class="btn mx-2 deleteFileBtn" id="${data[element]}" data-fichier-id="${fichierId}">
            <i class="fas fa-trash-alt" style='font-size:20px;color:red'></i>
            ${data[element]}
            <p class="">Supprimer</p>
        </a>
        `

        })
    }
}

function loadEvent() {
    const fichiersStl = document.querySelectorAll('.deleteFileBtn')
    fichiersStl.forEach((fichier) => {
        fichier.addEventListener('click', (e) => {
            console.log(fichier);
            let fichierId = fichier.getAttribute('data-fichier-id')
            fetch('http://localhost/jlayers-v2/public/fichiers/stl/' + fichierId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ fichierName: fichier.id })
            }).then((response) => {
                return response.json()
            }).then(async (data) => {
                console.log(document.querySelector('.listFile'), Object.keys(data));
                document.querySelector('.listFile').innerHTML = ''
                await rewriteList(data, fichierId);
                loadEvent()
            })
        })
    })
}
loadEvent()