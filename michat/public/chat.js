const socket = io();

const messages = document.getElementById('messages');
const messageInput = document.getElementById('message-input');
const sendButton = document.getElementById('send-button');

sendButton.addEventListener('click', () => {
    const message = messageInput.value;
    socket.emit('chat message', { message: message });  // Solo enviamos el mensaje, el servidor ya maneja el userType
    messageInput.value = '';
});

socket.on('chat message', (data) => {
    const messageElement = document.createElement('div');
    messageElement.textContent = `${data.userType === 'user' ? 'Usuario' : 'Proveedor'} ${data.userId} (${data.userName}): ${data.message}`;
    messages.appendChild(messageElement);
});
