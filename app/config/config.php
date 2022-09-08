<?php

class config 
{
    static public function conn()
    {
        try {
            $connect = new PDO("mysql:host=".$_ENV["MYSQL_HOST"].";dbname=".$_ENV["MYSQL_DB_NAME"].";charset=utf8", $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"]);
        }
        catch (PDOException $e) { // Caso a conexão falhe, mostra o erro...
            return json_encode(["message" =>"Error connect in database", "ERROR: "=> $e->getMessage()]);
        }
		return $connect;
    } 
}