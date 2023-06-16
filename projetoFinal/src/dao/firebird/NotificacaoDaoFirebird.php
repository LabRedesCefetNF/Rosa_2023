<?php

use interfaces\InterfacePDO;

require_once '/var/www/html/projetoFinal/src/utils/utils.php';

    class NotificacaoDaoFirebird 
    {
        private $conexao;

        public function __construct($conexao){
            $this->conexao = $conexao;
        }

        public function inserirCifrado(Notificacao $notificacao, string $chave)
        {
        $sqlInsert = null;
        try{
            $sqlInsert = "INSERT into notificacao (id_data_sus,
                            data_notificacao, id_estado, id_municipio,
                            excluida, validado, created_at, updated_at,
                            id_paciente, id_dados_ocupacao)
                            VALUES (ENCRYPT (:id_data_sus using aes mode OFB key :chave1 iv '0123456789123456'),
                                    ENCRYPT (:data_notificacao using aes mode OFB key :chave2 iv '0123456789123456'),
                                    :id_estado, :id_municipio,
                                    :excluida, :validado,
                                    ENCRYPT (:created_at using aes mode OFB key :chave3 iv '0123456789123456'),
                                    ENCRYPT (:updated_at using aes mode OFB key :chave4 iv '0123456789123456'),
                                    :id_paciente, :id_dados_ocupacao)";
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
                    "chave1" => $chave,
                    "chave2"=> $chave,
                    "chave3"=> $chave,
                    "chave4"=> $chave
                )
                );
                if(!$ok){
                    die("\nFalha ao executar comando de inserir notificação cifrada com AES modo OFB no firebird.  ");
                }
                //echo "\nSucesso ao inserir notificação cifrada com AES modo OFB no firebird.";
                return true;
            }
            catch(PDOException $e){
                die("\nFalha ao inserir notificação cifrada com AES modo OFB no firebird. " .$e->getMessage());
            }
        }

        public function buscarNotificacoesCifradas(string $chave, EstadoDaoFirebird $estadoDao, MunicipioDaoFirebird $municipioDao, DadosOcupacaoDaoFirebird $dadosDao, PacienteDaoFirebird $pacienteDAO)
        {
            $arrayNotificacoes = null;
            $sqlSelect = null;
            $estado = null;
            $municipio = null;
            $paciente = null;
            $dados = null;
            try{
                $sqlSelect = "SELECT id , 
                                DECRYPT(id_data_sus using aes mode OFB key 'AbcdAbcdAbcdAbcd' iv '0123456789123456') as id_data_sus,
                                DECRYPT(data_notificacao using aes mode OFB key 'AbcdAbcdAbcdAbcd' iv '0123456789123456') as data_notificacao,
                                id_estado,
                                id_municipio,
                                excluida, 
                                validado,
                                DECRYPT(created_at using aes mode OFB key 'AbcdAbcdAbcdAbcd' iv '0123456789123456') as created_at,
                                DECRYPT(updated_at using aes mode OFB key 'AbcdAbcdAbcdAbcd' iv '0123456789123456') as updated_at,
                                id_paciente, 
                                id_dados_ocupacao
                                
                                
                                from notificacao";
                $ps = $this->conexao->query($sqlSelect);
                if(!$ps){
                    die("\nFalha ao executar comando para buscar notificações cifradas com AES modo OFB no firebird.");
                }
                $linhas = $ps->fetchAll();
                $arrayNotificacoes = popularListaNotificacoes($chave, $linhas,$estadoDao, $municipioDao, $pacienteDAO, $dadosDao );
                //echo "\nSucesso ao buscar notificações cifradas com AES modo OFB no firebird.";
                return $arrayNotificacoes;
            }
            catch(PDOException $e){
                die("Falha ao buscar notificações cifradas com AES modo OFB no firebird. ". $e->getMessage());
            }
            
         }

         public function deletarNotificacoesCifradas(string $chave, EstadoDaoFirebird $estadoDao, MunicipioDaoFirebird $municipioDao, PacienteDaoFirebird $pacienteDAO, DadosOcupacaoDaoFirebird $dadosDao)
        {
            $sqlDelete = null;
            $arrayNotificacoes = [];
            $alagoas = bin2hex("Alagoas");
            try{
                $arrayNotificacoes = $this->buscarNotificacoesCifradas($chave, $estadoDao, $municipioDao, $dadosDao, $pacienteDAO);
                
                foreach($arrayNotificacoes as $n){
                    $idNotificacao = $n->recuperarId();
                    $idEstado = $n->recuperarEstado()->recuperarId();
                    $sqlDelete = "DELETE from NOTIFICACAO
                                        where id_estado in(
                                            select e.id
                                            from estado e
                                            where e.nome like 
                                            encrypt ( :nomeEstado using aes mode ofb key :key iv '0123456789123456'))
                                        AND id = :idNotificacao";
                    $ps = $this->conexao->prepare($sqlDelete);
                    $ps->bindParam('key', $chave, PDO::PARAM_STR);
                    $ps->bindParam('nomeEstado', $alagoas, PDO::PARAM_STR);
                    $ps->bindParam('idEstado', $idEstado, PDO::PARAM_INT);
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

        public function alterarEstadoNotificacoesCifradas(string $chave, EstadoDaoFirebird $estadoDao, MunicipioDaoFirebird $municipioDao, DadosOcupacaoDaoFirebird $dadosDao, PacienteDaoFirebird $pacienteDAO)
        {
           
            $arrayNotificacoes = [];
            try{
                $arrayNotificacoes = $this->buscarNotificacoesCifradas($chave, $estadoDao, $municipioDao, $dadosDao, $pacienteDAO);
                foreach($arrayNotificacoes as $n){
                    $nomeEstado = $n->recuperarEstado()->recuperarNome();
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
        
    }
?>