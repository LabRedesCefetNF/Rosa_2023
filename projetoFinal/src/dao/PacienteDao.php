<?php
use interfaces\InterfacePDO;
set_include_path('/var/www/html/projetoFinal/src/interfaces/');
require_once 'InterfacePDO.php';
    class PacienteDAO implements InterfacePDO {
        private $conexao;

        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        }

        public function inserir (Paciente $paciente)
        {
            $sqlInsert = null;
            try{
            $sqlInsert = "insert into paciente (nome, origem) values (:nome, :origem)";
            $ps = $this->conexao->prepare($sqlInsert);
            $ok =$ps->execute(
                array(
                    "nome" => $paciente->recuperarNome(),
                    "origem" => $paciente->recuperarOrigem()
                )
            );
            if (! $ok){
                die("\nErro ao executar comando na inserção do municipio. ");
            }
            if ($ps->rowCount()>0){
                //echo "\nPaciente inserido com sucesso. ";
                return true;
            }
            else {
                echo "\nErro ao inserir municipio. ";
            }
            }
            catch(PDOException $e){
                die("\nErro ao inserir paciente " . $e->getMessage());
            }
        }

        public function buscarUltimo (string $chave = null)
        {
            $sqlSelect = null;
            $paciente = null;
            try{
                $sqlSelect = "select nome, origem, id from paciente
                    where id = (select max(id) from paciente)";
                $ps = $this->conexao->query($sqlSelect);
                $linha = $ps->fetchall();
                foreach($linha as $l){
                    $paciente = new Paciente($l[0], $l[1], $l[2]);

                }
                //echo "\nÚltimo paciente retornado com sucesso. ";
                return $paciente;
            }
            catch(PDOException $e){
                die("\nFalha ao buscar o último paciente inserido " . $e->getMessage());
            }
            
        }

        public function buscarPorId(int $id, string $chave = null)
        {
            $sqlSelect = null;
            try{
                $sqlSelect = "select nome, origem from paciente where id = :id";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "id" => $id
                    )
                );
                if(!$ok){
                  die("\nFalha ao executar comando para buscar paciente por Id. ");
                }
                
                    $linha = $ps->fetchAll();
                    foreach($linha as $l){
                        $paciente = new Paciente($l[0], $l[1], $id);
                    }
                    //echo "\nSucesso ao buscar paciente por Id. ";
                    return $paciente;
                
            }
            catch(PDOException $e){
                die("\nFalha ao buscar paciente por Id. " . $e->getMessage());
            }
        }
        

        public function buscarPacientes()
        {
            $arrayPacientes = array();
            $sqlSelect = null;
            try{
                $sqlSelect = "select id, nome, origem from paciente";
                $ps = $this->conexao->query($sqlSelect);
                $linhas = $ps->fetchAll();
                foreach($linhas as $linha){
                    $paciente = new Paciente($linha[1], $linha[2], $linha[0]);
                    $arrayPacientes[] = $paciente;
                }
                //echo "\nSucesso ao buscar pacientes. ";
                return $arrayPacientes;
            }
            catch(PDOException $e){
            die("\nFalha ao retornar os pacientes. " . $e->getMessage());
            }
        }

        public function deletar(int $id, string $chave = null)
        {
            $sqlDelete = null;
            try{
                $sqlDelete = "DELETE from paciente
                                where id = :id";
                $ps = $this->conexao->prepare($sqlDelete);
                $ps->bindParam('id', $id, PDO::PARAM_INT);
                $ok = $ps->execute();
                if(!$ok){
                    die("\nFalha ao executar comando para deletar paciente. ");
                }
                //echo "\nSucesso ao executar comando para deletar paciente.";
                return true;

            }
            catch(PDOException $e){
                die("\nFalha ao deletar paciente. " .$e->getMessage());
            }
        }
                


        
           



    }
?>