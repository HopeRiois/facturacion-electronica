<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Punto de Pago</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Punto de pago</h2>

    <section class="cliente">
        <h3>Datos de cliente</h3>
        <label>Identificacion: <input type="text" id="identificacion"></label>
        <label>Nombre: <input type="text" id="nombre"></label>
        <label>Dirección: <input type="text" id="direccion"></label>
        <label>Teléfono: <input type="text" id="telefono"></label>
        <label>Correo: <input type="email" id="correo"></label>
        <button onclick="limpiarCliente()">Limpiar factura</button>
    </section>

    <section class="factura">
        <label>Factura N°: <input type="text" id="factura" value="00000001"></label>
        <label>Fecha: <input type="date" id="fecha" value="<?= date('Y-m-d') ?>"></label>
    </section>

    <section class="tabla">
        <table id="detalle">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Precio total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="body-productos"></tbody>
        </table>
        <input type="text" id="codigo" placeholder="Código">
        <input type="text" id="descripcion" placeholder="Descripción">
        <input type="number" id="cantidad" placeholder="Cantidad" min="1">
        <input type="number" id="precio" placeholder="Precio" min="0" step="0.01">
        <button onclick="agregarItem()">+</button>
    </section>

    <section class="totales">
        <p>Subtotal: $<span id="subtotal">0.00</span></p>
        <p>Impuesto (19%): $<span id="impuesto">0.00</span></p>
        <p>Total: $<span id="total">0.00</span></p>
    </section>

    <button onclick="enviarFactura()">Enviar</button>

    <hr>

    <h3>Vista previa XML de factura electrónica</h3>
    <pre id="xmlPreview" style="background:#f0f0f0;padding:10px;border:1px solid #ccc;"></pre>

    <button onclick="descargarXML()">Descargar XML</button>

    <script src="js/app.js"></script>
</body>
</html>
