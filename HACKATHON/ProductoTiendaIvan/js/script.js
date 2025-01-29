// Seleccionar elementos del DOM
const perfilButton = document.getElementById('perfilButton');
const modalOverlay = document.getElementById('modalOverlay');
const closeModalButton = document.getElementById('closeModalButton');

// Mostrar el modal al hacer clic en el botón "Perfil"
perfilButton.addEventListener('click', () => {
    modalOverlay.style.display = 'block';
});

// Cerrar el modal al hacer clic en el botón de cerrar
closeModalButton.addEventListener('click', () => {
    modalOverlay.style.display = 'none';
});

// Cerrar el modal si se hace clic fuera del contenido
modalOverlay.addEventListener('click', (event) => {
    if (event.target === modalOverlay) {
        modalOverlay.style.display = 'none';
    }
});

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

function showSection(sectionId) {
    // Ocultar todas las secciones
    const sections = document.querySelectorAll('.section-content');
    sections.forEach(section => section.classList.remove('active'));
  
    // Mostrar la sección seleccionada
    document.getElementById(sectionId).classList.add('active');
}

function sendRecoveryEmail() {
    const message = document.getElementById('recovery-message');
    message.classList.remove('hidden');
  
    // Simula una notificación que desaparece después de 3 segundos
    setTimeout(() => {
      message.classList.add('hidden');
    }, 3000);
}

function sendMessage() {
    const input = document.getElementById("message-input");
    const message = input.value.trim();

    if (message) {
    const chatMessages = document.getElementById("chat-messages");

    // Crear el mensaje saliente
    const outgoingMessage = document.createElement("div");
    outgoingMessage.classList.add("outgoing");

    const bubble = document.createElement("div");
    bubble.classList.add("bubble");
    bubble.textContent = message;

    outgoingMessage.appendChild(bubble);
    chatMessages.appendChild(outgoingMessage);

    // Limpiar el input
    input.value = "";

    // Desplazar hacia abajo automáticamente
    chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

//AProducto

const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('product-image');
const previewContainer = document.getElementById('preview-container');

// Manejo de eventos de la zona de carga
const manejarEventosZona = (e) => {
    e.preventDefault();
    e.type === 'dragover' ? dropZone.classList.add('dragover') : dropZone.classList.remove('dragover');
};

// Mostrar vista previa de múltiples imágenes
const mostrarVistaPrevia = (files) => {
    previewContainer.innerHTML = ''; // Limpiar las vistas previas actuales
    Array.from(files).forEach(file => {
        if (file.type.startsWith('image/') && file.size <= 2 * 1024 * 1024) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else {
            alert(`"${file.name}" no es válido. Asegúrate de que sea una imagen y no exceda 2 MB.`);
        }
    });
};

// Eventos de la zona de carga
dropZone.addEventListener('click', () => fileInput.click());
dropZone.addEventListener('dragover', manejarEventosZona);
dropZone.addEventListener('dragleave', manejarEventosZona);
dropZone.addEventListener('drop', (e) => {
    manejarEventosZona(e);
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files; // Asignar archivos al input
        mostrarVistaPrevia(files);
    }
});

// Evento del input de archivo
fileInput.addEventListener('change', () => {
    if (fileInput.files.length > 0) {
        mostrarVistaPrevia(fileInput.files);
    }
});

setTimeout(function() {
    var alert = document.getElementById('alert');
    if (alert) {
        alert.style.transition = "opacity 0.5s";
        alert.style.opacity = "0";
        setTimeout(() => alert.remove(), 500);
    }
}, 5000);

    function toggleAccordion(header) {
        const content = header.nextElementSibling; // Obtén el contenido del acordeón
        const icon = header.querySelector(".toggle-icon"); // Obtén el icono del acordeón

        if (content.style.display === "block") {
            content.style.display = "none"; // Oculta el contenido
            icon.textContent = "+"; // Cambia el icono
        } else {
            content.style.display = "block"; // Muestra el contenido
            icon.textContent = "−"; // Cambia el icono
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        const chatList = document.querySelector(".chat-list")
        const chatWindow = document.querySelector(".chat-window")
        const backButton = document.querySelector(".back-button")
        const chatItems = document.querySelectorAll(".chat-item")
      
        chatItems.forEach((item) => {
          item.addEventListener("click", () => {
            const chatId = item.getAttribute("data-chat-id")
            openChat(chatId)
          })
        })
      
        backButton.addEventListener("click", closeChat)
      
        function openChat(chatId) {
          // Here you would typically load the chat messages for the selected chat
          // For this example, we'll just show a placeholder message
          const chatMessages = document.querySelector(".chat-messages")
          chatMessages.innerHTML = `<p>Chat messages for chat ${chatId}</p>`
      
          // Update chat header
          const chatHeader = document.querySelector(".chat-header h2")
          chatHeader.textContent = `Chat ${chatId}`
      
          // Show the chat window with a slide animation
          chatWindow.classList.add("active")
      
          // On mobile, hide the chat list
          if (window.innerWidth <= 600) {
            chatList.style.display = "none"
          }
        }
      
        function closeChat() {
          // Hide the chat window with a slide animation
          chatWindow.classList.remove("active")
      
          // On mobile, show the chat list
          if (window.innerWidth <= 600) {
            chatList.style.display = "block"
          }
        }
      
        // Handle window resize
        window.addEventListener("resize", () => {
          if (window.innerWidth > 600) {
            chatList.style.display = "block"
          }
        })
      })
      
      