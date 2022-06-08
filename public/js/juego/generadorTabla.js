let intentos = 4;
let nivel = "";
let mensajeIntentos = "Remaining attempts: "

function generaTabla() {
    // Obtenemos la referencia del elemento body
    let textoIntentos = document.getElementById("intentos");
    textoIntentos.innerText = mensajeIntentos + intentos;
    var body = document.getElementById("tabla");

    // Creamos un elemento <table> y un elemento <tbody>
    var tabla = document.createElement("table");
    tabla.setAttribute("id", "imagenTabla");
    tabla.setAttribute("class", "mx-auto");
    var tblBody = document.createElement("tbody");
    tblBody.setAttribute("id", "tablaJuego");
    // Creamos las celdas
    for (var i = 0; i < 4; i++) {
        // Creamos las hileras de la tabla
        var fila = document.createElement("tr");
        for (var j = 0; j < 3; j++) {
            // Crea un elemento <td> y un nodo de texto, hace que el nodo de
            // texto sea el contenido de <td>, ubica el elemento <td> al final
            // de la hilera de la tabla
            var celda = document.createElement("td");
            celda.setAttribute("id", "oculto");
            celda.setAttribute("onclick", "revelarCelda(this)");
            fila.appendChild(celda);
        }
        // agregamos la hilera al final de la tabla (al final del elemento tblbody)
        tblBody.appendChild(fila);
    }
    // posicionamos el <tbody> debajo del elemento <table>
    tabla.appendChild(tblBody);
    // appends <table> into <body>
    body.appendChild(tabla);
    // modifica el atributo "border" de la tabla y lo fija a "2";
    // tabla.setAttribute("border", 2);
} //*/


function revelarCelda(celda) {

    //Si la celda aun no se ha tocado
    if (celda.getAttribute("id") != "visible") {
        let textoIntentos = document.getElementById("intentos");
        if (intentos > 0) {
            celda.setAttribute("id", "visible");
            intentos--;
            textoIntentos.innerText = mensajeIntentos + intentos;
            console.log('Intentos: ' + intentos);
        } else {
            alert('Ha llegado al máximo de intentos, tiene que responder');
        }
    }


}


function restablecer() {
    intentos = 4;
    let textoIntentos = document.getElementById("intentos");
    textoIntentos.innerText = mensajeIntentos + intentos;

    let casillas = document.getElementsByTagName("td");

    for (var i = 0; i < casillas.length; i++) {
        casillas[i].setAttribute("id", "oculto");
    }
}