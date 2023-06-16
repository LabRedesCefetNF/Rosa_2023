<?php
require_once '/var/www/html/projetoFinal/src/utils/utils.php';


    class NotificacaoDaoPostgreSql 
    {
        private $conexao = null;

        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        }

        public function inserirCifrado(Notificacao $notificacao, string $chave)
        {
            $sqlInsert = null;
            $strAlgo = "aes-cbc";
            try{
                $sqlInsert = "INSERT into notificacao (id_data_sus,
                    data_notificacao,
                    id_estado,
                    id_municipio,
                    excluida,
                    validado,
                    created_at,
                    updated_at,
                    id_paciente,
                    id_dados_ocupacao)
                        values( 
                        encrypt_iv(:id_data_sus, :key, '0123456789123456', :strAlgo),
                        encrypt_iv(:data_notificacao, :key, '0123456789123456', :strAlgo),
                        :id_estado,
                        :id_municipio,
                        :excluida,
                        :validado,
                        encrypt_iv(:created_at, :key, '0123456789123456', :strAlgo),
                        encrypt_iv(:updated_at, :key, '0123456789123456', :strAlgo),
                        :id_paciente,
                        :id_dados_ocupacao)";
                    $idEstado = $notificacao->recuperarEstado()->recuperarId();
                    $idMunicipio = $notificacao->recuperarMunicipio()->recuperaId();
                    $idPaciente = $notificacao->recuperarPaciente()->recuperarId();
                    $idDados = $notificacao->recuperarDadosOcupacao()->recuperarId();
                    $validado = ($notificacao->recuperarValidacao() ? 1 : 0);
                    $excluida = ($notificacao->recuperarExcluida() ? 1 : 0);
                    $ps = $this->conexao->prepare($sqlInsert);
                    $ok = $ps->execute(
                        array(
                            "id_data_sus" => $notificacao->recuperarIdDataSus(),
                            "data_notificacao" => $notificacao->recuperarDataNotificacao(),
                            "id_estado" => $idEstado,
                            "id_municipio" => $idMunicipio,
                            "excluida" => $excluida,
                            "validado" => $validado,
                            "created_at" => $notificacao->recuperarDataCriacao(),
                            "updated_at" => $notificacao->recuperarDataAtualizacao(),
                            "id_paciente" => $idPaciente,
                            "id_dados_ocupacao" => $idDados,
                            "key" => $chave,
                            "strAlgo" => $strAlgo
        
                        )
                    );
                    if(!$ok){
                        die("\nFalha ao executar comando para inserir notificação cifrada com AES modo CBC no postgreSQL");
                    }
                    //echo "\nSucesso ao inserir notificação cifrada com AES modo CBC no postgreSQL";
                    return true;
        
                }
                catch(PDOException $e){
                    die("\nFalha ao inserir notificação cifrada com AES modo CBC no postgreSQL. ". $e->getMessage());
         
                }

        }

        public function buscarUltimaNotificacaoCifrada( string $chave, EstadoDaoPostgreSql $estadoDao, MunicipioDaoPostgreSql $municipioDao, PacienteDaoPostgreSql $pacienteDAO, DadosOcupacaoDaoPostgreSql $dadosDao)
        {
            $sqlSelect = null;
            $strAlgo = "aes-cbc";
            $notificacao = null;
            try{
                $sqlSelect = "SELECT id,
                                CONVERT_FROM(decrypt_iv(id_data_sus::bytea, :key, '0123456789123456', :strAlgo), 'SQL_ASCII') as id_data_sus,
                                CONVERT_FROM(decrypt_iv(data_notificacao::bytea, :key, '0123456789123456', :strAlgo), 'SQL_ASCII') as data_notificacao,
                                id_estado, id_municipio, excluida, validado,
                                CONVERT_FROM(decrypt_iv(created_at::bytea, :key, '0123456789123456', :strAlgo), 'SQL_ASCII') as created_at,
                                CONVERT_FROM(decrypt_iv(updated_at::bytea, :key, '0123456789123456', :strAlgo), 'SQL_ASCII') as updated_at,
                                id_paciente, id_dados_ocupacao 
                                from notificacao
                                where id = (select max(id) from notificacao)";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" =>$chave,
                        "strAlgo" => $strAlgo
                    )
                    );
                if(!$ok){
                    die("\nFalha ao executar comando para buscar última notificação cifrada com AES modo CBC no postgreSQL");
    
                }
                $linha = $ps->fetchAll();
                $notificacao = $this->popularNotificacao( $chave, $linha, $estadoDao, $municipioDao, $pacienteDAO, $dadosDao);
                //echo "\nSucesso ao buscar última notificacao cifrada com AES modo CBC no postgreSQL.";
                return $notificacao;
                }
                catch(PDOException $e){
                    die("\nFalha ao buscar última notificação cifrada com AES modo CBC no postgreSQL. ". $e->getMessage());

                }
        }

        public function buscarNotificacoesCifradas(string $chave, EstadoDaoPostgreSql $estadoDao, MunicipioDaoPostgreSql $municipioDao, PacienteDaoPostgreSql $pacienteDAO, DadosOcupacaoDaoPostgreSql $dadosDao)
        {
            $sqlSelect = null;
            $arrayNotificacoes = null;
            $strAlgo = "aes-cbc";
            try{
                $sqlSelect = "SELECT id, 
                                CONVERT_FROM(decrypt_iv(id_data_sus::bytea, :key, '0123456789123456', :strAlgo), 'SQL_ASCII') as id_data_sus,
                                CONVERT_FROM(decrypt_iv(data_notificacao::bytea, :key, '0123456789123456', :strAlgo), 'SQL_ASCII') as data_notificacao,
                                id_estado, id_municipio, excluida, validado,
                                CONVERT_FROM(decrypt_iv(created_at::bytea, :key, '0123456789123456', :strAlgo), 'SQL_ASCII') as created_at,
                                CONVERT_FROM(decrypt_iv(updated_at::bytea, :key, '0123456789123456', :strAlgo), 'SQL_ASCII') as updated_at,
                                id_paciente, id_dados_ocupacao 
                                from notificacao";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave,
                        "strAlgo" => $strAlgo
                    )
                    );
                    if(!$ok){
                        die("\nFalha ao executar comando para buscar todas notificações cifrada com AES modo CBC no postgreSQL");
        
                    }
                    $linhas = $ps->fetchAll();
                    $arrayNotificacoes = popularListaNotificacoes($chave, $linhas, $estadoDao, $municipioDao, $pacienteDAO,$dadosDao);
                    //echo "\nSucesso ao buscar todas notificações cifradas com AES modo CBC no postgreSQL.";
                    return $arrayNotificacoes;
                }
                catch(PDOException $e){
                    die("\nFalha ao buscar todas as notificações cifradas com AES modo CBC no postgreSQL. ". $e->getMessage());
        
                }
        }

        public function deletarNotificacoesCifradas(string $chave, EstadoDaoPostgreSql $estadoDao, MunicipioDaoPostgreSql $municipioDao, PacienteDaoPostgreSql $pacienteDAO, DadosOcupacaoDaoPostgreSql $dadosDao)
        {
            $sqlDelete = null;
            $arrayNotificacoes = [];
            $strAlgo = "aes-cbc";
            $alagoas = "Alagoas";
            try{
                $arrayNotificacoes = $this->buscarNotificacoesCifradas($chave, $estadoDao, $municipioDao, $pacienteDAO, $dadosDao);
                
                foreach($arrayNotificacoes as $n){
                    $idNotificacao = $n->recuperarId();
                    $idEstado = $n->recuperarEstado()->recuperarId();
                    $sqlDelete = "DELETE from notificacao
                                    WHERE id_estado in(
                                                        select e.id
                                                        from estado e
                                                        where e.nome::bytea like
                                encrypt_iv(:nomeEstado, :key, '0123456789123456', :strAlgo))
                                and id =:idNotificacao";
                    $ps = $this->conexao->prepare($sqlDelete);
                    $ps->bindParam('key', $chave, PDO::PARAM_STR);
                    $ps->bindParam('strAlgo', $strAlgo, PDO::PARAM_STR);
                    $ps->bindParam('nomeEstado', $alagoas, PDO::PARAM_STR);
                    $ps->bindParam('idNotificacao', $idNotificacao, PDO::PARAM_INT);
                    $ok =$ps->execute();
                    if(!$ok){
                        die("\nFalha ao executar comando para deletar notificações no FireBird");
                    }
                    if($ps->rowCount()>0){
                        $estadoDao->deletar($n->recuperarEstado()->recuperarId());
                        $municipioDao->deletar($n->recuperarMunicipio()->recuperaId());
                        $pacienteDAO->deletar($n->recuperarPaciente()->recuperarId());
                        $dadosDao->deletar($n->recuperarDadosOcupacao()->recuperarId());
                       // echo "\nSucesso ao executar comando para deletar notificacao cifrada no Firebird. ";

                    }
                    
                
                }
                return true;
            }
            catch(PDOException $e){
                die("\nFalha ao deletar notificações cifradas com AES no Firebird. " .$e->getMessage());
            }
        }

        public function alterarNotificacoesCifradas(string $chave, EstadoDaoPostgreSql $estadoDao, MunicipioDaoPostgreSql $municipioDao, PacienteDaoPostgreSql $pacienteDao, DadosOcupacaoDaoPostgreSql $dadosDao)
        {
            $arrayNotificacoes = [];
            try{
                $arrayNotificacoes = $this->buscarNotificacoesCifradas($chave, $estadoDao, $municipioDao, $pacienteDao, $dadosDao);
                foreach($arrayNotificacoes as $n){
                    //echo "\n".$nomeEstado;
                    $idEstado = $n->recuperarEstado()->recuperarId();
                    
                    $ok = $estadoDao->alterar($chave,$idEstado);
                    if(!$ok){
                        die("\nFalha ao executar comando de alterar estado cifrado da notificacao.");
                    }
                    //echo "\nSucesso ao alterar estado da notificacao no Firebird";

                }
                return true;
            }
            catch(PDOException $e){
                die("\nFalha ao atualizar estado cifrado da notificação no Firebird. " . $e->getMessage());
            }
        }


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

     
    }

?>