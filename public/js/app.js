let items = [];
let estadoPago = false;

function agregarItem() {
    const idProducto = document.getElementById("idProducto").value;
    const codigo = document.getElementById("codigo").value;
    const descripcion = document.getElementById("descripcion").value;
    const cantidad = parseFloat(document.getElementById("cantidad").value);
    const precio = parseFloat(document.getElementById("precio").value);

    if (!codigo || !descripcion || isNaN(cantidad) || isNaN(precio)) return alert("Completa todos los campos");

    const total = cantidad * precio;
    items.push({ idProducto, codigo, descripcion, cantidad, precio, total });

    renderItems();
    calcularTotales();

    // limpiar inputs
    document.getElementById("codigo").value = "";
    document.getElementById("descripcion").value = "";
    document.getElementById("cantidad").value = "";
    document.getElementById("precio").value = "";
}

function renderItems() {
    const tbody = document.getElementById("listaItems");
    tbody.innerHTML = "";

    items.forEach((item, index) => {
        const row = `
            <tr>
                <td>${item.codigo}</td>
                <td>${item.descripcion}</td>
                <td>${item.cantidad}</td>
                <td>${item.precio.toFixed(2)}</td>
                <td>${item.total.toFixed(2)}</td>
                <td><button onclick="eliminarItem(${index})">X</button></td>
            </tr>`;
        tbody.innerHTML += row;
    });

    actualizarXML();
}

function eliminarItem(index) {
    items.splice(index, 1);
    renderItems();
    calcularTotales();
}

function calcularTotales() {
    let subtotal = items.reduce((sum, item) => sum + item.total, 0);
    let impuesto = subtotal * 0.19;
    let total = subtotal + impuesto;

    document.getElementById("subtotal").textContent = subtotal.toFixed(2);
    document.getElementById("impuesto").textContent = impuesto.toFixed(2);
    document.getElementById("total").textContent = total.toFixed(2);

    actualizarXML();
}

function limpiarCliente() {
    document.getElementById("identificacion").value = "";
    document.getElementById("nombre").value = "";
    document.getElementById("direccion").value = "";
    document.getElementById("telefono").value = "";
    document.getElementById("correo").value = "";
    items = [];
    renderItems();
    calcularTotales();
    actualizarXML();
}

function enviarFactura() {
    const factura = {
        idCliente: 0,
        identificacion: document.getElementById("identificacion").value,
        nombre: document.getElementById("nombre").value,
        direccion: document.getElementById("direccion").value,
        telefono: document.getElementById("telefono").value,
        correo: document.getElementById("correo").value,
        factura: document.getElementById("factura").value,
        fecha: document.getElementById("fecha").value,
        subtotal:document.getElementById("subtotal").value,
        impuesto: document.getElementById("impuesto").value,
        total:document.getElementById("total").value,
        items: items
    };

    const cliente = {
        identificacion: document.getElementById("identificacion").value,
        nombre: document.getElementById("nombre").value,
        direccion: document.getElementById("direccion").value,
        telefono: document.getElementById("telefono").value,
        correo: document.getElementById("correo").value
    };

    fetch("../models/Cliente.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(cliente)
    }).then(res => res.json())
            .then(async data => {
            factura.idCliente = data.cliente.id;
            console.log(factura);
            
            try {
                    const resp = await fetch("../Save_invoice.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify(factura)
                    });
                    resp.text();
                    estadoPago = true;
                    document.getElementById("xmlPreview").textContent = generarXML(factura);
                    inhabilitarCamposFactura();
                    alert("Factura guardada");
                } catch (er) {
                    return alert("Error al guardar factura");
                }
                    })
      .catch(err => alert("Error al administrar cliente"));
               
}

function generarXML(factura){
    const subtotal = factura.items.reduce((sum, item) => sum + item.total, 0);
    const impuesto = subtotal * 0.19;
    const total = subtotal + impuesto;

    let xml = `<FacturaElectronica>\n`;
    xml += `  <Identificacion>${factura.identificacion}</Identificacion>\n`;
    xml += `  <Nombre>${factura.nombre}</Nombre>\n`;
    xml += `  <Direccion>${factura.direccion}</Direccion>\n`;
    xml += `  <Telefono>${factura.telefono}</Telefono>\n`;
    xml += `  <Correo>${factura.correo}</Correo>\n`;
    xml += `  <NumeroFactura>${factura.numeroFactura}</NumeroFactura>\n`;
    xml += `  <Fecha>${factura.fecha}</Fecha>\n`;
    xml += `  <Items>\n`;

    factura.items.forEach(item => {
        xml += `    <Item>\n`;
        xml += `      <Codigo>${item.codigo}</Codigo>\n`;
        xml += `      <Descripcion>${item.descripcion}</Descripcion>\n`;
        xml += `      <Cantidad>${item.cantidad}</Cantidad>\n`;
        xml += `      <Precio>${item.precio.toFixed(2)}</Precio>\n`;
        xml += `      <Total>${item.total.toFixed(2)}</Total>\n`;
        xml += `    </Item>\n`;
    });

    xml += `  </Items>\n`;

    xml += `  <Subtotal>${subtotal.toFixed(2)}</Subtotal>\n`;
    xml += `  <Impuesto valor="19">${impuesto.toFixed(2)}</Impuesto>\n`;
    xml += `  <Total>${total.toFixed(2)}</Total>\n`;
    xml += `  <Pagado>${estadoPago}</Pagado>\n`;  // ← campo agregado

    xml += `</FacturaElectronica>`;

    return xml;
}

function actualizarXML(){
    const factura = {
        identificacion: document.getElementById("identificacion").value,
        nombre: document.getElementById("nombre").value,
        direccion: document.getElementById("direccion").value,
        telefono: document.getElementById("telefono").value,
        correo: document.getElementById("correo").value,
        numeroFactura: document.getElementById("factura").value,
        fecha: document.getElementById("fecha").value,
        items: items
    };

    document.getElementById("xmlPreview").textContent = generarXML(factura);
}

function descargarXML() {
    const factura = {
        identificacion: document.getElementById("identificacion").value,
        nombre: document.getElementById("nombre").value,
        direccion: document.getElementById("direccion").value,
        telefono: document.getElementById("telefono").value,
        correo: document.getElementById("correo").value,
        numeroFactura: document.getElementById("factura").value,
        fecha: document.getElementById("fecha").value,
        items: items
    };

    const xmlContent = generarXML(factura);
    const blob = new Blob([xmlContent], { type: "application/xml" });
    const url = URL.createObjectURL(blob);

    const link = document.createElement("a");
    link.href = url;
    link.download = `factura_${factura.numeroFactura}.xml`;
    link.click();

    URL.revokeObjectURL(url);
}

function inhabilitarCamposFactura() {
    // Deshabilita todos los inputs y textareas del documento
    document.querySelectorAll("input, select, textarea, button").forEach(el => {
        if (el.id !== "descargarXML" && el.id !== "limpiarFactura") {
            el.disabled = true;
        }
    });
}

document.getElementById("identificacion").addEventListener("blur", () => {
    const id = document.getElementById("identificacion").value.trim();
    if (id === "") return;

    fetch(`../models/Cliente.php?identificacion=${encodeURIComponent(id)}`)
        .then(res => res.json())
        .then(cliente => {
            if (cliente) {
                document.getElementById("nombre").value = cliente.nombres || "";
                document.getElementById("direccion").value = cliente.direccion || "";
                document.getElementById("telefono").value = cliente.telefono || "";
                document.getElementById("correo").value = cliente.correo || "";

                // Inhabilita los campos
                document.getElementById("nombre").disabled = true;
                document.getElementById("direccion").disabled = true;
                document.getElementById("telefono").disabled = true;
                document.getElementById("correo").disabled = true;
            } else {
                // Cliente no encontrado: limpia y habilita
                document.getElementById("nombre").value = "";
                document.getElementById("direccion").value = "";
                document.getElementById("telefono").value = "";
                document.getElementById("correo").value = "";

                document.getElementById("nombre").disabled = false;
                document.getElementById("direccion").disabled = false;
                document.getElementById("telefono").disabled = false;
                document.getElementById("correo").disabled = false;
            }
            actualizarXML();
        })
        .catch(err => console.error("Error al buscar cliente:", err));
});


document.getElementById("codigo").addEventListener("blur", () => {
    const codigo = document.getElementById("codigo").value.trim();
    if (codigo === "") return;

    if (codigo.length === 0) return;

    fetch("../models/Producto.php?codigo=" + encodeURIComponent(codigo))
      .then(res => res.json())
      .then(producto => {

        if (producto) {
            document.getElementById("idProducto").value = producto.id || "";
            document.getElementById("descripcion").value = producto.descripcion || "";
            document.getElementById("precio").value = producto.precio || "";
        } else {
            alert("Producto no encontrado.");
        }

        actualizarXML(); // Si estás actualizando XML con los productos
      })
      .catch(err => console.error("Error al buscar producto:", err));
});

document.addEventListener("DOMContentLoaded", () => {
    fetch("../Codigo_Factura.php")
        .then(res => res.json())
        .then(data => {
            if (data.numeroFactura) {
                document.getElementById("factura").value = data.numeroFactura;
            } else {
                alert("No se pudo obtener el número de factura");
            }
        })
        .catch(err => {
            console.error("Error al obtener número de factura:", err);
            alert("Error al cargar número de factura");
        });
});

["identificacion", "nombre", "direccion", "telefono", "correo", "factura", "fecha"].forEach(id => {
    document.getElementById(id).addEventListener("input", actualizarXML);
});