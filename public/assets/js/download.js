

$(document).ready(function () {

    // console.log(hrefFiles);
    var filesSTL = document.querySelectorAll('.downloadFiles');
    var hrefFiles = [];

    for (var i = 0; i < filesSTL.length; i++) {
        hrefFiles.push(filesSTL[i].getAttribute('data-href'))
    }

    $('#downloadAll').click(function () {
        // $('a.download_file > href').each(function () {
        //     $(this).trigger("click");
        // });

        // return false; //cancel navigation
        console.log('coucou');
        download('../../upload/FichierSTL/Double-Spiral-Vase2-614c76818cdbf.STL')
    });
});
// scene.clearColor = new BABYLON.Color3(0.5, 0.8, 0.5);
scene.clearColor = new BABYLON.Color3(1, 0, 0);
