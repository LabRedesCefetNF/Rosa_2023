<?php
require_once "/var/www/html/projetoFinal/src/utils/utils.php";
    class NotificacaoDao
    {
         private PDO $conexao; 

         public function __construct(PDO $conexao){
             $this->conexao = $conexao;
         }

         public function inserir(Notificacao $notificacao)
         {
            $sqlInsert = null;
            try{
                    $sqlInsert = "insert into notificacao (id_data_sus,
                                        data_notificacao,
                                        id_estado,
                                        id_municipio,
                                        id_dados_ocupacao,
                                        excluida,
                                        validado,
                                        created_at,
                                        updated_at,
                                        id_paciente)
                                            values( :id_data_sus,
                                            :data_notificacao,
                                            :id_estado,
                                            :id_municipio,
                                            :id_dados_ocupacao,
                                            :excluida,
                                            :validado,
                                            :created_at,
                                            :updated_at,
                                            :id_paciente)";
                    $idEstado = $notificacao->recuperarEstado()->recuperarId();
                    $idMunicipio = $notificacao->recuperarMunicipio()->recuperaId();
                    $idPaciente = $notificacao->recuperarPaciente()->recuperarId();
                    $idDados = $notificacao->recuperarDadosOcupacao()->recuperarId();
                    $validado = ($notificacao->recuperarValidacao() ? 1 : 0);
                    $excluida = ($notificacao->recuperarExcluida() ? 1 : 0);
            
            /*       echo $idEstado . " " . $idMunicipio . " " . $idPaciente . " " . $idDados . " \n"
                    .$notificacao->recuperarIdDataSus(). " " . $notificacao->recuperarDataNotificacao()
                    . " Excluida:" .  $notificacao->recuperarExcluida() . " validado:" . $notificacao->recuperarValidacao()
                    . " " .  $notificacao->recuperarDataCriacao() . " " . $notificacao->recuperarDataAtualizacao() ;
            */     
                    $ps = $this->conexao->prepare($sqlInsert);
                    $ok = $ps->execute(
                        array(
                            "id_data_sus" => $notificacao->recuperarIdDataSus(),
                            "data_notificacao" => $notificacao->recuperarDataNotificacao(),
                            "id_estado" => $idEstado,
                            "id_municipio" => $idMunicipio,
                            "id_dados_ocupacao" => $idDados,
                            "excluida" => $excluida,
                            "validado" => $validado,
                            "created_at" => $notificacao->recuperarDataCriacao(),
                            "updated_at" => $notificacao->recuperarDataAtualizacao(),
                            "id_paciente" => $idPaciente)
                        );
                    
                    if(!$ok){
                        die("\nFalha ao realizar comando de inserção de notificação. ");
                    }
                    if($ps->rowCount()>0){
                        //echo "\nSucesso ao inserir a notificação. ";
                        return true;
                    }
                
                }
                
                catch(PDOException $e){
                    die("\nFalha ao inserir notificação. " . $e->getMessage());
                }
           
         }

         public function buscarNotificacoes(EstadoDao $estadoDao, MunicipioDao $municipioDao, PacienteDAO $pacienteDAO, DadosOcupacaoDao $dadosDao)
         {
             $sqlSelect = null;
             $arrayNotificacoes = array();
             try{
                $sqlSelect = "select id, 
                                id_data_sus,
                                data_notificacao,
                                id_estado,
                                id_municipio,
                                excluida,
                                validado,
                                created_at,
                                updated_at,
                                id_paciente,
                                id_dados_ocupacao
                            from notificacao";
                 $ps = $this->conexao->query($sqlSelect);
                 $linhas = $ps->fetchAll();
                 if(!$ps){
                    die("Falha ao executar comando de buscar notificações. ");
                 }
                 $arrayNotificacoes = popularListaNotificacoes(null,$linhas,$estadoDao,$municipioDao, $pacienteDAO,$dadosDao);
                   
                    //echo "\nSucesso ao buscar por notificações. ";
                   
                    return $arrayNotificacoes;
                 
                 
             }
             catch(PDOException $e){
                die("\nFalha ao realizar buscar por notificações. " . $e->getMessage());
             }
         }

         public function alterarNotificacoes(EstadoDao $estadoDao, MunicipioDao $municipioDao, DadosOcupacaoDao $dadosDao, PacienteDao $pacienteDAO)
         {
            $arrayNotificacoes = [];
            try{
                $arrayNotificacoes = $this->buscarNotificacoes( $estadoDao, $municipioDao, $pacienteDAO, $dadosDao);
                foreach($arrayNotificacoes as $n){
                    $nomeEstado = $n->recuperarEstado()->recuperarNome();
                    //echo "\n".$nomeEstado;
                    $idEstado = $n->recuperarEstado()->recuperarId();
                    
                    $ok = $estadoDao->alterar($idEstado);
                    if(!$ok){
                        die("\nFalha ao executar comando de alterar estado da notificacao.");
                    }
                    //echo "\nSucesso ao alterar estado da notificacao";

                }
                return true;
            }
            catch(PDOException $e){
                die("\nFalha ao atualizar estado da notificação. " . $e->getMessage());
            }
                
         }

         public function deletarNotificacoes (EstadoDao $estadoDao, MunicipioDao $municipioDao, PacienteDao $pacienteDao, DadosOcupacaoDao $dadosDao)
         {
            $sqlDelete = null;
            $arrayNotificacoes = [];
            try{
                $arrayNotificacoes = $this->buscarNotificacoes( $estadoDao, $municipioDao, $pacienteDao, $dadosDao);
                
                foreach($arrayNotificacoes as $n){
                    $idNotificacao = $n->recuperarId();
                    $sqlDelete = "DELETE from notificacao
                                        where id_estado in(
                                            select e.id
                                            from estado e
                                            where e.nome like 
                                            'Alagoas')
                                        AND id = :idNotificacao";
                    $ps = $this->conexao->prepare($sqlDelete);
                    $ps->bindParam('idNotificacao', $idNotificacao, PDO::PARAM_INT);
                    $ok =$ps->execute();
                    
                     
                    if(!$ok){
                        die("\nFalha ao executar comando para deletar notificações");
                    }
                    if($ps->rowCount()>0){
                        $estadoDao->deletar($n->recuperarEstado()->recuperarId());
                        $municipioDao->deletar($n->recuperarMunicipio()->recuperaId());
                        $pacienteDao->deletar($n->recuperarPaciente()->recuperarId());
                        $dadosDao->deletar($n->recuperarDadosOcupacao()->recuperarId());
                        //echo "\nSucesso ao executar comando para deletar notificacao. ";

                    }
                    
                
                }
                return true;
            }
            catch(PDOException $e){
                die("\nFalha ao deletar notificações. " .$e->getMessage());
            }
                   
         }

       
       
/*
     public function alterarNotificacoesCifradasMariaDBAes(string $data, string $key, int $limite )
     {
        $sqlUpdate = null;
        try{
            $sqlUpdate = "UPDATE notificacao set data_notificacao = AES_ENCRYPT(:dataN, :chave),
                            updated_at = AES_ENCRYPT(:dataN, :chave)
                        where id in (select id from ( select id from notificacao ORDER BY id ASC limit :limite) tmp )";
            $ps = $this->conexao->prepare($sqlUpdate);
            $ps->bindParam('dataN', $data, PDO::PARAM_STR);
            $ps->bindParam('chave', $key, PDO::PARAM_STR);
            $ps->bindParam('limite', $limite, PDO::PARAM_INT);
            $ok = $ps->execute();
            if(!$ok){
                die("\nFalha ao executar comando de atualizar notificação cifrada com AES no MariaDB");
            }
            echo"\nSucesso ao atualizar notificacao cifrada com AES no MariaDB";
            return true;
        }
        catch(PDOException $e){
            die("\nFalha ao alterar notificação cifrada com AES no MariaDB. " .$e->getMessage());
        }

     }
    */

    

        

   
         
/*
     public function alterarEstadoNotificacoesCifradasPostgreSql( string $algoritmo, string $key, int $limite)
     {
        $sqlUpdate = null;
        $strAlgo = "cipher-algo=".$algoritmo;
        try{
            $sqlUpdate = "UPDATE estado 
                            set nome = pgp_sym_encrypt('São Paulo', :key, :strAlgo)
                            from notificacao
                            where notificacao.id_estado = estado.id
                            and estado.id in
                                    ( select id_estado from (
                                                select id, id_estado from notificacao
                                                ORDER BY id ASC
                                                limit :limite
                                                ) tmp
                                            )";
            
            $ps = $this->conexao->prepare($sqlUpdate);
            $ps->bindParam('key', $key, PDO::PARAM_STR);
            $ps->bindParam('strAlgo', $strAlgo, PDO::PARAM_STR);
            $ps->bindParam('limite', $limite, PDO::PARAM_INT);
            $ok = $ps->execute ();
            if(!$ok){
                die("\nFalha ao executar comando de atualizar estado da notificação cifrada com " .$algoritmo. " no postgreSQL");
            }
            echo "\nSucesso ao atualizar estado ligado à notificação cifrada com " .$algoritmo. " no postgreSQL";
        }
        catch(PDOException $e){
            die("\nFalha ao atualizar estado da notificação cifrada com " . $algoritmo. " no postgreSQL." . $e->getMessage());
        }

     }
*/

     private function popularNotificacao( string $key, array $linha, EstadoDaoPostgreSql $estadoDao, MunicipioDaoPostgreSql $municipioDao, PacienteDaoPostgreSql $pacienteDAO, DadosOcupacaoDaoPostgreSql $dadosDao)
     {
        $notificacao = null;
        foreach($linha as $l){
            $notificacao = new Notificacao($l[1],
                            $l[2],
                            $estadoDao->buscarPorId($l[3], $key),
                            $municipioDao->buscarPorId($l[4], $key),
                            $l[5],
                            $l[6],
                            $l[7],
                            $l[8],
                            $dadosDao->buscarPorId($l[10], $key),
                            $pacienteDAO->buscarPorId($l[9],$key),
                            $l[0]
                         );
        }
        return $notificacao;
     }

     /*private function popularListaNotificacoes( string $key, array $linhas, EstadoDao $estadoDao, MunicipioDao $municipioDao, PacienteDAO $pacienteDAO, DadosOcupacaoDao $dadosDao)
     {
        $arrayNotificacoes = [];
        foreach($linhas as $l){
            $notificacao = new Notificacao($l[1],
                            $l[2],
                            $estadoDao->buscarCifradoPorId($l[3], $key),
                            $municipioDao->buscarCifradoPorId($l[4], $key),
                            $l[5],
                            $l[6],
                            $l[7],
                            $l[8],
                            $dadosDao->buscarCifradoPorId($l[10], $key),
                            $pacienteDAO->buscarCifradoPorId($l[9], $key),
                            $l[0]
                         );
            $arrayNotificacoes [] = $notificacao;
        }
        return $arrayNotificacoes;
     }*/
    
    }
?>





