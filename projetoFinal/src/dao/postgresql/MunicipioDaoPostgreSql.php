<?php
set_include_path('/var/www/html/projetoFinal/src/interfaces/');

require_once 'InterfacePDO.php';
use interfaces\InterfacePDO;

    class MunicipioDaoPostgreSql implements InterfacePDO
    {
        private $conexao = null;

        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        }

        public function inserirCifrado(Municipio $municipio, string $chave)
        {
            $sqlInsert = null;
            $strAlgo = "aes-cbc";
            try{
                $sqlInsert = "INSERT into municipio (nome, id_estado) 
                                values (encrypt_iv(:nome, :key, '0123456789123456', :algoritmo), 
                                :id_estado)";
                $ps = $this->conexao->prepare($sqlInsert);
                $ok = $ps->execute(
                    array(
                        "nome" => $municipio->recuperarNome(),
                        "key" => $chave,
                        "algoritmo" => $strAlgo,
                        "id_estado" => $municipio->recuperarIdEstado()
                    )
                    );
                    if(!$ok){
                        die("\nFalha ao executar comando para inserir municipio cifrado com  AES modo CBC no postgreSQL.");
                    }
                    //echo "\nSucesso ao inserir municipio cifrado com  AES modo CBC no postgreSQL.";
                    return true;
                }
                catch(PDOException $e){
                    die("\nFalha ao inserir municipio cifrado com AES modo CBC no postgreSQL" . $e->getMessage());
                }
            }
        

        public function buscarUltimo(string $chave = null )
        {
            $sqlSelect = null;
            $strAlgo = "aes-cbc";
            $municipio = null;
            try{
                $sqlSelect = "SELECT id, CONVERT_FROM(decrypt_iv(nome::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as nome, 
                                id_estado
                                from municipio where id = (select max(id) from municipio)";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave,
                        "algoritmo" => $strAlgo
                    )
                    );
                if(!$ok){
                    die("\nFalha ao executar comando de busca de municipio cifrado com AES modo CBC no postgreSQL");
                }
                $linha = $ps->fetchAll();
                foreach($linha as $l){
                    $municipio = new Municipio($l[1],$l[2], $l[0]);
                }
                //echo "\nSucesso ao buscar último municipio cifrado com AES modo CBC no postgreSQL";
                return $municipio;
            }
            catch(PDOException $e){
                die("\nFalha ao buscar último municipio cifrado com AES modo CBC no postgreSQL. " . $e->getMessage());
            }
        }
        

        public function buscarPorId(int $id , string $chave = null)
        {
            $sqlSelect = null;
            $strAlgo = "aes-cbc";
            $municipio = null;
            try{
                $sqlSelect = "SELECT CONVERT_FROM(decrypt_iv(nome::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as nome, 
                                    id_estado
                                from municipio where id= :id";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave,
                        "algoritmo" => $strAlgo,
                        "id" => $id
                    )
                    );
                if(!$ok){
                    die("\nFalha ao executar comando para buscar municipio cifrado com AES modo CBC por id no postgreSQL");
                }
                $linha = $ps->fetchAll();
                foreach($linha as $l){
                    $municipio = new Municipio($l[0], $l[1], $id);
                }
                //echo "\nSucesso ao buscar municipio cifrado com AES modo CBC por id no postgreSQL";
                return $municipio;
            }
            catch(PDOException $e)
            {
                die("\nFalha ao buscar municipio cifrado com AES modo CBC por id no postgreSQL " .$e->getMessage());
            }
        }

        public function deletar(int $id, string $chave = null)
        {
            $sqlDelete = null;
            try{
                $sqlDelete = "DELETE from municipio
                            where id  = :id";
                $ps = $this->conexao->prepare($sqlDelete);
                $ps->bindParam('id', $id, PDO::PARAM_INT);
                $ok = $ps->execute();
                if(!$ok){
                    die("\nFalha ao deletar municipio cifrado com AES no postgreSQL");
                }
                //echo "\Sucesso ao deletar municipio cifrado com AES no postgreSQL";
            }
            catch(PDOException $e){
                die("\nFalha ao deletar municipio cifrado com AES no PostgreSQL. " .$e->getMessage());
            }
        }
    }

?>