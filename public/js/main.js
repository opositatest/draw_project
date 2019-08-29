var key = null;

// FUNCIÓN ONLOAD QUE INICIALIZA VARIAS FUNCIONES
$(document).ready($(window).on('scroll' ,function(){
    // navbar();
    }
));

//FUNCION PARA MODIFICAR EL NAVBAR
function navbar(){
	var poll = $('#edge').offset().top;
	var stop = Math.round($(window).scrollTop());
	if (key) {
    	if (stop >= poll) {
	        $("#nav").animate({height: "70px"});
	        key = false;
    	}
    } else if (!key){
    	if (stop < poll) {
	        $("#nav").animate({height: "90px"});
	        key = true;
	    }
    }

    if (key == null){
    	if (stop >= poll) {
            $("#nav").css("height", "70px");
            key = false;
    	} else if (stop < poll) {
	        $("#nav").css("height", "90px");
	        key = true;
	    }
    }
}