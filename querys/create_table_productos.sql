USE 'catalogo';
CREATE TABLE `catalogo`.`productos` (
  `id_producto` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `descripcion` VARCHAR(250) NULL,
  `imagen` VARCHAR(150) NULL,
  `cantidad` INT NULL DEFAULT 0,
  PRIMARY KEY (`id_producto`));