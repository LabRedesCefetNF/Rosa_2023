<?php
set_include_path('/var/www/html/projetoFinal/src/interfaces/');

require_once 'InterfacePDO.php';

use interfaces\InterfacePDO;

    class PacienteDAOMariaDB implements InterfacePDO
    {
        private $conexao = null;

        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        }

        public function inserirCifrado(Paciente $paciente, string $chave)
        {
            $sqlInsert = null;
            try{
                $sqlInsert = "insert into paciente (nome, origem) values (AES_ENCRYPT(:nome, :key), AES_ENCRYPT(:origem, :key))";
                $ps = $this->conexao->prepare($sqlInsert);
            $ok = $ps->execute(
                array(
                    "nome" => $paciente->recuperarNome(),
                    "key" => $chave,
                    "origem" => $paciente->recuperarOrigem()
                )
                 );
                if(!$ok){
                     die("Erro ao executar comando de inserir paciente cifrado. ");
                }
                if ($ps->rowCount()>0){
                     //echo "Paciente cifrado com AES inserido com sucesso. ";
                     return true;
                }
                else{
                    die("Erro ao inserir paciente cifrado. ");
                }
            }
            catch(PDOException $e){
                 die("Falha ao inserir paciente cifrado. " . $e->getMessage());
            }
        }
        public function buscarUltimo(string $chave = null)
        {
            $sqlSelect = null;
            try{
                $sqlSelect = "select id, 
                                    AES_DECRYPT(nome, :key) as nome,
                                    AES_DECRYPT(origem, :key) as origem
                                    from paciente
                    where id = (select max(id) from paciente)";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave
                    )
                );
                if(!$ok){
                    die("Falha ao executar comando para busca de último paciente cifrado com AES");
                }
                if($ps->rowCount()>0){
                    $linha = $ps->fetch(PDO::FETCH_ASSOC);
                    $paciente = new Paciente($linha['nome'], $linha['origem'], $linha['id']);
                    //echo "Sucesso ao buscar último paciente cifrado com AES. ";
                    return $paciente;
                }
            }
            catch(PDOException $e){
                die("Falha ao realizar busca por ultimo paciente cifrado com AES");
            }
        }

        public function buscarPorId(int $id, string $chave = null)
        {
            $sqlSelect = null;
            try{
                $sqlSelect = "select AES_DECRYPT(nome, :key) as nome,
                                AES_DECRYPT(origem, :key) as origem
                                from paciente where id = :id";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave,
                        "id" => $id
                    )
                );
                if(!$ok){
                    die("\nFalha ao realizar comando de busca por paciente cifrado com AES por id no MariaDB. ");
                }
                if($ps->rowCount()>0){
                    $linha = $ps->fetchAll();
                    foreach($linha as $l){
                        $paciente = new Paciente($l[0], $l[1], $id);
                    }
                    //echo "\nSucesso ao buscar paciente cifrado com AES por id no MariaDB. ";
                    return $paciente;
                }
            }
            catch(PDOException $e){
                die("\nFalha ao buscar paciente cifrado com AES por id no MariaDB. " . $e->getMessage());
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
                    die("\nFalha ao deletar paciente cifrado com AES no MariaDB");
                }
                //echo "\Sucesso ao deletar paciente cifrado com AES no MariaDB";
                return true;
            }
            catch(PDOException $e){
                die("\nFalha ao deletar paciente cifrado com AES no MariaDB. " .$e->getMessage());
            }
        }
    }
?>