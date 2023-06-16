<?php
require_once '/var/www/html/projetoFinal/src/utils/utils.php';

    class NotificacaoDaoMariaDB 
    {
        private $conexao = null;

        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        }

        public function inserirCifrado(Notificacao $notificacao, string $chave)
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
                    values( AES_ENCRYPT(:id_data_sus, :key),
                    AES_ENCRYPT(:data_notificacao, :key),
                    :id_estado, 
                    :id_municipio, 
                    :id_dados_ocupacao,
                    :excluida,
                    :validado, 
                    AES_ENCRYPT(:created_at, :key),
                    AES_ENCRYPT(:updated_at, :key),
                    :id_paciente)";

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
                        "id_dados_ocupacao" => $idDados,
                        "excluida" => $excluida,
                        "validado" => $validado,
                        "created_at" => $notificacao->recuperarDataCriacao(),
                        "updated_at" => $notificacao->recuperarDataAtualizacao(),
                        "id_paciente" => $idPaciente,
                        "key" => $chave
                    )
                );
                if(!$ok){
                    
                    die("\nFalha ao executar comando para inserção de notificação cifrada com AES no MariaDB. ");
                }
                if($ps->rowCount()>0){
                    //echo "\nSucesso ao inserir notificação cifrada com AES no MariaDB. ";
                    return true;
                }
                
            }
            catch(PDOException $e){
                die("\nFalha ao inserir notificação cifrada com AES no MariaDB. " . $e->getMessage());
            }
        }

       public function buscarNotificacoesCifradas (string $chave, EstadoDaoMariaDB $estadoDao, MunicipioDaoMariaDB $municipioDao, PacienteDAOMariaDB $pacienteDAO, DadosOcupacaoDaoMariaDB $dadosDao)
       {
            $sqlSelect = null;
            $arrayNotificacoes = array();
            try{
                $sqlSelect = "select id, 
                    AES_DECRYPT(id_data_sus, :key) as id_data_sus,
                    AES_DECRYPT(data_notificacao, :key) as data_notificacao,
                    id_estado,
                    id_municipio,
                    excluida,
                    validado,
                    AES_DECRYPT(created_at, :key) as created_at,
                    AES_DECRYPT(updated_at, :key) as updated_at,
                    id_paciente, 
                    id_dados_ocupacao
                    from notificacao ";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave
                    )
                );
                if(!$ok){
                    die("\nFalha ao executar comando de buscar por notificações cifradas com AES no MariaDB");
                }
                if($ps->rowCount()>0){
                    $linhas = $ps->fetchAll();
                    $arrayNotificacoes = popularListaNotificacoes($chave, $linhas, $estadoDao,$municipioDao, $pacienteDAO, $dadosDao);
                    //echo "\nSucesso ao buscar notificações cifradas com AES no MariaDB. ";
                    return $arrayNotificacoes;
                }

            }
            catch(PDOException $e){
                die("Falha ao realizar busca por notificações cifradas com AES no MariaDB. " . $e->getMessage());
            }
       }

       public function deletarNotificacoesCifradas(string $chave, EstadoDaoMariaDB $estadoDao, MunicipioDaoMariaDB $municipioDao, PacienteDAOMariaDB $pacienteDao, DadosOcupacaoDaoMariaDB $dadosDao)
       {
            $sqlDelete = null;
            $arrayNotificacoes = [];
            $alagoas = 'Alagoas';
            try{
                $arrayNotificacoes = $this->buscarNotificacoesCifradas($chave, $estadoDao, $municipioDao, $pacienteDao, $dadosDao);
                
                foreach($arrayNotificacoes as $n){
                    $idNotificacao = $n->recuperarId();
                    $sqlDelete = "DELETE from notificacao
                                        where id_estado in(
                                            select e.id
                                            from estado e
                                            where e.nome like 
                                            AES_ENCRYPT( :nomeEstado, :key ))
                                        AND id = :idNotificacao";
                    $ps = $this->conexao->prepare($sqlDelete);
                    $ps->bindParam('key', $chave, PDO::PARAM_STR);
                    $ps->bindParam('nomeEstado', $alagoas, PDO::PARAM_STR);
                    $ps->bindParam('idNotificacao', $idNotificacao, PDO::PARAM_INT);
                    $ok =$ps->execute();
                    if(!$ok){
                        die("\nFalha ao executar comando para deletar notificações no MariaDB");
                    }
                    if($ps->rowCount()>0){
                        $estadoDao->deletar($n->recuperarEstado()->recuperarId());
                        $municipioDao->deletar($n->recuperarMunicipio()->recuperaId());
                        $pacienteDao->deletar($n->recuperarPaciente()->recuperarId());
                        $dadosDao->deletar($n->recuperarDadosOcupacao()->recuperarId());
                    // echo "\nSucesso ao executar comando para deletar notificacao cifrada no Firebird. ";

                    }
                    
                
                }
                return true;
            }
            catch(PDOException $e){
                die("\nFalha ao deletar notificações cifradas com AES no MariaDB. " .$e->getMessage());
            }  
                    
       }

       public function alterarEstadoNotificacoesCifradas(string $chave, EstadoDaoMariaDB $estadoDao, MunicipioDaoMariaDB $municipioDao, DadosOcupacaoDaoMariaDB $dadosDao, PacienteDAOMariaDB $pacienteDAO)
        {
           
            $arrayNotificacoes = [];
            try{
                $arrayNotificacoes = $this->buscarNotificacoesCifradas($chave, $estadoDao, $municipioDao, $pacienteDAO, $dadosDao);
                foreach($arrayNotificacoes as $n){
                    $nomeEstado = $n->recuperarEstado()->recuperarNome();
                    //echo "\n".$nomeEstado;
                    $idEstado = $n->recuperarEstado()->recuperarId();
                    
                    $ok = $estadoDao->alterar($chave,$idEstado);
                    if(!$ok){
                        die("\nFalha ao executar comando de alterar estado cifrado da notificacao no MariaDB.");
                    }
                    //echo "\nSucesso ao alterar estado da notificacao no Firebird";

                }
                return true;
            }
            catch(PDOException $e){
                die("\nFalha ao atualizar estado cifrado da notificação no MariaDB. " . $e->getMessage());
            }

        }
    }
?>