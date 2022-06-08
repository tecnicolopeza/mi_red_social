function generarAleatorio(rangoInicio, rangoFin) {
    n = Math.round((Math.random() * (rangoFin - rangoInicio)) + rangoInicio); //hay que restarle el inicio porque a veces si sumas el inicio podria salirse del rango
    return n;
}