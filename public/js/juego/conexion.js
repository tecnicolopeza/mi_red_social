//API - IMAGEN Y NOMBRE
// Here we define our query as a multi-line string
// Storing it in a separate .graphql/.gql file is also possible
var query = `
query ($page : Int, $perPage: Int) {
    Page(page: $page, perPage: $perPage){
        characters(sort: FAVOURITES_DESC) { 
            id
            name{
                full
            }
            favourites
            image{
                large
            }
        }
    }

}
`;

// Define our query variables and values that will be used in the query request
// Tiene que llamarse dos veces en llamadas distintias porque solo permite de 50 en 50
var variables = {
    page: 1,
    perPage: 50
};

var variables2 = {
    page: 2,
    perPage: 50
};

// Define the config we'll need for our Api request
var url = 'https://graphql.anilist.co';
var options = {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    body: JSON.stringify({
        query: query,
        variables: variables
    })
};

var options2 = {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    body: JSON.stringify({
        query: query,
        variables: variables2
    })
};

var personajes = []; //lista de personajes

// Make the HTTP Api request
//Llamada a la api
function obtenerListaPersonajes() {

    fetch(url, options).then(handleResponse)
        .then(handleData)
        .catch(handleError);

}

function obtenerListaPersonajes2() {

    fetch(url, options2).then(handleResponse)
        .then(handleData)
        .catch(handleError);

}

function handleResponse(response) {
    return response.json().then(function(json) {
        return response.ok ? json : Promise.reject(json);
    });
}

function handleData(data) {
    console.log(data.data.Page.characters[0].name.full);
    agregarALista(data.data.Page.characters); //mete los personajes en el array
    console.log(personajes);

    //Lamamos al metodo principal de juego, se llama aqui por motivos de sincronia
    jugar();
}

function handleError(error) {
    console.error(error);

}

function agregarALista(lista) {
    lista.forEach(personaje => {
        personajes.push(personaje);
    });
}