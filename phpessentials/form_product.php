<?php
// form_product.php
?>

<form action="insert_product.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="product-name">Nombre del producto:</label>
        <input type="text" name="product-name" class="form-control" id="product-name" required>
    </div>
    
    <div class="form-group">
        <label for="product-image">Imágenes del producto:</label>
        <input type="file" name="product-image[]" class="form-control" id="product-image" multiple required>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Agregar Producto</button>
</form>
