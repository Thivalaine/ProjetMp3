<?php
	function connexionBdd() {
		require('config.php');
		try{
			$co = new PDO("mysql:host=" . $server .";dbname=" . $dbName, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",));
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e){
			die('Erreur : ' . $e->getMessage());
		}
        return $co;
	}
?>
