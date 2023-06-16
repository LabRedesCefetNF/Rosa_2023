<?php
set_include_path('/var/www/html/projetoFinal/src/interfaces/');
require_once 'InterfacePDO.php';
use interfaces\InterfacePDO;
    class MunicipioDaoFirebird implements InterfacePDO
    
    {
        private $conexao = null;

        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        }

        public function inserirCifrado(Municipio $municipio, string $chave)
        {
            $sqlInsert = null;
            $nome = $municipio->recuperarNome();
            $idEstado = $municipio->recuperarIdEstado();
            try{
                $sqlInsert = "INSERT into municipio (nome, id_estado) values 
                                (ENCRYPT(:nome using aes mode OFB key :chave iv '0123456789123456'), :id_estado)";
                $ps = $this->conexao->prepare($sqlInsert);
                $ps->bindValue('nome', $nome, PDO::PARAM_STR);
                $ps->bindValue('chave', $chave, PDO::PARAM_STR);
                $ps->bindValue('id_estado', $idEstado, PDO::PARAM_INT);
                $ps->execute();
                if(!$ps){
                    die("\nFalha ao executar comando para inserir municipio cifrado com AES modo OFB no firebird");
                }
                if($ps->rowCount()>0){
                    //echo "\nSucesso ao inserir municipio cifrado com AES modo OFB no firebird. ";
                    return true;
                }
            }
            catch(PDOException $e){
                die("\nFalha ao inserir municipio cifrado com AES modo OFB no firebird. " . $e->getMessage());
            }
        }

        public function buscarUltimo(string $chave = null)
        {
            $sqlSelect = null;
            $municipio = null;
            try{
                $sqlSelect = "SELECT id, DECRYPT(nome using aes mode OFB key :chave iv '0123456789123456') as nome, id_estado from municipio 
                                where id = (select max(id) from municipio)";
                $ps = $this->conexao->prepare($sqlSelect);
                $ps->bindValue('chave', $chave, PDO::PARAM_STR);
                $ps->execute();
                if(!$ps){
                    die("\nFalha ao executar comando para buscar último municipio cifrado com AES modo OFB no firebird");
                }
                $linha = $ps->fetchAll();
                foreach($linha as $l){
                    $municipio = new Municipio($l[1],$l[2], $l[0]);
                }
                //echo "\nSucesso ao  buscar último municipio cifrado com AES modo ECB no firebird.";
                return $municipio;
            }
            catch(PDOException $e){
                die("\nFalha ao buscar último município cifrado no firebird com AES modo ECB. " . $e->getMessage());
            }
        } 
        
        public function buscarPorId(int $id, string $chave = null)
        {
            $sqlSelect = null;
            $municipio = null;
            try{
                $sqlSelect = "SELECT DECRYPT(nome using aes mode OFB key :chave iv '0123456789123456') as nome, id_estado
                                from municipio where id = :id";
                $ps = $this->conexao->prepare($sqlSelect);
                $ps->bindValue('chave', $chave, PDO::PARAM_STR);
                $ps->bindValue('id', $id, PDO::PARAM_INT);
                $ps->execute();
                if(!$ps){
                    die("\nFalha ao executar comando para buscar municipio cifrado com AES modo OFB no firebird por id.");
                }
                $linha = $ps->fetchAll();
                foreach($linha as $l){
                    $municipio = new Municipio($l[0], $l[1], $id);
                }
                //echo "\nSucesso ao buscar municipio cifrado com AES modo OFB no firebird por id.";
                return $municipio;
             }catch(PDOException $e){
                die("Falha ao buscar municipio cifrado com AES modo OFB no firebird por id." . $e->getMessage());
            }
        }

        public function deletar(int $id, string $chave = null )
        {
            $sqlDelete = null;
                try{
                    $sqlDelete = "DELETE from municipio
                                where id  = :id";
                    $ps = $this->conexao->prepare($sqlDelete);
                    $ps->bindParam('id', $id, PDO::PARAM_INT);
                    $ok = $ps->execute();
                    if(!$ok){
                        die("\nFalha ao deletar municipio cifrado com AES no Firebird");
                    }
                    //echo "\Sucesso ao deletar municipio cifrado com AES no Firebird";
                }
                catch(PDOException $e){
                    die("\nFalha ao deletar municipio cifrado com AES no Firebird. " .$e->getMessage());
                }
        }


            

    }

?>