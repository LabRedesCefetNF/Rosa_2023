<?php

    class ConnectionCreatorFirebird
    {
        public static function createConnection()
        {
            $user = "SYSDBA";
            $password = "root123";
            $pdo = null;
            try{
                $pdo = new PDO("firebird:host=localhost;dbname=localhost:/home/cefet/tcc.fdb;", $user, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "[DRIVER] Sucesso ao conectar no firebird. ";
            return $pdo;
            }
            catch(PDOException $e){
                die("Falha ao realizar conexão ao firebird. " . $e->getMessage());
            }

        }
    }
?>