<?php
set_include_path('/var/www/html/projetoFinal/src/interfaces/');

require_once 'InterfacePDO.php';

use interfaces\InterfacePDO;

    class EstadoDaoMariaDB implements InterfacePDO
    {
        private $conexao = null;

        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        }

        public function inserirCifrado(Estado $estado, string $chave)
        {
            $sqlInsert = null;
            try{
                $sqlInsert = "insert into estado (nome) values (AES_ENCRYPT(:nome,:chave))";
                $ps = $this->conexao->prepare($sqlInsert);
                $ok = $ps->execute(
                    array(
                        'nome' => $estado->recuperarNome(),
                        'chave' => $chave
                        
                    )
                );
                if (! $ok){
                    die("\nErro ao executar comando de inserir estado cifrado com AES no MariaDB. "); 
                }
                if ($ps->rowCount()>0){
                    //echo "\nEstado cifrado com AES inserido com sucesso no MariaDB. ";
                    return true;
                }
                else{
                echo "\nErro ao inserir estado cifrado com AES no MariaDB. ";
                }
            }
            catch (PDOException $e){
                die('Erro ao inserir estado com nome cifrado. ' . $e->getMessage());
            }

        }

        public function buscarUltimo(string $chave = null)
        {
            $sqlSelect = null;
            try{
                $sqlSelect = "select id, AES_DECRYPT(nome, :key) as nome
                            
                                from estado
                                where id = (select max(id) from estado)";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave
                    )
                );
                if(!$ok){
                    die("\nFalha ao executar comando de busca do último estado cifrado com AES no MariaDB. ");
                }
                if($ps->rowCount()>0){
                    $linha = $ps->fetch(PDO::FETCH_ASSOC);
                    $estado = new Estado($linha['nome'],$linha['id']);
                    //echo "\nSucesso ao buscar último estado cifrado com AES no MariaDB. ";
                    return $estado;
                }
            }
            catch(PDOException $e){
                 die("\nFalha ao buscar último estado cifrado com AES no MariaDB. " . $e->getMessage());
            }
        }

        public function buscarPorId(int $id, string $chave = null)
        {
            $sqlSelect = null;
            try{
                $sqlSelect = "select AES_DECRYPT(nome, :key) as nome
                                from estado
                                where id = :id";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave,
                        "id" => $id
                    )
                );
                if(!$ok){
                    die("\nFalha ao executar comando de busca por estado cifrada com AES por ID no MariaDB. ");
                }
                if($ps->rowCount()>0){
                    $linha = $ps->fetchAll();
                    foreach($linha as $l){
                        $estado = new Estado($l[0], $id);
                    }
                  
                    //echo "\nSucesso ao buscar estado cifrado com AES por ID no MariaDB. ";
                    return $estado;
                }


            }
            catch(PDOException $e){
                die("Falha ao buscar estado cifrada com AES por ID no MariaDB. " . $e->getMessage());
            }
        }

        public function alterar(string $chave, int $id)
        {
            $sqlUpdate = null;
            
            try{
                $sqlUpdate = "UPDATE estado
                set nome = AES_ENCRYPT('Alagoas', :key)
                where id in (select e.id 
                              from notificacao n, estado e
                              where n.id_estado = e.id
                              and (select AES_DECRYPT(nome, :key2) as nome
                                    from estado where id = :idEstado)  
                                  not like 'Alagoas'
                                  and e.id = :id
                              )";
                $ps = $this->conexao->prepare($sqlUpdate);
                $ps->bindParam('key', $chave, PDO::PARAM_STR);
                $ps->bindParam('key2', $chave, PDO::PARAM_STR);
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

        public function deletar(int $id, string $chave = null )
        { 
            $sqlDelete = null;
            try{
                $sqlDelete = "DELETE from estado
                            where id  = :id";
                $ps = $this->conexao->prepare($sqlDelete);
                $ps->bindParam('id', $id, PDO::PARAM_INT);
                $ok = $ps->execute();
                if(!$ok){
                    die("\nFalha ao deletar estado cifrado com AES no MariaDB");
                }
                //echo "\Sucesso ao deletar estado cifrado com AES no MariaDB";
                return true;
            }
            catch(PDOException $e){
                die("\nFalha ao deletar estado cifrado com AES no MariaDB. " .$e->getMessage());
            }

        }

    }
?>