<?php

	class DatabaseHelper{

		private static $servername = 'localhost';
		private static $username = 'root';
		private static $password = '';
		private static $dbname = 'chatter';

		public static $conn;

		public static function init(){
			/**
			 * makes connection if conn is null
			 * @param None
			 * @return None			 
			 */
			if (self::$conn == null){							
				try{
					self::$conn = new PDO("mysql:host=".self::$servername.";dbname=".self::$dbname.";", self::$username, self::$password);
					self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);					
				}catch (Exception $ex){
					echo $ex;
				}				
			}
		}		
	}
