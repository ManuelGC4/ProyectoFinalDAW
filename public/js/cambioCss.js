document.getElementById("cambioCss").onclick = function() { cambiarCSS() };
window.onload = function() { cambiarCSS() };

function cambiarCSS() {
    var tema = document.getElementById("cambioCss").checked;

    if (tema) {
        document.getElementById('css').href = "../../css/oscuro.css";
    } else {
        document.getElementById('css').href = "../../css/claro.css";
    }
}