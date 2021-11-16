function actualizaIndex(categoria) {


    $.ajax({
        type: "POST",
        url: "/es",
        data: {
            categoria: categoria
        },
        dataType: "json",
        success: function(response) {
            console.log(response);
        }
    });


    // var render = document.getElementById('actualizaIndexRender');

    // render.innerHTML = "{{ render(path('index', {categoriaId: " + categoria + "})) }}";
}