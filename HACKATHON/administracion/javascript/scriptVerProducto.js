let currentIndex = 0;

function showSlide(index) {
    const slides = document.querySelectorAll('.carousel-item');
    const totalSlides = slides.length;
    const indicators = document.querySelectorAll('.carousel-indicators span');

    // Asegurarse de que el índice esté dentro de los límites
    if (index >= totalSlides) {
        currentIndex = 0;
    } else if (index < 0) {
        currentIndex = totalSlides - 1;
    } else {
        currentIndex = index;
    }

    // Cambiar la posición del carrusel
    const carouselInner = document.querySelector('.carousel-inner');
    carouselInner.style.transform = `translateX(-${currentIndex * 100}%)`;

    // Actualizar los puntos de navegación
    updateIndicators(indicators);
}

// Función para mover las diapositivas
function moveSlide(direction) {
    showSlide(currentIndex + direction);
}

// Función para actualizar los indicadores (puntos de navegación)
function updateIndicators(indicators) {
    indicators.forEach((indicator, index) => {
        if (index === currentIndex) {
            indicator.classList.add('active');
            indicator.setAttribute('data-number', index + 1);  // Asignar el número al indicador
        } else {
            indicator.classList.remove('active');
            indicator.removeAttribute('data-number');  // Limpiar el número
        }
    });
}

// Inicializar el carrusel
document.addEventListener('DOMContentLoaded', () => {
    const totalSlides = document.querySelectorAll('.carousel-item').length;

    // Crear puntos de navegación dinámicamente según el número de imágenes
    const indicatorsContainer = document.querySelector('.carousel-indicators');
    for (let i = 0; i < totalSlides; i++) {
        const indicator = document.createElement('span');
        indicatorsContainer.appendChild(indicator);
    }

    // Mostrar la primera diapositiva
    showSlide(currentIndex);
});
