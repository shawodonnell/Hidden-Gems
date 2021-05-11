<?php
 
 include("../config/connection.php");

//create users table with all the fields
$conn->query('
CREATE TABLE `sodonnell26`.`CSC7062_Users` 
(
  `id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
PRIMARY KEY (`id`) 
);') or die($conn->error);


 
?>
