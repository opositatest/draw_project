$(document).ready(function () {
    $("#prev").hide();
});

function previous(){
    var data = {"operation": 'prev', "offset": offset};
    $.ajax({
        data:  data, //datos que se envian a traves de ajax
        url:   '/encuestas/next-prev', //archivo que recibe la peticion
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
    var operation = "next";
    var data = {"operation": operation, "offset": offset};
    $.ajax({
        data:  data, //datos que se envian a traves de ajax
        url:   '/encuestas/next-prev', //archivo que recibe la peticion
        type:  'get', //método de envio
        beforeSend: function () {
            console.log("Procesando, espere por favor...");
        },
        success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
            cambiar(response);
        }
    });
}

function showMessage($message) {
    alert("Se está mostrando la " + $message + " página");
}

function cambiar(response){
    $contenedor = $("#contenedor");
    $contenedor.empty();

    $encuestas2 = JSON.parse(response[0]);

    for (var i = 0; i < $encuestas2.length; i++){

        //encuestas
        var div = document.createElement("div");
        div.setAttribute("class", "container ");
        div.setAttribute("style", "color: white; margin-bottom: 5%;");

        var row = document.createElement("div");
        row.setAttribute("class", "row justify-content-center");
        row.setAttribute("id", "beforeEncuestas");

        var col = document.createElement("div");
        col.setAttribute("class", "col-9 rounded roundedhov");

        var a = document.createElement("a");
        a.setAttribute("href", "/encuesta/" + $encuestas2[i].id);
        a.setAttribute("id", "portLink");

        var row2 = document.createElement("div");
        row2.setAttribute("class", "row justify-content-center");

        var h1 = document.createElement("h1");
        h1.setAttribute("id", "h1po");
        h1.setAttribute("class", "sizedh1");
        h1.innerText = $encuestas2[i].title;

        var row2_1 = document.createElement("div");
        row2_1.setAttribute("class", "row justify-content-center");
        row2_1.setAttribute("id", "portfolio");

        var img = document.createElement("img");
        img.setAttribute("src", '../img/' + $encuestas2[i].img);
        img.setAttribute("id", "imgPort");
        img.setAttribute("class", "sizedimg")

        //appends encuestas
        $(div).hide().appendTo($("#contenedor")).fadeIn(1000);
        $(row).hide().appendTo(div).fadeIn(1000);
        $(col).hide().appendTo(row).fadeIn(1000);
        $(a).hide().appendTo(col).fadeIn(1000);
        $(row2).hide().appendTo(a).fadeIn(1000);
        $(h1).hide().appendTo(row2).fadeIn(1000);
        $(row2_1).hide().appendTo(a).fadeIn(1000);
        $(img).hide().appendTo(row2_1).fadeIn(1000);
        window.scrollTo(0, 0);
    }

    if ($encuestas2[$encuestas2.length - 1].id === first) {
        $("#next").hide();
    } else {
        $("#next").fadeIn(1000);
    }
    if ($encuestas2[0].id === last) {
        $("#prev").hide();
    } else {
        $("#prev").fadeIn(1000);
    }

    offset = response[1];
}