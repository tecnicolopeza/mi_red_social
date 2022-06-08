window.onload = inicializar;

var ultimoAleatorio = 0;
var rondas = 10
var puntuacion = 0

var input = document.getElementById("respuesta");
input.addEventListener("keydown", function(e) {
    if (e.key === "Enter") {
        comprobarRespuesta();
    }
});


function inicializar() {
    generaTabla();
    obtenerListaPersonajes();
    obtenerListaPersonajes2();

}

function jugar() {

    if (rondas != 0) {
        if (personajes != null) {

            let aleatorio = generarAleatorio(0, 99);

            console.log("Nuevo: " + aleatorio);
            console.log("Antiguo: " + ultimoAleatorio);

            if (aleatorio == ultimoAleatorio) {
                //Sistema para que siempre saque uno distinto
                jugar();

            } else {
                ultimoAleatorio = aleatorio;
                cambiarPersonaje(aleatorio);
            }

        }
    } else {
        if (puntuacion >= 30) {
            alert("¡Partida acabada! Medala de ORO  \nSal un poco a que te de el sol");
        } else if (puntuacion < 30 && puntuacion >= 20) {
            alert("¡Partida acabada! Medala de PLATA  \nNada mal :o");
        } else {
            alert("¡Partida acabada! Medala de BRONCE  \nTe falta ver un poco de anime compañero :P");
        }

        rondas = 10;
        puntuacion = 0;
        document.getElementById("rondas").innerText = rondas;
        document.getElementById("puntuacion").innerText = puntuacion;
        jugar();

    }



}

function cambiarPersonaje(aleatorio) {
    let tabla = document.getElementById("tablaJuego");
    if (personajes[aleatorio] != undefined) {
        tabla.setAttribute("background", personajes[aleatorio].image.large);
    }

}

async function comprobarRespuesta() {
    let respuesta = document.getElementById("respuesta").value;

    if (respuesta == "" || respuesta == " ") {
        alert("La proxima vez intenta poner algo");
    } else {

        if (validarNombre(respuesta.toLowerCase(), personajes[ultimoAleatorio].name.full.toLowerCase())) {
            alert("¡Se nota que sabes de esto!");
            puntuacion = puntuacion + intentos;
        } else {
            alert("¡Fallaste! Te queda bastante anime por ver");
        }
    }

    document.getElementById("respuesta").value = null;
    document.getElementById("puntuacion").innerText = puntuacion;
    rondas--;
    document.getElementById("rondas").innerText = rondas;
    restablecer();
    setTimeout(function() {

        jugar();

    }, 600);
}

function validarNombre(respuesta, nombre) {
    let correcto = false;

    let nombreSeparado = nombre.split(" ");

    nombreSeparado.forEach(parteNombre => {
        if (respuesta == parteNombre) {
            correcto = true;
        }
    });


    return correcto;

}