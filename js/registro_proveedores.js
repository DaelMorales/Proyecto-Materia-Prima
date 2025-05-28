document.addEventListener('DOMContentLoaded', function() {
    var inputs = document.querySelectorAll('#formulario input');
    var selects = document.querySelectorAll('#formulario select');

    inputs.forEach(function(input) {
        input.setAttribute('required', '');
    });
    selects.forEach(function(select) {
        select.setAttribute('required', '');
    });

    document.getElementById('formulario').addEventListener('submit', function(event) {
        event.preventDefault();

        var nombres = document.getElementsByName('nombre')[0].value;
        var ape1 = document.getElementsByName('apellido1')[0].value;
        var ape2 = document.getElementsByName('apellido2')[0].value;
        var telefono = document.getElementById('telefono').value;
        var alcaldia = document.getElementById('alcaldia').value;
        var gen = document.getElementById('gen').value;
        var correo = document.getElementById('correo').value;
        var password = document.getElementById('password').value;

        var validationMessageContainer = document.getElementById('validation-message-container');
        validationMessageContainer.innerHTML = '';
        var alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger';

        var regexNumeros = /^[0-9]+$/;
        if (!telefono.match(regexNumeros)) {
            alertDiv.textContent = "El teléfono solo debe contener números.";
            validationMessageContainer.appendChild(alertDiv);
            validationMessageContainer.scrollIntoView({ behavior: 'smooth' });
            return;
        }

        var regexLetras = /^[a-zA-ZÁÉÍÓÚáéíóú\s]+$/;
        if (!nombres.match(regexLetras) || !ape1.match(regexLetras) || !ape2.match(regexLetras)) {
            alertDiv.textContent = "Los nombres y apellidos solo deben contener letras.";
            validationMessageContainer.appendChild(alertDiv);
            validationMessageContainer.scrollIntoView({ behavior: 'smooth' });
            return;
        }

        var regexCorreoPassword = /^[a-zA-Z0-9@._]+$/;
        if (!correo.match(regexCorreoPassword) || !password.match(regexCorreoPassword)) {
            alertDiv.textContent = "El correo y la contraseña solo deben contener letras y caracteres permitidos.";
            validationMessageContainer.appendChild(alertDiv);
            validationMessageContainer.scrollIntoView({ behavior: 'smooth' });
            return;
        }

        if (telefono.length != 10) {
            alertDiv.textContent = "El teléfono debe tener exactamente 10 dígitos.";
            validationMessageContainer.appendChild(alertDiv);
            validationMessageContainer.scrollIntoView({ behavior: 'smooth' });
            return;
        }
        if (!telefono.startsWith('55') && !telefono.startsWith('56')) {
            alertDiv.textContent = "El teléfono debe comenzar con 55 o 56.";
            validationMessageContainer.appendChild(alertDiv);
            validationMessageContainer.scrollIntoView({ behavior: 'smooth' });
            return;
        }
        var regexCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!regexCorreo.test(correo)) {
            alertDiv.textContent = "El correo debe contener @ y terminar con .com.";
            validationMessageContainer.appendChild(alertDiv);
            validationMessageContainer.scrollIntoView({ behavior: 'smooth' });
            return;
        }

        if (alcaldia === "") {
            alertDiv.textContent = "Selecciona tu alcaldía.";
            validationMessageContainer.appendChild(alertDiv);
            validationMessageContainer.scrollIntoView({ behavior: 'smooth' });
            return;
        }

        if (gen === "") {
            alertDiv.textContent = "Selecciona el género del tutor.";
            validationMessageContainer.appendChild(alertDiv);
            validationMessageContainer.scrollIntoView({ behavior: 'smooth' });
            return;
        }



        // Mostrar datos en el modal
        document.getElementById('modalNombre').innerText = nombres;
        document.getElementById('modalNombreCompleto').innerText = `${nombres} ${ape1} ${ape2}`;
        document.getElementById('modalTelefono').innerText = telefono;
        document.getElementById('modalAlcaldia').innerText = alcaldia;
        document.getElementById('modalGen').innerText = gen;
        document.getElementById('modalCorreo').innerText = correo;

        var confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        confirmModal.show();
    });

    document.getElementById('confirmBtn').addEventListener('click', function() {
        var form = document.getElementById('formulario');
        var formData = new FormData(form);

        fetch('./php/registro_proveedor.php', {
            method: 'POST',
            body: formData
        }).then(function(response) {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        }).then(function(data) {
            var messageContainer = document.getElementById('message-container');
            messageContainer.innerHTML = '';
            var alertDiv = document.createElement('div');
            if (data.success) {
                alertDiv.className = 'alert alert-success';
                alertDiv.textContent = data.message;
                form.reset();
                document.cookie = "session=active; path=/;";
                window.location.href = 'proveedores.html';
            } else {
                alertDiv.className = 'alert alert-danger';
                alertDiv.textContent = data.message;
            }
            messageContainer.appendChild(alertDiv);
            var confirmModal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
            confirmModal.hide();
            messageContainer.scrollIntoView({ behavior: 'smooth' });
        }).catch(function(error) {
            console.error('Error:', error);
            var messageContainer = document.getElementById('message-container');
            messageContainer.innerHTML = '';
            var alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger';
            alertDiv.textContent = 'Hubo un error al procesar la solicitud. Por favor, inténtalo de nuevo más tarde.';
            messageContainer.appendChild(alertDiv);
            messageContainer.scrollIntoView({ behavior: 'smooth' });
        });
    });
});
