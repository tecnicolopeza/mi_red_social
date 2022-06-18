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
            alert("¡Partida acabada! Medalla de ORO  \nSal un poco a que te de el sol");
        } else if (puntuacion < 30 && puntuacion >= 20) {
            alert("¡Partida acabada! Medalla de PLATA  \nNada mal :o");
        } else {
            alert("¡Partida acabada! Medalla de BRONCE  \nTe falta ver un poco de anime compañero :P");
        }

        rondas = 10;
        puntuacion = 0;
        document.getElementById("rondas").innerText = rondas;
        document.getElementById("puntuacion").innerText = puntuacion;
        jugar();

    }

}

function cambiarPersonaje(aleatorio) { //cambia imagen
    let tabla = document.getElementById("tablaJuego");
    if (personajes[aleatorio] != undefined) {
        tabla.setAttribute("background", personajes[aleatorio].image.large);
    }

}

async function comprobarRespuesta() { //asincrona para que permita el setTimeout
    let respuesta = document.getElementById("respuesta").value;

    if (respuesta == "" || respuesta == " ") {
        alert("El personaje era " + personajes[ultimoAleatorio].name.full);
    } else {

        if (validarNombre(respuesta.toLowerCase(), personajes[ultimoAleatorio].name.full.toLowerCase())) {
            alert("¡Se nota que sabes de esto! Era " + personajes[ultimoAleatorio].name.full);
            puntuacion = puntuacion + intentos;
        } else {
            alert("¡Fallaste! Te queda mucho anime por ver, era " + personajes[ultimoAleatorio].name.full);
        }
    }

    document.getElementById("respuesta").value = null;
    document.getElementById("puntuacion").innerText = puntuacion;
    rondas--;
    document.getElementById("rondas").innerText = rondas;
    restablecer();
    setTimeout(function() { //para que no de fallo con la animacion y no se vea el personaje antes de tiempo

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