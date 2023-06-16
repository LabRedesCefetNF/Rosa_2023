<?php
set_include_path('/var/www/html/projetoFinal/src/interfaces/');

require_once 'InterfacePDO.php';

use interfaces\InterfacePDO;

    class EstadoDaoPostgreSql implements InterfacePDO
    {
        private $conexao = null;

        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        }

        public function inserirCifrado( Estado $estado,string $chave)
        {
            $sqlInsert = null;
            $strAlgo = "aes-cbc";
            
            try{
                $sqlInsert = "INSERT into estado (nome) values
                                (encrypt_iv(:nome, :key, '0123456789123456',:algoritmo))";
                $ps = $this->conexao->prepare($sqlInsert);
                $ok = $ps->execute(
                    array(
                        "nome" => $estado->recuperarNome(),
                        "key" =>  $chave,
                        "algoritmo" => $strAlgo   
                    )
                    );
                if(!$ok){
                    die("\nFalha ao executar comando de inserir estado cifrado com AES modo CBC no postgreSQL.");
                }
                //echo "\nSucesso ao inserir estado cifrado com AES modo CBC no postgreSQL.";
                return true;
            }
            catch(PDOException $e){
                die("\nFalha ao inserir estado cifrado com algoritmo AES modo CBC no postgreSQL. " .$e->getMessage());
            }
        }

        public function buscarUltimo(string $chave = null )
        {
            $sqlSelect = null;
            $estado = null;
            $strAlgo = "aes-cbc";
            try{
                $sqlSelect = "SELECT id, CONVERT_FROM(decrypt_iv(nome::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as nome from estado
                                where id = (select max(id) from estado)";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave,
                        "algoritmo" => $strAlgo
                    )
                    );
                if(!$ok){
                        die("\nFalha ao executar comando para buscar último estado cifrado com AES modo CBC no postgreSQL.");
                }
                $linhas = $ps->fetchAll();
                foreach($linhas as $l){
                    $estado = new Estado($l[1], $l[0]);
                }
                //echo "\nSucesso ao buscar último estado cifrado com AES modo CBC no postgreSQL. ";
                return $estado;
            }
            catch(PDOException $e){
                die("\nFalha ao buscar último regitro cifrado com AES modo CBC no postgreSQL" .$e->getMessage());
            }
        }

        public function buscarPorId(int $id, string $chave = null)
        {
            $sqlSelect = null;
            $estado = null;
            $strAlgo = "aes-cbc";
            try{
                $sqlSelect = "SELECT CONVERT_FROM(decrypt_iv(nome::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as nome 
                                from estado where id =:id";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave,
                        "algoritmo" => $strAlgo,
                        "id" => $id
                    )
                    );
                    if(!$ok){
                        die("\nFalha ao executar comando para buscar estado cifrado com AES modo CBC por id no postgreSQL");
                    }
                    $linha = $ps->fetchAll();
                    foreach($linha as $l){
                        $estado = new Estado($l[0], $id);
                    }
                    //echo "\nSucesso ao buscar estado cifrado com AES modo CBC por id no postgreSQL";
                    return $estado;
                }
                catch(PDOException $e){
                    die("\nFalha ao buscar estado cifrado com AES modo CBC no postgreSQL por id. " . $e->getMessage());
                }
        }

        public function alterar( string $chave, int $id)
        {
            $sqlUpdate = null;
            $strAlgo = "aes-cbc";
            try{
                $sqlUpdate = "UPDATE estado
                set nome = encrypt_iv('Alagoas', :key, '0123456789123456',:algoritmo)
                where id in (select e.id 
                              from notificacao n, estado e
                              where n.id_estado = e.id
                              and (select CONVERT_FROM(decrypt_iv(nome::bytea, :key2, '0123456789123456', :algoritmo2), 'SQL_ASCII') as nome
                                    from estado where id = :idEstado)  
                                  not like 'Alagoas'
                                  and e.id = :id
                              )";
                $ps = $this->conexao->prepare($sqlUpdate);
                $ps->bindParam('key', $chave, PDO::PARAM_STR);
                $ps->bindParam('algoritmo', $strAlgo, PDO::PARAM_STR);
                $ps->bindParam('key2', $chave, PDO::PARAM_STR);
                $ps->bindParam('algoritmo2', $strAlgo, PDO::PARAM_STR);
                $ps->bindParam('idEstado', $id, PDO::PARAM_INT);
                $ps->bindParam('id', $id, PDO::PARAM_INT);
            
                $ok = $ps->execute ();
                if(!$ok){
                    die("\nFalha ao executar comando de atualizar estado da notificação cifrada com AES modo CBC no postgreSQL");
                }
                //echo "\nSucesso ao atualizar estado ligado à notificação cifrada com AES modo CBC no postgreSQL";
                return true;
            }
            catch(PDOException $e){
                die("\nFalha ao atualizar estado da notificação cifrada com AES modo CBC no postgreSQL." . $e->getMessage());
            }

        }

        public function deletar(int $id, string $chave = null)
        {
            $sqlDelete = null;
            try{
                $sqlDelete = "DELETE from estado
                            where id  = :id";
                $ps = $this->conexao->prepare($sqlDelete);
                $ps->bindParam('id', $id, PDO::PARAM_INT);
                $ok = $ps->execute();
                if(!$ok){
                    die("\nFalha ao deletar estado cifrado com AES no postgreSQL");
                }
                //echo "\Sucesso ao deletar estado cifrado com AES no postgreSQL";
            }
            catch(PDOException $e){
                die("\nFalha ao deletar estado cifrado com AES no PostgreSQL. " .$e->getMessage());
            }
        }

        
    }
    
?>