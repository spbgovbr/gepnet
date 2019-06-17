$(document).ready(function () {
    var gauge1 = new RGraph.Gauge({
        id: 'cvs1',
        min: 0,
        max: 100,
        value: [80, 100],
        options: {
            textAccessible: true
        }
    }).grow();

    var gauge2 = new RGraph.Gauge({
        id: 'cvs2',
        min: 0,
        max: 100,
        value: 100,
        options: {
            textAccessible: true
        }
    }).grow({frames: 25});


    setTimeout(function () {
        gauge1.value = [10, 20];
        gauge1.grow();

        gauge2.value = 16;
        gauge2.grow();
    }, 3500);
});

