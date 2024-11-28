function mostrarImagen(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            document.getElementById('imagen-grande-src').src = e.target.result;
            document.getElementById('imagen-grande').classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function actualizarVistaPrevia() {
    var titulo = document.getElementById('titulo').value;
    var descripcion = document.getElementById('descripcion').value;
    
    document.getElementById('titulo-previo').textContent = titulo;
    document.getElementById('descripcion-previa').textContent = descripcion;
}