SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `wsest_person`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wsest_person` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `ezuser_id` INT(11) NOT NULL ,
  `last_update` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE INDEX `ezuser_id` ON `wsest_person` (`ezuser_id` ASC) ;


-- -----------------------------------------------------
-- Table `wsest_status`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wsest_status` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `person_id` INT(11) NOT NULL ,
  `receiver_id` INT(11) NULL ,
  `via_id` INT(11) NULL DEFAULT NULL ,
  `discussion_id` INT(11) NULL ,
  `status` SMALLINT(6) NOT NULL ,
  `privacy` SMALLINT NOT NULL ,
  `date` INT(11) NOT NULL ,
  `message` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `author`
    FOREIGN KEY (`person_id` )
    REFERENCES `wsest_person` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `via`
    FOREIGN KEY (`via_id` )
    REFERENCES `wsest_status` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `discussion`
    FOREIGN KEY (`discussion_id` )
    REFERENCES `wsest_status` (`id` )
    ON DELETE SET NULL
    ON UPDATE NO ACTION,
  CONSTRAINT `receiver`
    FOREIGN KEY (`receiver_id` )
    REFERENCES `wsest_person` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `date` ON `wsest_status` (`date` ASC) ;

CREATE INDEX `author` ON `wsest_status` (`person_id` ASC) ;

CREATE INDEX `via` ON `wsest_status` (`via_id` ASC) ;

CREATE INDEX `discussion` ON `wsest_status` (`discussion_id` ASC) ;

CREATE INDEX `receiver` ON `wsest_status` (`receiver_id` ASC) ;


-- -----------------------------------------------------
-- Table `wsest_list`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wsest_list` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `subject` VARCHAR(20) NOT NULL ,
  `description` TEXT NULL ,
  `privacy` SMALLINT NULL ,
  `owner_id` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `owner`
    FOREIGN KEY (`owner_id` )
    REFERENCES `wsest_person` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `owner` ON `wsest_list` (`owner_id` ASC) ;


-- -----------------------------------------------------
-- Table `wsest_followers`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wsest_followers` (
  `follower_id` INT(11) NOT NULL ,
  `leader_id` INT(11) NOT NULL ,
  PRIMARY KEY (`follower_id`, `leader_id`) ,
  CONSTRAINT `follower`
    FOREIGN KEY (`follower_id` )
    REFERENCES `wsest_person` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `followed`
    FOREIGN KEY (`leader_id` )
    REFERENCES `wsest_person` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `follower` ON `wsest_followers` (`follower_id` ASC) ;

CREATE INDEX `followed` ON `wsest_followers` (`leader_id` ASC) ;


-- -----------------------------------------------------
-- Table `wsest_favorite`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wsest_favorite` (
  `status_id` INT(11) NOT NULL ,
  `person_id` INT(11) NOT NULL ,
  PRIMARY KEY (`status_id`, `person_id`) ,
  CONSTRAINT `fk_status`
    FOREIGN KEY (`status_id` )
    REFERENCES `wsest_status` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_person`
    FOREIGN KEY (`person_id` )
    REFERENCES `wsest_person` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `index_status` ON `wsest_favorite` (`status_id` ASC) ;

CREATE INDEX `index_person` ON `wsest_favorite` (`person_id` ASC) ;


-- -----------------------------------------------------
-- Table `wsest_list_item`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wsest_list_item` (
  `list_id` INT(11) NOT NULL ,
  `person_id` INT(11) NOT NULL ,
  PRIMARY KEY (`list_id`, `person_id`) ,
  CONSTRAINT `list`
    FOREIGN KEY (`list_id` )
    REFERENCES `wsest_list` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `person`
    FOREIGN KEY (`person_id` )
    REFERENCES `wsest_person` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `person` ON `wsest_list_item` (`person_id` ASC) ;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
