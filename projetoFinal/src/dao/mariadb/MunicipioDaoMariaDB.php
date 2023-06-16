<?php
set_include_path('/var/www/html/projetoFinal/src/interfaces/');

require_once 'InterfacePDO.php';

use interfaces\InterfacePDO;
    class MunicipioDaoMariaDB implements InterfacePDO
    {
        private $conexao = null;

        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        }

        public function inserirCifrado(Municipio $municipio, string $chave)
        {
            $sqlInsert = null;
            try{
                $sqlInsert = "insert into municipio (nome, id_estado) values (AES_ENCRYPT(:nome, :key), :id_estado)";
                $ps = $this->conexao->prepare($sqlInsert);
                $ok = $ps->execute(
                array(
                       "nome" => $municipio->recuperarNome(),
                       "key" => $chave,
                       "id_estado" => $municipio->recuperarIdEstado()
                     )
                );
                if (!$ok){
                    die("\nFalha ao realizar comando para inserir municipio cifrado com AES no MariaDB. ");
                }
                if ($ps->rowCount()>0){
                     //echo "\nSucesso ao inserir municipio cifrado com AES no MariaDB. ";
                     return true;
                }
                else{
                    die("\nErro ao inserir municipio cifrado com AES no MariaDB. ");
                }
            }
            catch(PDOException $e){
                die("\nErro ao inserir municipios cifrados com AES no MariaDB. " . $e->getMessage());
            }
        }

        public function buscarUltimo(string $chave = null)
        {
            $sqlSelect = null;
            try{
               
             $sqlSelect = "select id, AES_DECRYPT(nome, :key) as nome, id_estado from municipio 
             where id = (select max(id) from municipio)";
             $ps = $this->conexao->prepare($sqlSelect);
                $ps->execute(
                array(
                    "key" => $chave
                )
                );
                if(!$ps){
                
                die("Falha ao executar o comando para retornar o último município inserido cifrado. ");
                }
                if($ps->rowCount()>0){
                    
                    $linha = $ps->fetch(PDO::FETCH_ASSOC);
                    $municipio = new Municipio($linha['nome'], $linha['id_estado'], $linha['id']);
                    //echo "\nSucesso ao buscar último município";
                    return $municipio;
                }
                }
                catch(PDOException $e){
                    die("Falha ao buscar o último municipio cifrado. " . $e->getMessage());
                }
        }

        public function buscarPorId(int $id, string $chave = null)
        {
            $sqlSelect = null;
            try{
                $sqlSelect = "SELECT AES_DECRYPT(nome, :key) as nome, id_estado from municipio where id = :id";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave,
                        "id" => $id
                    )
                );
                if (!$ok){
                    die("\nFalha ao executar comando de  busca de municipio cifrado com AES por id no MariaDB. ");
                }
                if ($ps->rowCount()>0){
                    $linha = $ps->fetchAll();
                    foreach($linha as $l){
                        $municipio = new Municipio($l[0], $l[1], $id);
                    }
                    //echo "\nSucesso ao buscar municipio cifrado com AES por id no MariaDB. ";
                    return $municipio;
                }
            }
            catch(PDOException $e){
             die("\nFalha ao buscar municipio cifrado com AES por ID no MariaDB. " . $e->getMessage());
            }
        }

        public function deletar (int $id, string $chave = null)
        {
            $sqlDelete = null;
            try{
                $sqlDelete = "DELETE from municipio
                            where id  = :id";
                $ps = $this->conexao->prepare($sqlDelete);
                $ps->bindParam('id', $id, PDO::PARAM_INT);
                $ok = $ps->execute();
                if(!$ok){
                    die("\nFalha ao deletar municipio cifrado com AES no MariaDB");
                }
                //echo "\nSucesso ao deletar municipio cifrado com AES no MariaDB";
                return true;
            }
            catch(PDOException $e){
                die("\nFalha ao deletar municipio cifrado com AES no MariaDB. " .$e->getMessage());
            }
        }
    }
?>