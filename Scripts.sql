CREATE schema facturacion;

use facturacion;

CREATE TABLE IF NOT EXISTS `Cliente` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `identificacion` VARCHAR(20) NOT NULL UNIQUE,
  `nombres` VARCHAR(255) NULL,
  `direccion` VARCHAR(255) NULL,
  `telefono` VARCHAR(20) NULL,
  `correo` VARCHAR(255) NULL,
  PRIMARY KEY (`id`));
  
CREATE TABLE IF NOT EXISTS `Producto` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(255) NULL,
  `descripcion` VARCHAR(500) NULL,
  `precio` FLOAT NULL,
  PRIMARY KEY (`id`));
  
  CREATE TABLE IF NOT EXISTS `Venta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `numero_factura` VARCHAR(45) NULL,
  `subtotal` FLOAT NULL,
  `impuestos` FLOAT NULL,
  `total` FLOAT NULL,
  `fecha_compra` DATETIME NULL,
  `cliente_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_ClienteId` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`));
  
CREATE TABLE IF NOT EXISTS `Venta_has_Producto` (
  `Venta_id` INT NOT NULL,
  `Producto_id` INT NOT NULL,
  PRIMARY KEY (`Venta_id`, `Producto_id`),
  INDEX `fk_Venta_has_Producto_Producto1_idx` (`Producto_id` ASC) VISIBLE,
  INDEX `fk_Venta_has_Producto_Venta1_idx` (`Venta_id` ASC) VISIBLE,
  CONSTRAINT `fk_Venta_has_Producto_Venta1`
    FOREIGN KEY (`Venta_id`)
    REFERENCES `Venta` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Venta_has_Producto_Producto1`
    FOREIGN KEY (`Producto_id`)
    REFERENCES `Producto` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
    
    
INSERT INTO clienproductote values (null, '1003314827', 'Alvaro Rios', "Calle 1#2-3", '300000000', 'alvaro.rios@correo.co');

INSERT INTO Producto (codigo, descripcion, precio) VALUES
('AA01', 'Papa', 1580.00),
('AA02', 'Yuca', 2940.50),
('AA03', 'Ñame', 4720.75),
('AA04', 'Magi', 1070.20),
('AA05', 'Guineo', 3125.00),
('AA06', 'Cebolla', 2235.40),
('AA07', 'Aguacate', 4810.00),
('AA08', 'Tomate', 3500.99),
('AA09', 'Fijol', 4100.60),
('AA10', 'Arroz', 1999.90),
('AA11', 'Azucar', 1085.35),
('AA12', 'Sal', 2795.00),
('AA13', 'Lentejas', 3750.45),
('AA14', 'Cebollin', 1435.80),
('AA15', 'Cilantro', 1800.00),
('AA16', 'Limon', 4200.99),
('AA17', 'Guayaba', 3300.00),
('AA18', 'Tomate arbol', 2650.00),
('AA19', 'Corozo', 1710.50),
('AA20', 'Mora', 2199.99),
('AA21', 'Melon', 3600.70),
('AA22', 'Patilla', 3900.80),
('AA23', 'Guanabana', 1099.90),
('AA24', 'Ajo', 1575.00),
('AA25', 'Comino', 2450.60),
('AA26', 'Oregano', 3375.75),
('AA27', 'Lechuga', 1325.00),
('AA28', 'Piña', 1850.90),
('AA29', 'Queso 4 onz', 3110.00),
('AA30', 'Murlo', 4299.99);
