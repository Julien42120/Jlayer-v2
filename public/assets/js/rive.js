const r = new rive.Rive({
    src: 'http://localhost/jlayers/public/assets/rive/new_file.rev',
    canvas: document.getElementById('rive'),
    autoplay: true,
    stateMachines: 'State Machine 1',
    fit: rive.Fit.cover,
    artboard(_controller = SimpleAnimation('idle'));


});