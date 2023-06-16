<?php

    class ConnectionCreateorPostgreSQL
    {
        public static function createConnection()
        {
            $host = "192.168.67.110";
            $user = 'postgres';
            $pass = 'root123';

            try{
                $pdo =  new PDO("pgsql:dbname=tcc host=".$host, $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "\nSucesso ao conectar ao PostgreSQL.";
                return $pdo;
            }
            catch(PDOException $e){
                die("\nFalha ao realizar conexão com o PostgreSQL. " . $e->getMessage());
            }
        }
    }