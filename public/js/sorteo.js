$(document).ready(function () {
    $("#prev").hide();
});


//funciones para la paginacion del historial
function previous(){
    var data = {"operation": 'prev', "offset": offset};
    $.ajax({
        data:  data, //datos que se envian a traves de ajax
        url:   '/sorteo/historial', //archivo que recibe la peticion
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
        url:   '/sorteo/historial', //archivo que recibe la peticion
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

    $sorteos2 = JSON.parse(response[0]);

    for (var i = 0; i < $sorteos2.length; i++){

        //sorteos
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
        b1.innerHTML = "SORTEO " + $sorteos2[i].id;

        var b2 = document.createElement("button");
        b2.setAttribute("type", "button");
        b2.setAttribute("class", "list-group-item list-group-item-action");
        b2.innerHTML = "Premio: " + $sorteos2[i].premio.title;

        var b3 = document.createElement("button");
        b3.setAttribute("type", "button");
        b3.setAttribute("class", "list-group-item list-group-item-action");
        b3.innerHTML = "Participantes: " + $sorteos2[i].usuarios.length;

        var b4 = document.createElement("button");
        b4.setAttribute("type", "button");
        b4.setAttribute("class", "list-group-item list-group-item-action");
        $fecha = $sorteos2[i].fecha;
        $fechagmt = new Date($fecha.timestamp * 1000);
        b4.innerHTML = "Fecha: " + $fechagmt.toLocaleDateString();


        var b5 = document.createElement("button");
        b5.setAttribute("type", "button");
        b5.setAttribute("class", "list-group-item list-group-item-action");
        if ($sorteos2[i].ganador){
            b5.innerHTML = "Ganador: Usuario " + $sorteos2[i].ganador.id;
        } else {
            b5.innerHTML = "Ganador: No ha habido participantes";
        }

        //appends encuestas
        $(row).hide().appendTo($("#contenedor")).fadeIn(1000);
        $(col).hide().appendTo(row).fadeIn(1000);
        $(div).hide().appendTo(col).fadeIn(1000);
        $(b1).hide().appendTo(div).fadeIn(1000);
        $(b2).hide().appendTo(div).fadeIn(1000);
        $(b3).hide().appendTo(div).fadeIn(1000);
        $(b4).hide().appendTo(div).fadeIn(1000);
        $(b5).hide().appendTo(div).fadeIn(1000);
    }

    if ($sorteos2[$sorteos2.length - 1].id === first) {
        $("#next").hide();
    } else {
        $("#next").fadeIn(1000);
    }
    if ($sorteos2[0].id === last) {
        $("#prev").hide();
    } else {
        $("#prev").fadeIn(1000);
    }

    offset = response[1];
}

function borrarse() {
    var userEmail = $("#usermail").val();
    var userPass = $("#userpass").val();
    var check = $("#validate");
    if (userEmail !== "") {
        if (userPass !== "") {
            if (check.is(':checked')){
                var userdata = {"mail": userEmail, "pass": userPass};
                $.ajax({
                    data:  userdata, //datos que se envian a traves de ajax
                    url:   '/sorteo/leave', //archivo que recibe la peticion
                    type:  'post', //método de envio
                    beforeSend: function () {
                        console.log("Borrando usuario del sorteo...");
                    },
                    success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
                        limpiarModal();
                        showAlert(response[0], response[1]);
                    }
                });
            }
        }
    }
}
//funcion que limpialosinputs del modal
function limpiarModal() {
    $("#usermail").val("");
    $("#userpass").val("");
    $("#validate").attr('checked', 'false');
}
// funcion que crea una alerta para avisar de que se ha suscrito correctamente
function showAlert(title, message) {
    $("#borrarseModal").empty();
    var al = document.createElement("div");
    al.setAttribute("class", "bg-success text-white rounded");
    al.setAttribute("role", "alert");
    al.setAttribute("style", "margin: 350px 650px 0px 650px; padding: 0.5%");

    var al_title = document.createElement("h4");
    al_title.setAttribute("class", "text-center");
    al_title.innerText = title;

    var al_p = document.createElement("p");
    al_p.setAttribute("class", "text-center");
    al_p.innerText = message;

    var row = document.createElement("row");
    row.setAttribute("class", "row justify-content-center");

    var redir = document.createElement("a");
    redir.setAttribute("class", "btn btn-warning btn-large text-center");
    redir.setAttribute("type", "button");
    redir.setAttribute("href", "/sorteo");
    redir.innerText = "Aceptar";


    $(al_title).hide().appendTo(al).fadeIn(1000);
    $(al_p).hide().appendTo(al).fadeIn(1000);
    $(row).hide().appendTo(al).fadeIn(1000);
    $(redir).hide().appendTo(row).fadeIn(1000);


    $(al).hide().appendTo("#borrarseModal").fadeIn(1000);
}