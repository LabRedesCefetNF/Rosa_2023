<?php
set_include_path('/var/www/html/projetoFinal/src/interfaces/');

require_once 'InterfacePDO.php';
use interfaces\InterfacePDO;

    class DadosOcupacaoDaoPostgreSql implements InterfacePDO
    {
        private $conexao = null;

        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        } 
        
        public function inserirCifrado(DadosOcupacao $dados, $chave)
        {
            $sqlInsert = null;
            $strAlgo = "aes-cbc";
            try{
                $sqlInsert = "INSERT into dados_ocupacao (cnes,
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
                                                            encrypt_iv(:cnes, :key, '0123456789123456', :algoritmo),
                                                            encrypt_iv(:ocupacao_suspeito_cli, :key, '0123456789123456', :algoritmo),
                                                            encrypt_iv(:ocupacao_suspeito_uti, :key, '0123456789123456', :algoritmo),
                                                            encrypt_iv(:ocupacao_confirmado_cli, :key, '0123456789123456', :algoritmo),
                                                            encrypt_iv(:ocupacao_confirmado_uti, :key, '0123456789123456', :algoritmo),
                                                            encrypt_iv(:ocupado_covid_uti, :key, '0123456789123456', :algoritmo),
                                                            encrypt_iv(:ocupado_covid_cli, :key, '0123456789123456', :algoritmo),
                                                            encrypt_iv(:ocupacao_hospitalar_uti, :key, '0123456789123456', :algoritmo),
                                                            encrypt_iv(:ocupacao_hospitalar_cli, :key, '0123456789123456', :algoritmo),
                                                            encrypt_iv(:saida_suspeita_obitos, :key, '0123456789123456', :algoritmo),
                                                            encrypt_iv(:saida_suspeita_altas, :key, '0123456789123456', :algoritmo),
                                                            encrypt_iv(:saida_confirmada_obitos, :key, '0123456789123456', :algoritmo),
                                                            encrypt_iv(:saida_confirmada_altas, :key, '0123456789123456', :algoritmo))";
                $ps = $this->conexao->prepare($sqlInsert);
                $ok = $ps->execute(
                    array(
                        "key" => $chave,
                        "algoritmo" => $strAlgo,
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
                        "saida_suspeita_altas"=> $dados->recuperarSaidaSuspeitaAltas(),
                        "saida_confirmada_obitos" => $dados->recuperarSaidaConfirmadaObitos(),
                        "saida_confirmada_altas" => $dados->recuperarSaidaConfirmadaAltas()
                        )
                    );
                if(!$ok){
                    die("\nFalha ao executar comando de inserir dados de ocupação cifrado AES modo CBC no postgreSQL.");
                }
                //echo "\nSucesso ao inserir dados de ocupação cifrado AES modo CBC no postgreSQL.";
                return true;
            }
            catch(PDOException $e){
                die("\nFalha ao inserir dados de ocupação cifrado com AES modo CBC no postgreSQL. " . $e->getMessage());
            }

        }

        public function buscarUltimo(string $chave = null)
        {
            $sqlSelect = null;
            $strAlgo = "aes-cbc";
            $dados = null;
            try{
                $sqlSelect = "SELECT id, 
                            CONVERT_FROM(decrypt_iv(cnes::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as cnes,
                            CONVERT_FROM(decrypt_iv(ocupacao_suspeito_cli::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupacao_suspeito_cli,
                            CONVERT_FROM(decrypt_iv(ocupacao_suspeito_uti::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupacao_suspeito_uti,
                            CONVERT_FROM(decrypt_iv(ocupacao_confirmado_cli::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupacao_confirmado_cli,
                            CONVERT_FROM(decrypt_iv(ocupacao_confirmado_uti::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupacao_confirmado_uti,
                            CONVERT_FROM(decrypt_iv(ocupado_covid_uti::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupado_covid_uti,
                            CONVERT_FROM(decrypt_iv(ocupado_covid_cli::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupado_covid_cli,
                            CONVERT_FROM(decrypt_iv(ocupacao_hospitalar_uti::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupado_covid_uti,
                            CONVERT_FROM(decrypt_iv(ocupacao_hospitalar_cli::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupado_covid_cli,
                            CONVERT_FROM(decrypt_iv(saida_suspeita_obitos::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as saida_suspeita_obitos,
                            CONVERT_FROM(decrypt_iv(saida_suspeita_altas::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as saida_suspeita_altas,
                            CONVERT_FROM(decrypt_iv(saida_confirmada_obitos::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as saida_confirmada_obitos,
                            CONVERT_FROM(decrypt_iv(saida_confirmada_altas::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as saida_confirmada_altas
                                from dados_ocupacao
                                where id = (SELECT max(id) from dados_ocupacao)"; 
                                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave,
                        "algoritmo" => $strAlgo
                    )
                    );
                if(!$ok){
                    die("\nFalha ao executar comando para buscar último registro de dados de ocupação cifrado com AES modo CBC no postgreSQL. ");
                }
                $linha = $ps->fetchAll();
                $dados = $this->popularDadoOcupacao($linha);

                //echo "\nSucesso ao buscar último registro de dados de ocupação cifrado com AES modo CBC no postgreSQL. ";
                return $dados;
            }
            catch(PDOException $e){
                die("\nFalha ao buscar último registro de dados de ocupação cifrado com AES modo CBC no postgreSQL. " . $e->getMessage());

            }
        }

        public function buscarPorId(int $id, string $chave = null)
        {
            $sqlSelect = null;
            $strAlgo = "aes-cbc";
            $dados = null;
            try{
                $sqlSelect = "SELECT id, 
                    CONVERT_FROM(decrypt_iv(cnes::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as cnes,
                    CONVERT_FROM(decrypt_iv(ocupacao_suspeito_cli::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupacao_suspeito_cli,
                    CONVERT_FROM(decrypt_iv(ocupacao_suspeito_uti::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupacao_suspeito_uti,
                    CONVERT_FROM(decrypt_iv(ocupacao_confirmado_cli::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupacao_confirmado_cli,
                    CONVERT_FROM(decrypt_iv(ocupacao_confirmado_uti::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupacao_confirmado_uti,
                    CONVERT_FROM(decrypt_iv(ocupado_covid_uti::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupado_covid_uti,
                    CONVERT_FROM(decrypt_iv(ocupado_covid_cli::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupado_covid_cli,
                    CONVERT_FROM(decrypt_iv(ocupacao_hospitalar_uti::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupado_covid_uti,
                    CONVERT_FROM(decrypt_iv(ocupacao_hospitalar_cli::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as ocupado_covid_cli,
                    CONVERT_FROM(decrypt_iv(saida_suspeita_obitos::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as saida_suspeita_obitos,
                    CONVERT_FROM(decrypt_iv(saida_suspeita_altas::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as saida_suspeita_altas,
                    CONVERT_FROM(decrypt_iv(saida_confirmada_obitos::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as saida_confirmada_obitos,
                    CONVERT_FROM(decrypt_iv(saida_confirmada_altas::bytea, :key, '0123456789123456', :algoritmo), 'SQL_ASCII') as saida_confirmada_altas
                    from dados_ocupacao
                                where id = :id";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave,
                        "algoritmo" => $strAlgo,
                        "id" => $id
                    )
                    );
                    if(!$ok){
                        die("\nFalha ao executar comando para buscar registro de dados de ocupação cifrado com AES modo CBC no postgreSQL por id. ");
                    }
                    $linha = $ps->fetchall();
                    $dados = $this->popularDadoOcupacao($linha);
                    //echo "\nSucesso ao buscar registro de dados de ocupação cifrado com AES modo CBC por id no postgreSQL. ";
                    return $dados;
                }
                catch(PDOException $e){
                    die("\nFalha ao buscar registro de dados de ocupação cifrado com AES modo CBC no postgreSQL por Id. " . $e->getMessage());
                }           
                
        }

        public function deletar(int $id, string $chave = null)
        {
            $sqlDelete = null;
            try{
                $sqlDelete = "DELETE from dados_ocupacao
                            where id  = :id";
                $ps = $this->conexao->prepare($sqlDelete);
                $ps->bindParam('id', $id, PDO::PARAM_INT);
                $ok = $ps->execute();
                if(!$ok){
                    die("\nFalha ao deletar dados_ocupacao cifrado com AES no postgreSQL");
                }
                //echo "\Sucesso ao deletar dados_ocupacao cifrado com AES no postgreSQL";
            }
            catch(PDOException $e){
                die("\nFalha ao deletar dados_ocupacao cifrado com AES no PostgreSQL. " .$e->getMessage());
            }
        }


        private function popularDadoOcupacao(array $linha)
        {
            $dados = null;
            foreach($linha as $l){
                $dados = new DadosOcupacao(
                    $l[1],
                    $l[2],
                    $l[3],
                    $l[4],
                    $l[5],
                    $l[6],
                    $l[7],
                    $l[8],
                    $l[9],
                    $l[10],
                    $l[11],
                    $l[12],
                    $l[13],
                    $l[0]
                );
                return $dados;
        }
     }

    }

?>