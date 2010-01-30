SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `proyecto_contabilidad` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `proyecto_contabilidad`;

-- -----------------------------------------------------
-- Table `proyecto_contabilidad`.`LIBRO_DIARIO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto_contabilidad`.`LIBRO_DIARIO` ;

CREATE  TABLE IF NOT EXISTS `proyecto_contabilidad`.`LIBRO_DIARIO` (
  `id_ldiario` INT NOT NULL AUTO_INCREMENT ,
  `fecha_ldiario` DATE NOT NULL ,
  PRIMARY KEY (`id_ldiario`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proyecto_contabilidad`.`CUENTA`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto_contabilidad`.`CUENTA` ;

CREATE  TABLE IF NOT EXISTS `proyecto_contabilidad`.`CUENTA` (
  `id_cuenta` INT NOT NULL AUTO_INCREMENT ,
  `nombre_cuenta` VARCHAR(45) NOT NULL ,
  `tipo_cuenta` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id_cuenta`) )
ENGINE = InnoDB;

INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES 	('1','Banco','Activo');
INSERT INTO cuenta (id_cuenta,nombre_cuenta,tipo_cuenta) VALUES 	('2','Capital','Pasivo');

-- -----------------------------------------------------
-- Table `proyecto_contabilidad`.`MOVIMIENTO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto_contabilidad`.`MOVIMIENTO` ;

CREATE  TABLE IF NOT EXISTS `proyecto_contabilidad`.`MOVIMIENTO` (
  `id_cuenta_movimiento` INT NOT NULL ,
  `id_ldiario_movimiento` INT DEFAULT NULL,
  `monto_movimiento` INT NOT NULL ,
  `columna_movimiento` VARCHAR(45) NOT NULL ,
  CONSTRAINT `fk_MOVIMIENTO_CUENTA`
    FOREIGN KEY (`id_cuenta_movimiento` )
    REFERENCES `proyecto_contabilidad`.`CUENTA` (`id_cuenta` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_MOVIMIENTO_LIBRO_DIARIO`
    FOREIGN KEY (`id_ldiario_movimiento` )
    REFERENCES `proyecto_contabilidad`.`LIBRO_DIARIO` (`id_ldiario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_MOVIMIENTO_CUENTA` ON `proyecto_contabilidad`.`MOVIMIENTO` (`id_cuenta_movimiento` ASC) ;

CREATE INDEX `fk_MOVIMIENTO_LIBRO_DIARIO` ON `proyecto_contabilidad`.`MOVIMIENTO` (`id_ldiario_movimiento` ASC) ;


-- -----------------------------------------------------
-- Table `proyecto_contabilidad`.`PRODUCTO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto_contabilidad`.`PRODUCTO` ;

CREATE  TABLE IF NOT EXISTS `proyecto_contabilidad`.`PRODUCTO` (
  `id_producto` INT NOT NULL AUTO_INCREMENT ,
  `nombre_producto` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id_producto`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proyecto_contabilidad`.`FICHA_INVENTARIO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto_contabilidad`.`FICHA_INVENTARIO` ;

CREATE  TABLE IF NOT EXISTS `proyecto_contabilidad`.`FICHA_INVENTARIO` (
  `id_finventario` INT NOT NULL AUTO_INCREMENT ,
  `descripcion` VARCHAR(45) NOT NULL ,
  `fecha_inventario` DATE NOT NULL ,
  `id_producto_finventario` INT NOT NULL ,
  PRIMARY KEY (`id_finventario`, `id_producto_finventario`) ,
  CONSTRAINT `fk_FICHA_INVENTARIO_PRODUCTO`
    FOREIGN KEY (`id_producto_finventario` )
    REFERENCES `proyecto_contabilidad`.`PRODUCTO` (`id_producto` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_FICHA_INVENTARIO_PRODUCTO` ON `proyecto_contabilidad`.`FICHA_INVENTARIO` (`id_producto_finventario` ASC) ;


-- -----------------------------------------------------
-- Table `proyecto_contabilidad`.`TRANSACCION`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto_contabilidad`.`TRANSACCION` ;

CREATE  TABLE IF NOT EXISTS `proyecto_contabilidad`.`TRANSACCION` (
  `id_transaccion` INT NOT NULL AUTO_INCREMENT ,
  `id_finventario_transaccion` INT NOT NULL ,
  `tipo_transaccion` VARCHAR(45) NOT NULL ,
  `unidades_transaccion` INT NOT NULL ,
  `total_transaccion` INT NOT NULL ,
  `precio_unidad` FLOAT NOT NULL ,
  PRIMARY KEY (`id_transaccion`),
  CONSTRAINT `fk_FICHA_INVENTARIO_TRANSACCION`
    FOREIGN KEY (`id_finventario_transaccion` )
    REFERENCES `proyecto_contabilidad`.`FICHA_INVENTARIO` (`id_finventario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_FICHA_INVENTARIO_TRANSACCION` ON `proyecto_contabilidad`.`TRANSACCION`  (`id_finventario_transaccion` ASC) ;

-- -----------------------------------------------------
-- Table `proyecto_contabilidad`.`GASTO_ASOCIADO`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto_contabilidad`.`GASTO_ASOCIADO` ;

CREATE  TABLE IF NOT EXISTS `proyecto_contabilidad`.`GASTO_ASOCIADO` (
  `id_gasto_asociado` INT NOT NULL AUTO_INCREMENT ,
  `monto_gasto` INT NOT NULL ,
  `pagos_restantes` INT NOT NULL ,
  PRIMARY KEY (`id_gasto_asociado`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `proyecto_contabilidad`.`GASTO_CUENTA`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `proyecto_contabilidad`.`GASTO_CUENTA` ;

CREATE  TABLE IF NOT EXISTS `proyecto_contabilidad`.`GASTO_CUENTA` (
  `columna_gasto` VARCHAR(45) NOT NULL ,
  `id_cuenta_gasto` INT NULL ,
  `id_gasto_asociado` INT NULL ,
  CONSTRAINT `fk_GASTO_CUENTA_CUENTA`
    FOREIGN KEY (`id_cuenta_gasto` )
    REFERENCES `proyecto_contabilidad`.`CUENTA` (`id_cuenta` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_GASTO_CUENTA_GASTO_ASOCIADO`
    FOREIGN KEY (`id_gasto_asociado` )
    REFERENCES `proyecto_contabilidad`.`GASTO_ASOCIADO` (`id_gasto_asociado` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_GASTO_CUENTA_CUENTA` ON `proyecto_contabilidad`.`GASTO_CUENTA` (`id_cuenta_gasto` ASC) ;

CREATE INDEX `fk_GASTO_CUENTA_GASTO_ASOCIADO` ON `proyecto_contabilidad`.`GASTO_CUENTA` (`id_gasto_asociado` ASC) ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
