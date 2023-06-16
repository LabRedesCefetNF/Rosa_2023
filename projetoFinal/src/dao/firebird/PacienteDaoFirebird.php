<?php
set_include_path('/var/www/html/projetoFinal/src/interfaces/');
require_once 'InterfacePDO.php';
use interfaces\InterfacePDO;

    class PacienteDaoFirebird implements InterfacePDO
    {
        private $conexao = null;

        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        } 

        public function inserirCifrado ($paciente, string $chave)
        {
            $sqlInsert = null;
            $nome = $paciente->recuperarNome();
            $origem = $paciente->recuperarOrigem();
          
            
            try{
                $sqlInsert = "INSERT into paciente (nome, origem) values
                                (ENCRYPT(:nome using aes mode OFB key :chave iv '0123456789123456'),
                                ENCRYPT(:origem using aes mode OFB key :chave2 iv '0123456789123456'))";
                $ps = $this->conexao->prepare($sqlInsert);
                $ps->bindValue('nome', $nome, PDO::PARAM_STR);
                $ps->bindParam('chave', $chave, PDO::PARAM_STR);
                $ps->bindParam('chave2', $chave, PDO::PARAM_STR); // por algum motivo, o firebird não aceitou palavras iguais para o bind de mesmo valor em parâmetros nas duas funções
                $ps->bindValue('origem', $origem, PDO::PARAM_STR);
                $ps->execute();
                if(!$ps){
                    die("\nFalha ao executar comando para inserir paciente cifrado com AES modo OFB no firebird.");
                }
                //echo "\nSucesso ao inserir paciente cifrado com AES modo OFB no firebird.";
                return true;
            }
            catch(PDOException $e){
                die("Falha ao inserir paciente cifrado com AES modo OFB no firebird. " . $e->getMessage());
            }
        }

        public function buscarUltimo(string $chave = null)
        {
            $sqlSelect = null;
            $paciente = null;
            try{
                $sqlSelect = "SELECT id, DECRYPT(nome using aes mode OFB key :chave iv '0123456789123456') as nome,
                                DECRYPT(origem using aes mode OFB key :chave2 iv '0123456789123456') as origem
                                from paciente where id = (select max(id) from paciente)";
                $ps = $this->conexao->prepare($sqlSelect);
                $ps->bindParam('chave', $chave, PDO::PARAM_STR);
                $ps->bindParam('chave2', $chave, PDO::PARAM_STR);
                $ps->execute();
                if(!$ps){
                    die("\nFalha ao  buscar último paciente cifrado com AES modo OFB no Firebird. ");
                }
                $linha = $ps->fetchAll();
                foreach($linha as $l){
                    $paciente = new Paciente($l[1],$l[2],$l[0]);
                }
                //echo "\nSucesso ao buscar último paciente cifrado com AES modo OFB no Firebird.";
                return $paciente;
            }
            catch(PDOException $e){
                die("\nFalha ao buscar último paciente cifrado com AES modo OFB no firebird. " . $e->getMessage());
            }
        }

        public function buscarPorId(int $id, string $chave = null)
        {
            $sqlSelect = null;
            $paciente = null;
            try{
                $sqlSelect = "SELECT DECRYPT(nome using aes mode OFB key :chave iv '0123456789123456') as nome,
                                DECRYPT(origem using aes mode OFB key :chave2 iv '0123456789123456') as origem
                                from paciente where id = :id ";
                $ps = $this->conexao->prepare($sqlSelect);
                $ps->bindValue('id', $id, PDO::PARAM_STR);
                $ps->bindParam('chave', $chave, PDO::PARAM_STR);
                $ps->bindParam('chave2', $chave, PDO::PARAM_STR);
                $ps->execute();
                if(!$ps){
                    die("\nFalha ao executar comando para buscar paciente cifrado com AES modo OFB no Firebird.");
                }
                $linha = $ps->fetchAll();
                foreach($linha as $l){
                    $paciente = new Paciente($l[0],$l[1],$id);
                } 
                //echo "\nSucesso ao buscar paciente cifrado com AES modo OFB no Firebird por Id.";
                return $paciente;
            }
            catch(PDOException $e){
                die("\nFalha ao buscar paciente cifrado com AES modo OFB no firebird. " . $e->getMessage());
            }
        }

        public function deletar(int $id, string $chave = null)
        {
            $sqlDelete = null;
                try{
                    $sqlDelete = "DELETE from paciente
                                where id  = :id";
                    $ps = $this->conexao->prepare($sqlDelete);
                    $ps->bindParam('id', $id, PDO::PARAM_INT);
                    $ok = $ps->execute();
                    if(!$ok){
                        die("\nFalha ao deletar paciente cifrado com AES no Firebird");
                    }
                    //echo "\Sucesso ao deletar paciente cifrado com AES no Firebird";
                }
                catch(PDOException $e){
                    die("\nFalha ao deletar paciente cifrado com AES no Firebird. " .$e->getMessage());
                }
        }

    }

?>