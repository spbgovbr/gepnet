SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `teste` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `teste` ;

-- -----------------------------------------------------
-- Table `teste`.`role`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `teste`.`role` (
  `role_id` INT NOT NULL AUTO_INCREMENT ,
  `role_name` VARCHAR(100) NULL ,
  PRIMARY KEY (`role_id`) ,
  UNIQUE INDEX `role_id_UNIQUE` (`role_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `teste`.`user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `teste`.`user` (
  `user_id` INT NOT NULL AUTO_INCREMENT ,
  `user_name` VARCHAR(100) NULL ,
  `role_role_id` INT NOT NULL ,
  `use_email` VARCHAR(100) NULL ,
  `user_senha` VARCHAR(45) NULL ,
  `user_fl_ativo` VARCHAR(1) NULL DEFAULT 'N' ,
  PRIMARY KEY (`user_id`) ,
  UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC) ,
  INDEX `fk_user_role` (`role_role_id` ASC) ,
  CONSTRAINT `fk_user_role`
    FOREIGN KEY (`role_role_id` )
    REFERENCES `teste`.`role` (`role_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `teste`.`module`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `teste`.`module` (
  `module_id` INT NOT NULL AUTO_INCREMENT ,
  `module_name` VARCHAR(45) NULL ,
  PRIMARY KEY (`module_id`) ,
  UNIQUE INDEX `module_name_UNIQUE` (`module_name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `teste`.`resource`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `teste`.`resource` (
  `resource_id` INT NOT NULL AUTO_INCREMENT ,
  `resource_name` VARCHAR(100) NULL ,
  `module_id` INT NOT NULL ,
  PRIMARY KEY (`resource_id`, `module_id`) ,
  UNIQUE INDEX `resource_id_UNIQUE` (`resource_id` ASC) ,
  INDEX `fk_resource_module1` (`module_id` ASC) ,
  CONSTRAINT `fk_resource_module1`
    FOREIGN KEY (`module_id` )
    REFERENCES `teste`.`module` (`module_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `teste`.`permission`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `teste`.`permission` (
  `resource_id` INT NOT NULL ,
  `role_id` INT NOT NULL ,
  `permission_name` VARCHAR(100) NULL ,
  PRIMARY KEY (`resource_id`, `role_id`) ,
  INDEX `fk_resource_has_role_resource1` (`resource_id` ASC) ,
  INDEX `fk_resource_has_role_role1` (`role_id` ASC) ,
  CONSTRAINT `fk_resource_has_role_resource1`
    FOREIGN KEY (`resource_id` )
    REFERENCES `teste`.`resource` (`resource_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_resource_has_role_role1`
    FOREIGN KEY (`role_id` )
    REFERENCES `teste`.`role` (`role_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
