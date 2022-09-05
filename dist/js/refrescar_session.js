var intervalo = setInterval(peticion, 10*1000);

function peticion() {
    return $.ajax({
        url: "../refrescar_session.php",
        method: "GET"
    });
}