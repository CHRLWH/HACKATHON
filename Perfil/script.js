const gallery = [];

// Mostrar modal
document.getElementById("edit-profile-btn").addEventListener("click", () => {
    document.getElementById("edit-modal").classList.add("visible");
});

// Ocultar modal
document.getElementById("close-modal").addEventListener("click", () => {
    document.getElementById("edit-modal").classList.remove("visible");
});

// Guardar cambios
document.getElementById("save-btn").addEventListener("click", () => {
    // Actualizar nombre, título y descripción
    const name = document.getElementById("name").value;
    const title = document.getElementById("title").value;
    const bio = document.getElementById("bio").value;
    const photo = document.getElementById("photo").value;

    document.getElementById("user-name").textContent = name;
    document.getElementById("user-title").textContent = title;
    document.getElementById("user-bio").textContent = bio;
    document.getElementById("profile-picture").src = photo;

    // Añadir imagen a la galería
    const galleryItem = document.getElementById("gallery-item").value;
    if (galleryItem) {
        gallery.push(galleryItem);
        renderGallery();
        document.getElementById("gallery-item").value = ""; // Limpiar campo
    }

    // Ocultar modal
    document.getElementById("edit-modal").classList.remove("visible");
});

// Renderizar galería
function renderGallery() {
    const galleryGrid = document.getElementById("gallery-grid");
    galleryGrid.innerHTML = "";
    gallery.forEach(item => {
        const img = document.createElement("img");
        img.src = item;
        galleryGrid.appendChild(img);
    });
}

// Inicializar galería vacía
renderGallery();
