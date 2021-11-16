function actualizaIndex(categoria) {

    $.post("/", {
            "data": categoria
        },
        function() {});
}