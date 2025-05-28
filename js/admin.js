document.addEventListener('DOMContentLoaded', function() {
    fetchProveedores();
    fetchUsuarios();
});

function fetchUsuarios() {
    axios.get('./php/fetch_usuarios.php')
        .then(function(response) {
            const usuarios = response.data;
            const usuariosTableBody = document.querySelector('#usuariosTable tbody');
            usuariosTableBody.innerHTML = '';
            usuarios.forEach(function(usuario) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${usuario.nombre}</td>
                    <td>${usuario.apPat}</td>
                    <td>${usuario.apMat}</td>
                    <td>${usuario.telefono}</td>
                    <td>${usuario.alcaldia}</td>
                    <td>${usuario.gen}</td>
                    <td>${usuario.correo}</td>
                    <td>${usuario.contrasena}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editUsuario(${usuario.id})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteUsuario(${usuario.id})">Eliminar</button>
                    </td>
                `;
                usuariosTableBody.appendChild(row);
            });
        })
        .catch(function(error) {
            console.error('Error fetching usuarios:', error);
        });
}

function fetchProveedores() {
    axios.get('./php/fetch_proveedores.php')
        .then(function(response) {
            const proveedores = response.data;
            const profTableBody = document.querySelector('#profTable tbody');
            profTableBody.innerHTML = '';
            proveedores.forEach(function(proveedor) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${proveedor.nombre}</td>
                    <td>${proveedor.apPat}</td>
                    <td>${proveedor.apMat}</td>
                    <td>${proveedor.telefono}</td>
                    <td>${proveedor.alcaldia}</td>
                    <td>${proveedor.gen}</td>
                    <td>${proveedor.correo}</td>
                    <td>${proveedor.contrasena}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editProveedor(${proveedor.idProv})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteProveedor(${proveedor.idProv})">Eliminar</button>
                    </td>
                `;
                profTableBody.appendChild(row);
            });
        })
        .catch(function(error) {
            console.error('Error fetching proveedores:', error);
        });
}

function editUsuario(id) {
    axios.get(`./php/get_usuario.php?id=${id}`)
        .then(function(response) {
            const usuario = response.data;

            document.getElementById('editNombre').value = usuario.nombre;
            document.getElementById('editApellido1').value = usuario.apPat;
            document.getElementById('editApellido2').value = usuario.apMat;
            document.getElementById('editTelefono').value = usuario.telefono;
            document.getElementById('editAlcaldia').value = usuario.alcaldia;
            document.getElementById('editGen').value = usuario.gen;
            document.getElementById('editCorreo').value = usuario.correo;
            document.getElementById('editPassword').value = usuario.contrasena;

            const editUserForm = document.getElementById('editUserForm');
            editUserForm.setAttribute('data-id', id);

            const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editUserModal.show();
            alert(response.data)
        })
        .catch(function(error) {
            console.error('Error fetching usuario:', error);
        });
}

function deleteUsuario(id) {
    console.log('Enviando ID:', id); // Imprimir el ID para depuración
    axios.post('./php/borrar_usuario.php', { id: id })
        .then(function(response) {
            alert(response.data); // Imprimir la respuesta del servidor
            fetchUsuarios();
        })
        .catch(function(error) {
            console.error('Error borrando usuario:', error);
        });
}


function editProveedor(idProv) {
    axios.get(`./php/get_proveedor.php?idProv=${idProv}`)
        .then(function(response) {
            const proveedor = response.data;

            document.getElementById('editPNombre').value = proveedor.nombre;
            document.getElementById('editPApellido1').value = proveedor.apPat;
            document.getElementById('editPApellido2').value = proveedor.apMat;
            document.getElementById('editPTelefono').value = proveedor.telefono;
            document.getElementById('editPAlcaldia').value = proveedor.alcaldia;
            document.getElementById('editPGen').value = proveedor.gen;
            document.getElementById('editPCorreo').value = proveedor.correo;
            document.getElementById('editPassword').value = proveedor.contrasena;

            const editProveedorForm = document.getElementById('editProveedorForm');
            editProveedorForm.setAttribute('data-id', idProv);

            const editProveedorModal = new bootstrap.Modal(document.getElementById('editProveedorModal'));
            editProveedorModal.show();
            alert(response.data);
        })
        .catch(function(error) {
            console.error('Error fetching proveedor:', error);
        });
}

function deleteProveedor(idProv) {
    console.log('Enviando ID:', idProv); // Imprimir el ID para depuración
    axios.post('./php/borrar_proveedor.php', { idProv: idProv })
        .then(function(response) {
            alert(response.data); // Imprimir la respuesta del servidor
            fetchUsuarios();
        })
        .catch(function(error) {
            console.error('Error borrando proveedor:', error);
        });
}


document.getElementById('createUserForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    axios.post('./php/crear_usuario.php', formData)
        .then(function(response) {
            alert(response.data); // Imprimir la respuesta del servidor para depuración
            fetchUsuarios();
            const createUserModal = new bootstrap.Modal(document.getElementById('createUserModal'));
            createUserModal.hide();
        })
        .catch(function(error) {
            console.error('Error creating usuario:', error);
        });
});

document.getElementById('createProveedorForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    axios.post('./php/crear_proveedor.php', formData)
        .then(function(response) {
            alert(response.data); // Imprimir la respuesta del servidor para depuración
            fetchUsuarios();
            const createProveedorModal = new bootstrap.Modal(document.getElementById('createProveedorModal'));
            createProveedorModal.hide();
        })
        .catch(function(error) {
            console.error('Error creating proveedor:', error);
        });
});


document.getElementById('editUserForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const id = event.target.getAttribute('data-id');
    formData.append('id', id);

    axios.post('./php/editar_usuario.php', formData)
        .then(function(response) {
            fetchUsuarios();
            const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editUserModal.hide();
            alert(response.data);
            alert("Usuario editado correctamente");
        })
        .catch(function(error) {
            console.error('Error editing usuario:', error);
        });
});

document.getElementById('editProveedorForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const idProv = event.target.getAttribute('data-id');
    formData.append('idProv', idProv);

    axios.post('./php/editar_proveedor.php', formData)
        .then(function(response) {
            fetchUsuarios();
            const editProveedorModal = new bootstrap.Modal(document.getElementById('editProveedorModal'));
            editProveedorModal.hide();
            alert("Proveedor editado correctamente");
        })
        .catch(function(error) {
            console.error('Error editing usuario:', error);
        });
});