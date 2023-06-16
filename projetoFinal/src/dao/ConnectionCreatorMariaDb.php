<?php


    class ConnectionCreatorMariaDB
    {
    
        public static function createConnection()
        {
            $dns = 'mysql:host=192.168.67.189;port=3306;dbname=tcc;charset=latin1';
            $usuario = 'root';
            $senha = 'root123';
          $pdo = null;
          try{
            $pdo = new PDO($dns, $usuario, $senha);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo 'sucesso ao conectar';
            return $pdo;
            
          }catch(PDOException $e){
            die('Falha ao realizar    conexão ' . $e->getMessage());         
         }

        }
    }
?>