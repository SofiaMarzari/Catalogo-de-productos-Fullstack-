USE catalogos;
CREATE TABLE `imagen` (
  `id_imagen` INT NOT NULL AUTO_INCREMENT,
  `path` VARCHAR(250) NOT NULL,
  `id_producto` INT NOT NULL,
  PRIMARY KEY (`id_imagen`),
 FOREIGN KEY (`id_producto`) REFERENCES producto(id_producto)
 );