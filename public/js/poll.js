var puntuacion = 0;
var Id_poll = 1;
var PPos = 0;
var key = null;
var pos = 0;
var printed = 0;




// FUNCIÓN ONLOAD QUE INICIALIZA VARIAS FUNCIONES
function cargar(){
    imprimirPoll(poll.id);
    imprimirSavedComments();
    actualizarComments();
    limpiarTextArea();
    limpiarModal();
}

// FUNCION QUE SACA LAS PREGUNTAS Y RESPUESTAS
function imprimirPoll(id_poll){
    //datos poll
    var titulo = poll.title;
    // create titulo de la poll
    var h1 = document.createElement("h1");
    h1.setAttribute("id", "titulo_poll_h1");
    h1.setAttribute("style", "max-width: 1230px");
    h1.innerHTML = titulo;
    $(h1).hide().appendTo("#title").fadeIn(1000);
    imprimirQuestions(id_poll, this.pos, getPPos());
}

function imprimirQuestions(id_poll, pos, pos_question){
    //datos question
    var id_question = poll.questions[pos_question].id;
    var enunciado_question = poll.questions[pos_question].text;
    var ruta_img = "../../img/" + poll.questions[pos_question].image;

    // create imagen de la question
    var img = document.createElement("img");
    img.setAttribute("src", ruta_img);
    img.setAttribute("class", "img-fluid rounded");
    img.setAttribute("style", "width: 500px; height: 400px");
    $(img).hide().appendTo("#img_poll").fadeIn(1000);
    //$("#img_poll").append(img);

    // create enunciado de la question
    var h4 = document.createElement("h4");
    h4.setAttribute("id", "enunciado_question_h4");
    h4.innerHTML = enunciado_question;
    $(h4).hide().appendTo("#question").fadeIn(1000);
    //$("#question").append(h4);

    // create answers de la question
    for (var j = 0; j < poll.questions[pos_question].answers.length; j++){
        //datos answer
        var id_answer = poll.questions[pos_question].answers[j].id;
        var enunciado_answer = poll.questions[pos_question].answers[j].text;


        var div = document.createElement("div");
        div.setAttribute("class", "col-sm-8 justify-content-center text-center");
        div.setAttribute("style", "padding-bottom:20px");

        var answer = document.createElement("input");
        answer.setAttribute("type", "button");
        answer.setAttribute("id", id_answer);
        answer.setAttribute("class", "btn btn-dark btn-responsive");
        answer.setAttribute("style", "word-wrap: break-word; white-space:normal !important; width: 400px;");
        answer.setAttribute("value", enunciado_answer);
        answer.setAttribute("data-idResponse", id_answer);
        answer.setAttribute("data-idQuestion", id_question);
        answer.setAttribute("data-idPoll", id_poll);
        answer.setAttribute("onclick", "accion(this)");

        $(answer).hide().appendTo(div).fadeIn(1000);
        //div.append(answer);

        //$("#div_answers").append(div);
        $(div).hide().appendTo("#div_answers").fadeIn(1000);
        setPPos(pos_question + 1);
        //progress bar
        var num_preg = poll.questions.length + 1;
        var newWidth = (pos_question + 1) / num_preg * 100;
        progress(newWidth);
    }
    this.printed ++;
}

// funcion que imprime el result

function imprimirSolucion(pos){
    const container = window.document.getElementById('father-container');
    console.log(poll)
    for (let i = 0; i < poll.results.length; i++){
        if ( (poll.results[i].minVal)  <= (this.puntuacion) && (this.puntuacion) <= (poll.results[i].maxVal) ){
            container.className = 'd-flex flex-column';
            const childContainer = window.document.createElement('div');
            childContainer.className = 'child-container-poll';
            container.appendChild(childContainer);
            const title = window.document.createElement('div');
            childContainer.appendChild(title);
            const imageSolutionContainer = window.document.createElement('div');
            childContainer.appendChild(imageSolutionContainer);
            const textSolutionContainer = window.document.createElement('div');
            childContainer.appendChild(textSolutionContainer);

            const solucion = poll.results[i].text;
            const explicacion = poll.results[i].explanation;

            const h1 = document.createElement("h1");
            h1.innerHTML = solucion;
            $(h1).hide().appendTo(title).fadeIn(1000);

            const ruta = "../../img/" + poll.results[i].image;
            const img = document.createElement("img");
            img.setAttribute("src", ruta);
            img.setAttribute("style", "width: 400px; height: 400px");
            img.setAttribute("class", "img-fluid rounded");
            $(img).hide().appendTo(imageSolutionContainer).fadeIn(1000);

            const result = document.createElement("p");
            result.style.margin = "10px"
            result.innerHTML = explicacion;
            $(result).hide().appendTo(textSolutionContainer).fadeIn(1000);

            progress(100);
            break;
        }
    }

    const col = document.createElement("div");
    col.setAttribute("class", "col-sm-6 justify-content-center text-sm-right");
    col.setAttribute("id", "colRedo");
    $(col).appendTo("#div_answers");

    var col1 = document.createElement("div");
    col1.setAttribute("class", "col-sm-6 justify-content-center text-sm-left");
    col1.setAttribute("id", "colLottery");
    $(col1).appendTo("#div_answers");

    var redo = document.createElement("a");
    redo.setAttribute("type", "button");
    redo.setAttribute("class", "btn btn-danger");
    redo.setAttribute("style", "padding: 15px;");
    redo.setAttribute("href", "/poll/" + poll.id);
    redo.innerHTML = "Volver a jugar";

    var btnLottery = document.createElement("a");
    btnLottery.setAttribute("class", "btn btn-warning btn-lg btn-mar");
    btnLottery.setAttribute("href", "/lottery/add");
    btnLottery.innerHTML = "Suscríbete al lottery";

    $(redo).hide().appendTo("#colRedo").fadeIn(1000);
    $(btnLottery).hide().appendTo("#colLottery").fadeIn(1000);
}

// funcion que realizan los botones de las answers
function accion(boton){
    let selfQuestion;
    let selfAnswer;
    let selfValor;

    for (var j = 0; j < poll.questions.length; j++) {
        if ($(boton).attr("data-idQuestion") == poll.questions[j].id) {
            selfQuestion =  poll.questions[j];
            for (var i = 0; i < selfQuestion.answers.length; i++) {
                if ($(boton).attr("data-idResponse") == selfQuestion.answers[i].id) {
                    selfAnswer = selfQuestion.answers[i];
                }
            }
        }
    }
    selfValor = selfAnswer.value;
    setPuntuacion(selfValor);
    limpiarSalida();
    // si el numero de questions impresas es igual al total de questions de esa poll, sacamos el result
    if (this.printed == poll.questions.length){
        imprimirSolucion(this.pos);
    } else {
        imprimirQuestions(getEPos(), this.pos, getPPos());
    }
}

// funcion que resetea la question y los botones
function limpiarSalida(){
    $("#titulo_poll").empty();
    $("#img_poll").empty();
    $("#question").empty();
    $("#div_answers").empty();
}

//funcion que limpialosinputs del modal
function limpiarModal() {
    $("#userName").val("");
    $("#userEmail").val("");
    $("#userPass").val("");
    $("#validate").prop('checked', false);
}

// funcion que saca por pantalla los comments ya guardados
function imprimirSavedComments(){
    for($i = 0; $i < poll.comments.length; $i++){
        var div = document.createElement("div");
        div.setAttribute("class", "row rounded");
        div.setAttribute("style", "margin-bottom: 25px; padding: 5%; width 600px");

        // creo div col 1
        var div1 = document.createElement("div");
        div1.setAttribute("class", "col-sm-2 text-center");

        // creo img de div1
        var img = document.createElement("img");
        img.setAttribute("src", "/img/user.png");
        img.setAttribute("class", "img-circle");
        img.setAttribute("height", "65");
        img.setAttribute("width", "65");
        img.setAttribute("style", "padding-bottom: 10px");
        img.setAttribute("alt", "avatar");

        // meto img en div1
        $(img).hide().appendTo(div1).fadeIn(1000);

        // creo div col 2
        var div2 = document.createElement("div");
        div2.setAttribute("class", "col-sm-11 text-sm-left");
        div2.setAttribute("style", "margin-bottom: 10px");

        // creo p de div2
        var p = document.createElement("p");
        p.innerHTML = poll.comments[$i].text;

        // meto p en div 2
        $(p).hide().appendTo(div2).fadeIn(1000);

        // meto div1 y div2 en div row
        $(div1).hide().appendTo(div).fadeIn(1000);
        $(div2).hide().appendTo(div).fadeIn(1000);

        //meto div row en div row rowComment
        $(div).hide().prependTo("#rowComment").fadeIn(1000);

    }
}
// funcion que imprime los comments nuevos y los guarda en bbdd
function imprimirComments(){
    var texto = $("#coment").val();
    if (texto != "") {
        //llamada ajax para persistirlo
        saveComment();

        var comment = [];
        comment.push({"texto": texto, "poll": poll});

        // creo div row
        var div = document.createElement("div");
        div.setAttribute("class", "row rounded");
        div.setAttribute("style", "margin-bottom: 25px; padding: 5%; width 600px");

        // creo div col 1
        var div1 = document.createElement("div");
        div1.setAttribute("class", "col-sm-2 text-center");

        // creo img de div1
        var img = document.createElement("img");
        img.setAttribute("src", "/img/user.png");
        img.setAttribute("class", "img-circle");
        img.setAttribute("height", "65");
        img.setAttribute("width", "65");
        img.setAttribute("style", "padding-bottom: 10px");
        img.setAttribute("alt", "avatar");

        // meto img en div1
        $(img).hide().appendTo(div1).fadeIn(1000);

        // creo div col 2
        var div2 = document.createElement("div");
        div2.setAttribute("class", "col-sm-11 text-sm-left");
        div2.setAttribute("style", "margin-bottom: 10px");

        // creo p de div2
        var p = document.createElement("p");
        p.innerHTML = comment[0].texto;

        // meto p en div 2
        $(p).hide().appendTo(div2).fadeIn(1000);

        // meto div1 y div2 en div row
        $(div1).hide().appendTo(div).fadeIn(1000);
        $(div2).hide().appendTo(div).fadeIn(1000);

        //meto div row en div row rowComment
        $(div).hide().prependTo("#rowComment").fadeIn(1000);
        $("#coment").val("");
        limpiarTextArea();
    }
}
//llamada ajax
function saveComment(){
    const val = $("#coment").val();
    const pollId = poll.id;
    const data = {"texto": val, "poll":pollId};

    $.ajax({
        data:  data, //datos que se envian a traves de ajax
        url:   '/poll/comment/save', //archivo que recibe la peticion
        type:  'post', //método de envio
        beforeSend: function () {
            console.log("Procesando, espere por favor...");
        },
        success:  function (answer) { //una vez que el archivo recibe el request lo procesa y lo devuelve
            if(answer){
                alert(answer);
            } else {
                $new =  parseInt($("#number").text()) + 1;
                $("#number").text($new);
            }
        }
    });
}

//funcion que actualiza el numero que se muestra de comments totales
function actualizarComments(){
    $("#number").text(poll.comments.length);
}

//funcion que elimina la salida de los comments
function limpiarComments(){
    $("#rowComment").empty();
}

//funcion que limpia el textarea
function limpiarTextArea(){
    $("#coment").val("");
}

// funcion que actualiza la barra de progreso
function progress(newWidth){
    $("#PBar").css({width: newWidth + "%"});
    $("#PBar").val(newWidth + "%");
}

//funcion que hace la llamada ajax para añadir user
function addUser() {
    $userName = $("#userName").val();
    $userEmail = $("#userEmail").val();
    $userPass = $("#userPass").val();
    $check = $("#validate");
    if ($userName && $userEmail && $userPass) {
                if ($check.is(':checked')){
                    $userdata = {"name": $userName, "mail": $userEmail, "pass": $userPass};
                    $.ajax({
                        data:  $userdata, //datos que se envian a traves de ajax
                        url:   '/lottery/add', //archivo que recibe la peticion
                        type:  'post', //método de envio
                        beforeSend: function () {
                            console.log("Añadiendo user...");
                        },
                        success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
                            limpiarModal();
                            showAlert(response[0], response[1]);
                        }
                    });
                }
            }
}

// funcion que crea una alerta para avisar de que se ha suscrito correctamente
function showAlert(title, message) {
    $("#myModal").empty();
    var al = document.createElement("div");
    al.setAttribute("class", "bg-success text-white");
    al.setAttribute("role", "alert");
    al.setAttribute("style", "margin: 350px 650px 0px 650px; padding: 1%; min-width: 225px");

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
    redir.setAttribute("href", "/lottery");
    redir.innerText = "ver lottery";


    $(al_title).hide().appendTo(al).fadeIn(1000);
    $(al_p).hide().appendTo(al).fadeIn(1000);
    $(row).hide().appendTo(al).fadeIn(1000);
    $(redir).hide().appendTo(row).fadeIn(1000);


    $(al).hide().appendTo("#myModal").fadeIn(1000);
}


// GETTERS Y SETTERS
function setEPos(EPos){
    this.Id_poll = Id_poll;
}

function getEPos(){
    return this.Id_poll;
}

function setPPos(PPos){
    this.PPos = PPos;
}

function getPPos(){
    return this.PPos;
}

function setPuntuacion(nueva_puntuacion){
    this.puntuacion += nueva_puntuacion;
}

function getPuntuacion(){
    return this.puntuacion;
}