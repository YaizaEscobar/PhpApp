//validar los campos del formulario
function validarFormulario() {
    const nombre = document.getElementById('nombre').value;
    const descripcion = document.getElementById('descripcion').value;
    const precio = document.getElementById('precio').value;
    const calidad = document.getElementById('calidad').value;
    const nombreImagen = document.getElementById('nombreImagen').value;
    const imagen = document.getElementById('imagen');
    const errorMensaje = document.getElementById('errorMensaje');

    // Restablecer el mensaje de error
    errorMensaje.textContent = '';

    // Verificar que el nombre solo contenga letras mayúsculas, letras minúsculas y espacios
    for (let i = 0; i < nombre.length; i++) {
        let charCode = nombre.charCodeAt(i);
        if (!((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode === 32)) {
            errorMensaje.textContent = 'El nombre debe contener solo letras y espacios.';
            return false; // Evitar el envío del formulario
        }
    }

    // Verificar que la descripcion solo contenga letras mayúsculas, letras minúsculas y espacios
    for (let i = 0; i < descripcion.length; i++) {
        let charCode = descripcion.charCodeAt(i);
        if (!((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122) || charCode === 32)) {
            errorMensaje.textContent = 'La descripcion debe contener solo letras y espacios.';
            return false; // Evitar el envío del formulario
        }
    }

    //comprobar que el precio sea numerico y no este vacio
    if (precio === "" || isNaN(precio)) {
        errorMensaje.textContent = 'El precio debe ser un número.';
        return false; // Evitar el envío del formulario
    }

    //comprobar que la calidad sean caracteres B/P/b/p
    if (calidad === "" || !/^[BbPp]$/.test(calidad)) {
        errorMensaje.textContent = 'La calidad debe ser "B" o "P" (mayúsculas o minúsculas).';
        return false; // Evitar el envío del formulario
    }

    // Verificar que el nombre de la imagen solo contenga letras mayúsculas, letras minúsculas
    for (let i = 0; i < nombreImagen.length; i++) {
        let charCode = nombreImagen.charCodeAt(i);
        if (!((charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122))) {
            errorMensaje.textContent = 'El nombre de la imagen debe contener solo letras (mayúsculas o minúsculas).';
            return false; // Evitar el envío del formulario
        }
    }

    // Verificar que se haya seleccionado un archivo de imagen
    if (imagen.files.length === 0) {
        errorMensaje.textContent = 'Selecciona una imagen JPEG o JPG.';
        return false; // Evitar el envío del formulario
    }
    // Si todo está bien, se permite el envío del formulario
    return true;
}
