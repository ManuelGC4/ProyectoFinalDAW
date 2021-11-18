window.onload = cambiarTemaColor(window.localStorage.getItem('tema'));
window.onload = cambiarIdioma(window.localStorage.getItem('idioma'));

function cambiarIdioma(idioma) {
    var url = window.location.href;
    var drop = document.getElementById("idiomaDrop");

    switch (idioma) {
        case "EN":
            if (url.search("/es") > 0) {
                window.location.href = url.replace("/es", "/en");
            }
            break;
        case "ES":
            if (url.search("/en") > 0) {
                window.location.href = url.replace("/en", "/es");
            }
            break;
    }

    drop.innerHTML = idioma;
    window.localStorage.setItem('idioma', idioma);
}

function cambiarTemaColor(tema) {
    var dropdownMenu = document.getElementById("dropdownMenu");
    var dropdownIdioma = document.getElementById("dropdownIdioma");
    var idiomaDrop = document.getElementById("idiomaDrop");
    var cssClaro = document.getElementById("cssClaro");
    var cssOscuro = document.getElementById("cssOscuro");

    switch (tema) {
        case "claro":
            if (dropdownMenu.getAttribute("class") != "dropdown-menu") {
                dropdownMenu.setAttribute("class", "dropdown-menu");
            }

            if (idiomaDrop.getAttribute("class") != "btn btn-light dropdown-toggle") {
                idiomaDrop.setAttribute("class", "btn btn-light dropdown-toggle");
            }

            if (dropdownIdioma.getAttribute("class") != "dropdown-menu") {
                dropdownIdioma.setAttribute("class", "dropdown-menu");
            }

            if (cssClaro.hasAttribute("disabled")) {
                cssOscuro.setAttribute("disabled", "");
                cssClaro.removeAttribute("disabled");
            }
            break;
        case "oscuro":
            if (dropdownMenu.getAttribute("class") != "dropdown-menu dropdown-menu-dark") {
                dropdownMenu.setAttribute("class", "dropdown-menu dropdown-menu-dark");
            }

            if (idiomaDrop.getAttribute("class") != "btn btn-dark dropdown-toggle") {
                idiomaDrop.setAttribute("class", "btn btn-dark dropdown-toggle");
            }

            if (dropdownIdioma.getAttribute("class") != "dropdown-menu dropdown-menu-dark") {
                dropdownIdioma.setAttribute("class", "dropdown-menu dropdown-menu-dark");
            }

            if (cssOscuro.hasAttribute("disabled")) {
                cssClaro.setAttribute("disabled", "");
                cssOscuro.removeAttribute("disabled");
            }
            break;
    }

    window.localStorage.setItem('tema', tema);
}

/*
window.onload = function getUrl() {
}
*/