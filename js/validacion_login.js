document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar que se envíe el formulario

        // Obtener valores del formulario
        var correo = document.getElementById('correo').value;
        var password = document.getElementById('password').value;
        console.log("Correo ingresado:", correo); // Mensaje de depuración
        var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Validar el correo para determinar si cumple con el formato requerido
        if (!regexCorreo.test(correo)) {
            console.log("Validación fallida"); // Mensaje de depuración
            alert("El correo ingresado no es válido. Asegúrate de que contenga '@' seguido de un dominio.");
            return;
        }

        console.log("Validación exitosa"); // Mensaje de depuración

        // Enviar los datos del formulario al servidor mediante AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "./php/login.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                console.log(xhr.responseText); // Depurar la respuesta del servidor
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        document.cookie = "session=active; path=/;";
                        window.location.href = response.redirect; // Redirigir según el valor de 'redirect'
                    } else {
                        alert(response.message); // Mostrar mensaje de error
                    }
                } catch (e) {
                    console.error('La respuesta no es JSON válido:', e);
                    alert('Hubo un error en el servidor. Por favor, inténtalo de nuevo más tarde.');
                }
            }
        };
        xhr.send("correo=" + encodeURIComponent(correo) + "&password=" + encodeURIComponent(password));
    });
});
