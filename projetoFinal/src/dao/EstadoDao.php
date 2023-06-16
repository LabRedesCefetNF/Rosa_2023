<?php 
set_include_path('/var/www/html/projetoFinal/src/interfaces/');
require_once 'InterfacePDO.php';

use interfaces\InterfacePDO;  
    class EstadoDao implements InterfacePDO
    {
        private $conexao = null;
    
        
        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        }

        public function inserir (Estado $estado)
        {
            $sqlInsert = null;
            try{
            $sqlInsert = "insert into estado (nome) 
                    values (:nome)";
            $ps = $this->conexao->prepare($sqlInsert);
            $ok = $ps->execute(
                    array(
                        'nome' => $estado->recuperarNome()
                        
                    )
                ); 
               if (! $ok){
                    die("\nErro ao executar comando de Inserir estado. "); 

                }
                if ($ps->rowCount()>0){
                    //echo "\nEstado inserido com sucesso. ";
                    return true;
                }
                
                else{
                echo "\nErro ao inserir estado. ";
                }
                
            }
            catch(PDOException $e){
            die("\nFalha ao realizar inserção de estado " . $e->getMessage());
            }
        }

        public function buscarUltimo (string $chave = null) 
        {
            $sqlSelect = null;
            $estado = null;
            try{
                $sqlSelect = "select id, nome from estado
                    where id = (select max(id) from estado);";
                $ps = $this->conexao->query($sqlSelect);
                $linha = $ps->fetchAll();
                foreach($linha as $l){
                    $estado = new Estado($l[1], $l[0]); 
                }

              //  $linha = $ps->fetch(PDO::FETCH_ASSOC);
                //$estado = new Estado($linha['NOME'], $linha['ID']);
                 //echo "\nUltimo estado inserido retornado com sucesso. ";
                
                return $estado;
            }
            catch(PDOException $e)
            {
                 die("\nFalha ao buscar por último estado inserido. " . $e->getMessage());
            }
        }

        public function buscarPorId(int $id, string $chave = null)
        {
            $sqlServer = null;
            try{
                $sqlSelect = " select nome from estado where id = :id";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "id" => $id
                    )
                );
                $linhas = $ps->fetchAll();
                if(!$ok){
                     die("\nFalha ao executar comando de buscar estado por Id ");
                }
                if($ps->rowCount()>0){
                    foreach($linhas as $l){
                        $estado = new estado($l[0], $id);

                      }
                    //echo "\nSucesso ao buscar estado por Id";
                    return $estado;
                }
                
                
            }
            catch(PDOException $e){
                die("\nFalha ao buscar estado por Id. " . $e->getMessage());
            }
        }

        public function deletar(int $id, string $chave = null)
        {
            $sqlDelete = null;
            try{
                $sqlDelete = "DELETE from estado
                                where id = :id";
                $ps = $this->conexao->prepare($sqlDelete);
                $ps->bindParam('id', $id, PDO::PARAM_INT);
                $ok = $ps->execute();
                if(!$ok){
                    die("\nFalha ao executar comando para deletar estado. ");
                }
                //echo "\nSucesso ao executar comando para deletar estado.";
                return true;

            }
            catch(PDOException $e){
                die("\nFalha ao deletar estado. " .$e->getMessage());
            }
        }

        public function alterar(int $id)
        {
            $sqlUpdate = null;
                try{
                    $sqlUpdate = "UPDATE estado
                    set nome = 'Alagoas'
                    where id in (select e.id 
                                  from notificacao n, estado e
                                  where n.id_estado = e.id
                                  and (select  nome
                                        from estado where id = :idEstado)  
                                      not like 'Alagoas'
                                      and e.id = :id
                                  )";
                $ps = $this->conexao->prepare($sqlUpdate);
                $ps->bindParam('idEstado', $id, PDO::PARAM_INT);
                $ps->bindParam('id', $id, PDO::PARAM_INT);
                $ok = $ps->execute();
                if(!$ok){
                    die("\nFalha ao executar comando de alterar estado em claro. ");
                }
               // echo "\nSucesso ao alterar estado em claro. ";
                return true;
            }
            catch(PDOException $e){
                die("\nFalha ao alterar estado em claro. " . $e->getMessage());
            }
        }

    }
          

?>