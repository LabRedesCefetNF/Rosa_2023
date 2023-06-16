<?php
set_include_path('/var/www/html/projetoFinal/src/interfaces/');

require_once 'InterfacePDO.php';

use interfaces\InterfacePDO;

    class PacienteDaoPostgreSql implements InterfacePDO
    {
        private $conexao = null;

        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        }

        public function inserirCifrado(Paciente $paciente, string $chave)
        {
            $sqlInsert = null;
            $strAlgo = "aes-cbc";
            try{
                $sqlInsert = "INSERT into paciente (nome, origem) values(
                               encrypt_iv(:nome, :key, '0123456789123456', :algoritmo),
                               encrypt_iv(:origem, :key, '0123456789123456', :algoritmo))";
                $ps = $this->conexao->prepare($sqlInsert);
                $ok = $ps->execute(
                    array(
                        "nome" => $paciente->recuperarNome(),
                        "origem" => $paciente->recuperarOrigem(),
                        "key" => $chave,
                        "algoritmo" => $strAlgo
                    )
                    );
                    if(!$ok){
                        die("\nFalha ao executar comando de inserir paciente cifrado com AES modo CBC no postgreSQL");
                    }
                    //echo "\nSucesso ao inserir paciente cifrado com AES modo CBC no postgreSQL";
                    return true;
                }
                catch(PDOException $e){
                    die("\nFalha ao inserir paciente cifrado com AES modo CBC no postgreSQL. " .$e->getMessage());
                }
        }

        public function buscarUltimo(string $chave = null)
        {
            $sqlSelect = null;
            $strAlgo = "aes-cbc";
            $paciente = null;
            try{
                $sqlSelect = "SELECT id, 
                            CONVERT_FROM(decrypt_iv(nome::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as nome,
                            CONVERT_FROM(decrypt_iv(origem::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as origem
                                from paciente where id = (select max(id) from paciente)";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave,
                        "algoritmo" => $strAlgo
                        
                    )
                    );
                if(!$ok){
                    die("\nFalha ao executar comando para buscar último paciente cifrado com AES modo CBC no postgreSQL");
                }
                $linha = $ps->fetchAll();
                foreach($linha as $l){
                    $paciente = new Paciente($l[1],$l[2],$l[0]);
                }
                //echo "\nSucesso ao buscar último paciente cifrado com AES modo CBC no postgreSQL";
                return $paciente;
            }
            catch(PDOException $e){
                die ("\nFalha ao buscar último paciente cifrado com AES modo CBC no postgreSQL" . $e->getMessage());
            }
        }

        public function buscarPorId(int $id, string $chave = null)
        {
            $sqlSelect = null;
            $strAlgo = "aes-cbc";
            $paciente = null;
            try{
                $sqlSelect = "SELECT 
                            CONVERT_FROM(decrypt_iv(nome::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as nome,
                            CONVERT_FROM(decrypt_iv(origem::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as origem
                                    from paciente where id=:id";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave,
                        "algoritmo" => $strAlgo,
                        "id" => $id
                    )
                    );
                if(!$ok){
                    die("\nFalha ao executar comando para buscar paciente cifrado com AES modo CBC por id no postgreSQL");
                }
                $linha = $ps->fetchAll();
                foreach($linha as $l){
                    $paciente = new Paciente($l[0],$l[1],$id);
                }
                    //echo "\nSucesso ao buscar paciente cifrado com AES modo CBC por id no postgreSQL";
                    return $paciente;
            }
            catch(PDOException $e){
                die ("\nFalha ao buscar paciente cifrado com AES modo CBC por id no postgreSQL" . $e->getMessage());

            }
        }

        public function deletar (int $id, string $chave = null)
        {
            $sqlDelete = null;
            try{
                $sqlDelete = "DELETE from paciente
                            where id  = :id";
                $ps = $this->conexao->prepare($sqlDelete);
                $ps->bindParam('id', $id, PDO::PARAM_INT);
                $ok = $ps->execute();
                if(!$ok){
                    die("\nFalha ao deletar Paciente cifrado com AES no postgreSQL");
                }
                //echo "\Sucesso ao deletar Paciente cifrado com AES no postgreSQL";
            }
            catch(PDOException $e){
                die("\nFalha ao deletar Paciente cifrado com AES no PostgreSQL. " .$e->getMessage());
            }
        }
    }
?>