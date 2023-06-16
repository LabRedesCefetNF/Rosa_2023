<?php
set_include_path('/var/www/html/projetoFinal/src/interfaces/');
require_once 'InterfacePDO.php';
require_once "/var/www/html/projetoFinal/src/utils/utils.php";

use interfaces\InterfacePDO;

    class DadosOcupacaoDao implements InterfacePDO{

        private $conexao;
        
        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        }
        
        public function inserir(DadosOcupacao $dados)
        {
            $sqlInsert = null;
            try{
                    $sqlInsert = "insert into dados_ocupacao (cnes, 
                                    ocupacao_suspeito_cli, 
                                    ocupacao_suspeito_uti, 
                                    ocupacao_confirmado_cli, 
                                    ocupacao_confirmado_uti, 
                                    ocupado_covid_uti, 
                                    ocupado_covid_cli, 
                                    ocupacao_hospitalar_uti, 
                                    ocupacao_hospitalar_cli, 
                                    saida_suspeita_obitos, 
                                    saida_suspeita_altas, 
                                    saida_confirmada_obitos, 
                                    saida_confirmada_altas) 
                                            values (
                                            :cnes, 
                                            :ocupacao_suspeito_cli, 
                                            :ocupacao_suspeito_uti,
                                            :ocupacao_confirmado_cli, 
                                            :ocupacao_confirmado_uti,
                                            :ocupado_covid_uti, 
                                            :ocupado_covid_cli, 
                                            :ocupacao_hospitalar_uti, 
                                            :ocupacao_hospitalar_cli,
                                            :saida_suspeita_obitos, 
                                            :saida_suspeita_altas,
                                            :saida_confirmada_obitos, 
                                            :saida_confirmada_altas)";
                    $ps = $this->conexao->prepare($sqlInsert);
                $ps->execute(
                    array(
                        "cnes" => $dados->recuperarCnes(),
                        "ocupacao_suspeito_cli" => $dados->recuperarOcupacaoSuspeitoCli(),
                        "ocupacao_suspeito_uti" => $dados->recuperarOcupacaoSuspeitoUti(),
                        "ocupacao_confirmado_cli" => $dados->recuperarOcupacaoConfirmadoCli(),
                        "ocupacao_confirmado_uti" => $dados->recuperarOcupacaoConfirmadoUti(),
                        "ocupado_covid_uti" => $dados->recuperarOcupacaoCovidUti(),
                        "ocupado_covid_cli" => $dados->recuperarOcupacaoCovidCli(),
                        "ocupacao_hospitalar_uti" => $dados->recuperarOcupacaoHospitalarUti(),
                        "ocupacao_hospitalar_cli" => $dados->recuperarOcupacaoHospitalarCli(),
                        "saida_suspeita_obitos" => $dados->recuperarSaidaSuspeitaObitos(),
                        "saida_suspeita_altas" => $dados->recuperarSaidaSuspeitaAltas(),
                        "saida_confirmada_obitos" => $dados->recuperarSaidaConfirmadaObitos(),
                        "saida_confirmada_altas" => $dados->recuperarSaidaConfirmadaAltas()

                    )
                );
            
                if(!$ps){
                    die("\nFalha ao executar comando para inserir dados de ocupação. ");
                }
               
                    //echo "\nSucesso ao inserir dados da ocupação. ";
                    return true;
               
            }
            catch(PDOException $e){
               die("\nFalha ao inserir dados de ocupação. " . $e->getMessage());
            }
        }

      
             
        public function buscarUltimo(string $chave = null)
        {
             $sqlSelect = null;  
             try{
                 $sqlSelect = "SELECT id, 
                    cnes, 
                    ocupacao_suspeito_cli, 
                    ocupacao_suspeito_uti, 
                    ocupacao_confirmado_cli, 
                    ocupacao_confirmado_uti, 
                    ocupado_covid_uti, 
                    ocupado_covid_cli, 
                    ocupacao_hospitalar_uti, 
                    ocupacao_hospitalar_cli, 
                    saida_suspeita_obitos, 
                    saida_suspeita_altas, 
                    saida_confirmada_obitos, 
                    saida_confirmada_altas
                        from dados_ocupacao
                        where id = (select max(id) from dados_ocupacao)";
                    $ps = $this->conexao->query($sqlSelect);
                     $linha = $ps->fetchAll();
           // var_dump($linha);
                     
                        $dadosOcupacao = popularDadoOcupacao($linha);
                          
                        
                        
                         //echo "Sucesso ao retornar último registro de dados de ocupação inserido. ";
                         return $dadosOcupacao;     
                        
                        if(!$ps){
                              die("\nFalha ao executar comando de busca pelo último registro de dados de ocupação inserido. ");
                        }
                }  
             catch(PDOException $e){
                die("\nFalha ao realizar busca pelo último dado de ocupação inserido. " . $e->getMessage());
             }                                          
        }

        public function buscarPorId(int $id, string $chave = null)
        {
            $sqlSelect = null;
            $dadosOcupacao = null;
            try{
              $sqlSelect = "SELECT
                id,
                 cnes, 
                ocupacao_suspeito_cli, 
                ocupacao_suspeito_uti, 
                ocupacao_confirmado_cli, 
                ocupacao_confirmado_uti, 
                ocupado_covid_uti, 
                ocupado_covid_cli, 
                ocupacao_hospitalar_uti, 
                ocupacao_hospitalar_cli, 
                saida_suspeita_obitos, 
                saida_suspeita_altas, 
                saida_confirmada_obitos, 
                saida_confirmada_altas
                    from dados_ocupacao
                    where id = :id";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "id" => $id
                    )
                );
                if(!$ok){
                    die("\nalha ao executar comando de buscar por dados de ocupação por Id. ");  
                }
               
                    $linha = $ps->fetchAll();
                    $dadosOcupacao = popularDadoOcupacao($linha);
                    //echo "\nSucesso ao buscar dados de ocupação por Id. ";
                    return $dadosOcupacao;
                
            }
            catch(PDOException $e){
                die("\nFalha ao buscar dados de ocupação por Id. " . $e->getMessage());
            }
        } 

        public function deletar(int $id, string $chave = null)
        {
            $sqlDelete = null;
            try{
                $sqlDelete = "DELETE from dados_ocupacao
                                where id = :id";
                $ps = $this->conexao->prepare($sqlDelete);
                $ps->bindParam('id', $id, PDO::PARAM_INT);
                $ok = $ps->execute();
                if(!$ok){
                    die("\nFalha ao executar comando para deletar dados de ocupacao. ");
                }
                //echo "\nSucesso ao executar comando para deletar dados de ocupacao.";
                return true;

            }
            catch(PDOException $e){
                die("\nFalha ao deletar dados de ocupacao. " .$e->getMessage());
            }
        }

    }
?>