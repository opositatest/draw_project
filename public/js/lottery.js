$(document).ready(function () {
    $("#prev").hide();
});


//funciones para la paginacion del historial
function previous(){
    var data = {"operation": 'prev', "offset": offset};
    $.ajax({
        data:  data, //datos que se envian a traves de ajax
        url:   '/lottery/historial', //archivo que recibe la peticion
        type:  'get', //método de envio
        beforeSend: function () {
            console.log("Procesando, espere por favor...");
        },
        success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
            cambiar(response);
        }
    });
}

function next(){
    var operation = "next"
    var data = {"operation": operation, "offset": offset};
    $.ajax({
        data:  data, //datos que se envian a traves de ajax
        url:   '/lottery/historial', //archivo que recibe la peticion
        type:  'get', //método de envio
        beforeSend: function () {
            console.log("Procesando, espere por favor...");
        },
        success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
            cambiar(response);
        }
    });
}

function cambiar(response){
    $contenedor = $("#contenedor");
    $contenedor.empty();

    $lotteries2 = JSON.parse(response[0]);

    for (var i = 0; i < $lotteries2.length; i++){

        //lotteries
        var row = document.createElement("div");
        row.setAttribute("class", "row justify-content-center");

        var col = document.createElement("div");
        col.setAttribute("class", "col-9 text-left");
        col.setAttribute("style", "margin-bottom: 50px");

        var div = document.createElement("div");
        div.setAttribute("class", "list-group");

        var b1 = document.createElement("button");
        b1.setAttribute("type", "button");
        b1.setAttribute("class", "list-group-item list-group-item-action bg-warning text-dark text-center");
        b1.innerHTML = "SORTEO " + $lotteries2[i].id;

        var b2 = document.createElement("button");
        b2.setAttribute("type", "button");
        b2.setAttribute("class", "list-group-item list-group-item-action");
        b2.innerHTML = "Prize: " + $lotteries2[i].prize.title;

        var b3 = document.createElement("button");
        b3.setAttribute("type", "button");
        b3.setAttribute("class", "list-group-item list-group-item-action");
        b3.innerHTML = "Participantes: " + $lotteries2[i].users.length;

        var b4 = document.createElement("button");
        b4.setAttribute("type", "button");
        b4.setAttribute("class", "list-group-item list-group-item-action");
        $fecha = $lotteries2[i].fecha;
        $fechagmt = new Date($fecha.timestamp * 1000);
        b4.innerHTML = "Fecha: " + $fechagmt.toLocaleDateString();


        var b5 = document.createElement("button");
        b5.setAttribute("type", "button");
        b5.setAttribute("class", "list-group-item list-group-item-action");
        if ($lotteries2[i].ganador){
            b5.innerHTML = "Ganador: User " + $lotteries2[i].ganador.id;
        } else {
            b5.innerHTML = "Ganador: No ha habido participantes";
        }

        //appends polls
        $(row).hide().appendTo($("#contenedor")).fadeIn(1000);
        $(col).hide().appendTo(row).fadeIn(1000);
        $(div).hide().appendTo(col).fadeIn(1000);
        $(b1).hide().appendTo(div).fadeIn(1000);
        $(b2).hide().appendTo(div).fadeIn(1000);
        $(b3).hide().appendTo(div).fadeIn(1000);
        $(b4).hide().appendTo(div).fadeIn(1000);
        $(b5).hide().appendTo(div).fadeIn(1000);
    }

    if ($lotteries2[$lotteries2.length - 1].id === first) {
        $("#next").hide();
    } else {
        $("#next").fadeIn(1000);
    }
    if ($lotteries2[0].id === last) {
        $("#prev").hide();
    } else {
        $("#prev").fadeIn(1000);
    }

    offset = response[1];
}
