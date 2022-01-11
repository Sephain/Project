// import legend from "chartist-plugin-legend";

let btn = document.querySelector('#btn-chart');
let someDiv = document.getElementById('hate');
btn.onclick = function (e){
    e.preventDefault();

    var requestURL = 'data.json';
    var request = new XMLHttpRequest();
    request.open('GET', requestURL);
    request.responseType = 'json';
    request.send();

    request.onload = function() {
        var dataset = request.response;
        console.log('THIS IS JSON FILE');

        console.log(dataset);

        let data1 = {
            labels:dataset[0],
            series:[dataset[2], dataset[1]]
        }
        var options = {

            lineSmooth: Chartist.Interpolation.cardinal({
        fillHoles: true,
        }),
        plugins: [
            Chartist.plugins.legend({
                legendNames: ['Расходы', 'Доходы'],
                position: someDiv
            })
        ]
        };

        var chart = new Chartist.Line('.chart1', data1, options);

    // Let's put a sequence number aside so we can use it in the event callbacks
    var seq = 0,
    delays = 20,
    durations = 10;

    // Once the chart is fully created we reset the sequence
    chart.on('created', function() {
    seq = 0;
    });

    // On each drawn element by Chartist we use the Chartist.Svg API to trigger SMIL animations
    chart.on('draw', function(data) {
    seq++;

    if(data.type === 'line') {
    // If the drawn element is a line we do a simple opacity fade in. This could also be achieved using CSS3 animations.
    data.element.animate({
        opacity: {
        // The delay when we like to start the animation
        begin: seq * delays + 700,
        // Duration of the animation
        dur: durations,
        // The value where the animation should start
        from: 0,
        // The value where it should end
        to: 1
        }
    });
    } else if(data.type === 'label' && data.axis === 'x') {
    data.element.animate({
        y: {
        begin: seq * delays,
        dur: durations,
        from: data.y + 100,
        to: data.y,
        // We can specify an easing function from Chartist.Svg.Easing
        easing: 'easeOutQuart'
        }
    });
    } else if(data.type === 'label' && data.axis === 'y') {
    data.element.animate({
        x: {
        begin: seq * delays,
        dur: durations,
        from: data.x - 100,
        to: data.x,
        easing: 'easeOutQuart'
        }
    });
    } else if(data.type === 'point') {
    data.element.animate({
        x1: {
        begin: seq * delays,
        dur: durations,
        from: data.x - 10,
        to: data.x,
        easing: 'easeOutQuart'
        },
        x2: {
        begin: seq * delays,
        dur: durations,
        from: data.x - 10,
        to: data.x,
        easing: 'easeOutQuart'
        },
        opacity: {
        begin: seq * delays,
        dur: durations,
        from: 0,
        to: 1,
        easing: 'easeOutQuart'
        }
    });
    } else if(data.type === 'grid') {
    // Using data.axis we get x or y which we can use to construct our animation definition objects
    var pos1Animation = {
        begin: seq * delays,
        dur: durations,
        from: data[data.axis.units.pos + '1'] - 30,
        to: data[data.axis.units.pos + '1'],
        easing: 'easeOutQuart'
    };

    var pos2Animation = {
        begin: seq * delays,
        dur: durations,
        from: data[data.axis.units.pos + '2'] - 100,
        to: data[data.axis.units.pos + '2'],
        easing: 'easeOutQuart'
    };

    var animations = {};
    animations[data.axis.units.pos + '1'] = pos1Animation;
    animations[data.axis.units.pos + '2'] = pos2Animation;
    animations['opacity'] = {
        begin: seq * delays,
        dur: durations,
        from: 0,
        to: 1,
        easing: 'easeOutQuart'
    };

    data.element.animate(animations);
    }
    });


    }
}