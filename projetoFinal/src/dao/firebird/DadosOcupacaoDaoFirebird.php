<?php
set_include_path('/var/www/html/projetoFinal/src/interfaces/');
require_once 'InterfacePDO.php';
use interfaces\InterfacePDO;
    class DadosOcupacaoDaoFirebird implements InterfacePDO
    {
        private $conexao = null;

        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        }

        public function inserirCifrado(DadosOcupacao $dados, string $chave)
        {
            $sqlInsert = null;
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

                VALUES (
                    ENCRYPT(:cnes using aes mode OFB key :chave1 iv '0123456789123456'),
                    ENCRYPT(:ocupacao_suspeito_cli using aes mode OFB key :chave2 iv '0123456789123456'),
                    ENCRYPT(:ocupacao_suspeito_uti using aes mode OFB key :chave3 iv '0123456789123456'),
                    ENCRYPT(:ocupacao_confirmado_cli using aes mode OFB key :chave4 iv '0123456789123456'),
                    ENCRYPT(:ocupacao_confirmado_uti using aes mode OFB key :chave5 iv '0123456789123456'),
                    ENCRYPT(:ocupado_covid_uti using aes mode OFB key :chave6 iv '0123456789123456'),
                    ENCRYPT(:ocupado_covid_cli using aes mode OFB key :chave7 iv '0123456789123456'),
                    ENCRYPT(:ocupacao_hospitalar_uti using aes mode OFB key :chave8 iv '0123456789123456'),
                    ENCRYPT(:ocupacao_hospitalar_cli using aes mode OFB key :chave9 iv '0123456789123456'),
                    ENCRYPT(:saida_suspeita_obitos using aes mode OFB key :chave10 iv '0123456789123456'),
                    ENCRYPT(:saida_suspeita_altas using aes mode OFB key :chave11 iv '0123456789123456'),
                    ENCRYPT(:saida_confirmada_obitos using aes mode OFB key :chave12 iv '0123456789123456'),
                    ENCRYPT(:saida_confirmada_altas using aes mode OFB key :chave13 iv '0123456789123456'))";
                 $ps = $this->conexao->prepare($sqlInsert);
                 $ok = $ps->execute(
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
                         "saida_confirmada_altas" => $dados->recuperarSaidaConfirmadaAltas(),
                         "chave1" => $chave,
                         "chave2" => $chave,
                         "chave3" => $chave,
                         "chave4" => $chave,
                         "chave5" => $chave,
                         "chave6" => $chave,
                         "chave7" => $chave,
                         "chave8" => $chave,
                         "chave9" => $chave,
                         "chave10" => $chave,
                         "chave11" => $chave,
                         "chave12" => $chave,
                         "chave13" => $chave

                         
                     )
                     );
                     if(!$ok){
                        die("\nFalha ao executar comando para inserir dados de ocupção cifrada com AES modo OFB no firebird.");
                    }
                   // echo "\nSucesso ao inserir dados de ocupação cifrados com  AES modo OFB no firebird";
                    return true;
                }
                catch(PDOException $e){
                    die("\nFalha ao inserir dados de ocupação cifrados com AES modo OFB no Firebird. " . $e->getMessage());
                }
        }


        public function buscarUltimo (string $chave = null )
        {
            $sqlSelect = null;
            $dados = null;
            try{
                $sqlSelect = "SELECT id, 
                DECRYPT(cnes using aes mode OFB key :chave1 iv '0123456789123456') as cnes,
                DECRYPT(ocupacao_suspeito_cli using aes mode OFB key :chave2 iv '0123456789123456') as ocupacao_suspeito_cli,
                DECRYPT(ocupacao_suspeito_uti using aes mode OFB key :chave3 iv '0123456789123456') as ocupacao_suspeito_uti,
                DECRYPT(ocupacao_confirmado_cli using aes mode OFB key :chave4 iv '0123456789123456') as ocupacao_confirmado_cli,
                DECRYPT(ocupacao_confirmado_uti using aes mode OFB key :chave5 iv '0123456789123456') as ocupacao_confirmado_uti,
                DECRYPT(ocupado_covid_uti using aes mode OFB key :chave6 iv '0123456789123456') as ocupado_covid_uti,
                DECRYPT(ocupado_covid_cli using aes mode OFB key :chave7 iv '0123456789123456') as ocupado_covid_cli,
                DECRYPT(ocupacao_hospitalar_uti using aes mode OFB key :chave8 iv '0123456789123456') as ocupacao_hospitalar_uti,
                DECRYPT(ocupacao_hospitalar_cli using aes mode OFB key :chave9 iv '0123456789123456') as ocupacao_hospitalar_cli,
                DECRYPT(saida_suspeita_obitos using aes mode OFB key :chave10 iv '0123456789123456') as saida_suspeita_obitos,
                DECRYPT(saida_suspeita_altas using aes mode OFB key :chave11 iv '0123456789123456') as saida_suspeita_altas,
                DECRYPT(saida_confirmada_obitos using aes mode OFB key :chave12 iv '0123456789123456') as saida_confirmada_obitos,
                DECRYPT(saida_confirmada_altas using aes mode OFB key :chave13 iv '0123456789123456') as saida_confirmada_altas

                    from dados_ocupacao where id = (select max(id) from dados_ocupacao) ";
                $ps = $this->conexao->prepare($sqlSelect);
                $ps->execute(
                    array(
                        "chave1" => $chave,
                        "chave2"=> $chave,
                        "chave3"=> $chave,
                        "chave4"=> $chave,
                        "chave5"=> $chave,
                        "chave6"=> $chave,
                        "chave7"=> $chave,
                        "chave8"=> $chave,
                        "chave9"=> $chave,
                        "chave10"=> $chave,
                        "chave11"=> $chave,
                        "chave12"=> $chave,
                        "chave13"=> $chave
                    )
                );
                if(!$ps){
                    die("\nFalha ao executar comando para buscar último dado de ocupação cifrado com AES modo OFB no Firebird");
                }
                $linha = $ps->fetchAll();
                $dados = $this->popularDadoOcupacao($linha);
               // echo "\nSucesso ao buscar último dado de ocupação cifrado com AES modo OFB.";
                return $dados;

            }
            catch(PDOException $e){
                die("Falha ao buscar último dado de ocupação cifrado com AES modo OFB no firebird");
            }
        }
        
        
        public function buscarPorId(int $id, string $chave = null)
        {
            $sqlSelect = null;
            $dados = null;
            try{
                $sqlSelect = "SELECT id, 
                DECRYPT(cnes using aes mode OFB key :chave1 iv '0123456789123456') as cnes,
                DECRYPT(ocupacao_suspeito_cli using aes mode OFB key :chave2 iv '0123456789123456') as ocupacao_suspeito_cli,
                DECRYPT(ocupacao_suspeito_uti using aes mode OFB key :chave3 iv '0123456789123456') as ocupacao_suspeito_uti,
                DECRYPT(ocupacao_confirmado_cli using aes mode OFB key :chave4 iv '0123456789123456') as ocupacao_confirmado_cli,
                DECRYPT(ocupacao_confirmado_uti using aes mode OFB key :chave5 iv '0123456789123456') as ocupacao_confirmado_uti,
                DECRYPT(ocupado_covid_uti using aes mode OFB key :chave6 iv '0123456789123456') as ocupado_covid_uti,
                DECRYPT(ocupado_covid_cli using aes mode OFB key :chave7 iv '0123456789123456') as ocupado_covid_cli,
                DECRYPT(ocupacao_hospitalar_uti using aes mode OFB key :chave8 iv '0123456789123456') as ocupacao_hospitalar_uti,
                DECRYPT(ocupacao_hospitalar_cli using aes mode OFB key :chave9 iv '0123456789123456') as ocupacao_hospitalar_cli,
                DECRYPT(saida_suspeita_obitos using aes mode OFB key :chave10 iv '0123456789123456') as saida_suspeita_obitos,
                DECRYPT(saida_suspeita_altas using aes mode OFB key :chave11 iv '0123456789123456') as saida_suspeita_altas,
                DECRYPT(saida_confirmada_obitos using aes mode OFB key :chave12 iv '0123456789123456') as saida_confirmada_obitos,
                DECRYPT(saida_confirmada_altas using aes mode OFB key :chave13 iv '0123456789123456') as saida_confirmada_altas
                    from dados_ocupacao where id = :id";
                 $ps = $this->conexao->prepare($sqlSelect);
                 $ps->execute(array(
                     "id" => $id,
                     "chave1" => $chave,
                        "chave2"=> $chave,
                        "chave3"=> $chave,
                        "chave4"=> $chave,
                        "chave5"=> $chave,
                        "chave6"=> $chave,
                        "chave7"=> $chave,
                        "chave8"=> $chave,
                        "chave9"=> $chave,
                        "chave10"=> $chave,
                        "chave11"=> $chave,
                        "chave12"=> $chave,
                        "chave13"=> $chave
                 ));
                 if(!$ps){
                     die("\nFalha ao executar comando de busca de dados de ocupação cifrado com AES modo OFB no firebird.");
                 }
                 $linha = $ps->fetchAll();
                 $dados = $this->popularDadoOcupacao($linha);
                 //echo "\nSucesso ao buscar dados de ocupação cifrado por AES modo OFB no firebird.";
                return $dados;

            }
            catch(PDOException $e){
                die("\nFalha ao buscar dados de ocupação cifrados com AES modo OFB no firebird por id ");
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
                        die("\nFalha ao deletar dados_ocupacao cifrado com AES no Firebird");
                    }
                    //echo "\Sucesso ao deletar dados_ocupacao cifrado com AES no Firebird";
                }
                catch(PDOException $e){
                    die("\nFalha ao deletar dados_ocupacao cifrado com AES no Firebird. " .$e->getMessage());
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