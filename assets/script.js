// Simulamos un stock de productos (en una aplicación real esto vendría de la base de datos)
const stockData = [
    { id: 1, name: 'Producto A', stock: 10 },
    { id: 2, name: 'Producto B', stock: 5 },
    { id: 3, name: 'Producto C', stock: 0 }
];

// Función para llenar la tabla con los productos
function loadStock() {
    const tableBody = document.querySelector('#stock-table tbody');
    tableBody.innerHTML = '';  // Limpiamos la tabla antes de cargar los datos

    stockData.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.name}</td>
            <td>${product.stock}</td>
            <td><button onclick="requestProduct(${product.id})" ${product.stock <= 0 ? 'disabled' : ''}>Solicitar</button></td>
        `;
        tableBody.appendChild(row);
    });
}

// Función para solicitar un producto
function requestProduct(productId) {
    const product = stockData.find(p => p.id === productId);
    if (product && product.stock > 0) {
        const cantidad = 1;  // Se solicita una unidad por ahora

        // Enviar petición al servidor
        fetch('stock.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `producto_id=${productId}&cantidad=${cantidad}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);  // Muestra el mensaje del servidor
            loadStock();  // Volver a cargar el stock
        })
        .catch(error => {
            alert('Hubo un error: ' + error);
        });
    }
}


window.onload = loadStock;
