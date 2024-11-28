// Esperar a que el DOM se cargue completamente
document.addEventListener('DOMContentLoaded', function() {
  // Seleccionar el ícono de menú y el menú desplegable
  const iconMenu = document.querySelector('.icon-menu');
  const menu = document.querySelector('.menu');

  // Verificar si ambos elementos existen
  if (iconMenu && menu) {
    // Agregar el evento de clic al ícono del menú
    iconMenu.addEventListener('click', function() {
      // Alternar la clase 'open' en el menú
      menu.classList.toggle('open');
    });
  } else {
    console.log("Error: no se encontraron los elementos del menú o el ícono.");
  }
});

