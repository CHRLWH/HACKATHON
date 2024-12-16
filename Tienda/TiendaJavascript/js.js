const products = [
    { id: 1, name: "Handmade Ceramic Mug", category: "home", price: "$30", image: "assets/mug.jpg" },
    { id: 2, name: "Handmade Gold Hoop Earrings", category: "jewelry", price: "$51", image: "assets/earrings.jpg" },
  ];
  
  function renderProducts(filter) {
    const grid = document.getElementById("product-grid");
    grid.innerHTML = ""; // Clear the grid
    products
      .filter((product) => filter === "all" || product.category === filter)
      .forEach((product) => {
        grid.innerHTML += `
          <div class="col-md-4">
            <div class="card h-100">
              <img src="${product.image}" class="card-img-top" alt="${product.name}">
              <div class="card-body">
                <h5 class="card-title">${product.name}</h5>
                <p class="card-text">${product.price}</p>
                <button class="btn btn-warning">Request Chat</button>
              </div>
            </div>
          </div>
        `;
      });
  }
  
  function filterProducts(filter) {
    renderProducts(filter);
  }
  
  // Initial render
  document.addEventListener("DOMContentLoaded", () => renderProducts("all"));
  