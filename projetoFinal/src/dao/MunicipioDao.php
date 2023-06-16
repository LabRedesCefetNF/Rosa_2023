<?php
use interfaces\InterfacePDO;
set_include_path('/var/www/html/projetoFinal/src/interfaces/');
require_once 'InterfacePDO.php';

    class MunicipioDao implements InterfacePDO
    {
        private $conexao;

        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        }

        public function inserir(Municipio $municipio)
        {
            $sqlInsert = null;
            try{
                $sqlInsert = "insert into municipio (nome, id_estado) values (:nome, :id_estado)";
                $ps = $this->conexao->prepare($sqlInsert);
                $ok = $ps->execute(
                    array(
                        "nome" => $municipio->recuperarNome(),
                        "id_estado" => $municipio->recuperarIdEstado()
                    )
                );
                
                if (! $ok){
                    die("\nErro ao executar comando na inserção de municipio. ");
                }
                if ($ps->rowCount()>0){
                    //echo "\nMunicipio inserido com sucesso. ";
                    return true;
                }
                else {
                    echo "\nErro ao inserir municipio. ";
                }
                

            }
            catch(PDOException $e){
                die("\nErro ao inserir municipio. ". $e->getMessage());
            }
            
        }

        public function buscarMunicipios()
        {
            $sqlSelect = null;
            $arrayMunicipios = array();
            try{
                $sqlSelect = "select id, nome, id_estado from municipio";
                $ps = $this->conexao->query($sqlSelect);
                $linhas = $ps->fetchAll();
                foreach($linhas as $linha){
                  $municipio = new Municipio($linha[1], $linha[2], $linha[0]);
                  $arrayMunicipios[] = $municipio;

                }
                //echo "\nSucesso ao buscar todos os municipios. ";
                return $arrayMunicipios;
            }
            catch(PDOException $e){
                die("\nFalha ao buscar todos os municipios. " . $e->getMessage());
            }
        }

        public function buscarUltimo(string $chave = null)
        {
            $municipio = null;
            $sqlSelect = null;
            try{
                 $sqlSelect = "select id, nome, id_estado from municipio 
                              where id = (select max(id) from municipio)";
                $ps = $this->conexao->query($sqlSelect);
                if (!$ps){
                    die("\nFalha ao realizar comando de busca do último município inserido. ");
                }
                
                    $linha = $ps->fetchAll();
                    foreach($linha as $l){
                        $municipio = new Municipio($l[1], $l[2], $l[0]);

                    }
                    //echo "\nÚltimo municipio inserido retornado com sucesso. ";
                    return $municipio;
                
                
                 
            }
            catch(PDOException $e){
                die("\nFalha ao buscar o último municipio inserido. " . $e->getMessage());
            }

        }

        public function buscarPorId(int $id, string $chave = null)
        {
            $sqlSelect = null;
            try{
                 $sqlSelect = "select id_estado, nome from municipio where id = :id";
                 $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "id" => $id
                    )
                );
                if(!$ok){
                    die("\nFalha ao executar comando de busca de municipio por Id. ");
                }
                
                    $linha = $ps->fetchAll();
                    foreach($linha as $l){
                         $municipio = new Municipio($l[1], $l[0], $id);
                        
                    }
                    //echo "\nSucessso ao buscar municipio por ID. ";
                    return $municipio;
                
            }
            catch(PDOException $e){
                die("\nFalha ao buscar municipio por Id. " . $e->getMessage());
            }
        }

        public function deletar(int $id, string $chave = null)
        {
            $sqlDelete = null;
            try{
                $sqlDelete = "DELETE from municipio
                                where id = :id";
                $ps = $this->conexao->prepare($sqlDelete);
                $ps->bindParam('id', $id, PDO::PARAM_INT);
                $ok = $ps->execute();
                if(!$ok){
                    die("\nFalha ao executar comando para deletar municipio. ");
                }
                //echo "\nSucesso ao executar comando para deletar municipio.";
                return true;

            }
            catch(PDOException $e){
                die("\nFalha ao deletar municipio. " .$e->getMessage());
            }
        }

       

    }
?>