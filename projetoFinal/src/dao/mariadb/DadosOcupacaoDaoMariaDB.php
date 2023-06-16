<?php
set_include_path('/var/www/html/projetoFinal/src/interfaces/');

require_once 'InterfacePDO.php';
require_once '/var/www/html/projetoFinal/src/utils/utils.php';

use interfaces\InterfacePDO;

    class DadosOcupacaoDaoMariaDB implements InterfacePDO
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
                    AES_ENCRYPT(:cnes, :key), 
                    AES_ENCRYPT(:ocupacao_suspeito_cli, :key), 
                    AES_ENCRYPT(:ocupacao_suspeito_uti, :key),
                    AES_ENCRYPT(:ocupacao_confirmado_cli, :key), 
                    AES_ENCRYPT(:ocupacao_confirmado_uti, :key),
                    AES_ENCRYPT(:ocupado_covid_uti, :key), 
                    AES_ENCRYPT(:ocupado_covid_cli, :key), 
                    AES_ENCRYPT(:ocupacao_hospitalar_uti, :key), 
                    AES_ENCRYPT(:ocupacao_hospitalar_cli, :key),
                    AES_ENCRYPT(:saida_suspeita_obitos, :key), 
                    AES_ENCRYPT(:saida_suspeita_altas, :key),
                    AES_ENCRYPT(:saida_confirmada_obitos, :key), 
                    AES_ENCRYPT(:saida_confirmada_altas, :key))";
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
                    "key" => $chave
                    )
                );  
            if(!$ok){
                die("Falha ao executar o comando para inserir o registro de dados de ocupação cifrado. ");
            }
            if($ps->rowCount()>0){
                //echo "Sucesso ao inserir dados de ocupação cifrados com AES. ";
                return true;
            }
            }
            catch(PDOException $e){
                 die("Falha ao inserir dados de ocupação cifrado com AES. " . $e->getMessage());
            }
        }
        public function buscarUltimo(string $chave = null)
        {
            $sqlSelect = null;
            $dadosOcupacao = null;
            try{
                $sqlSelect = "select id, 
                    AES_DECRYPT(cnes, :key) as cnes, 
                    AES_DECRYPT(ocupacao_suspeito_cli, :key) as ocupacao_suspeito_cli, 
                    AES_DECRYPT(ocupacao_suspeito_uti, :key) as ocupacao_suspeito_uti, 
                    AES_DECRYPT(ocupacao_confirmado_cli, :key) as ocupacao_confirmado_cli, 
                    AES_DECRYPT(ocupacao_confirmado_uti, :key) as ocupacao_confirmado_uti, 
                    AES_DECRYPT(ocupado_covid_uti, :key) as ocupado_covid_uti, 
                    AES_DECRYPT(ocupado_covid_cli, :key) as ocupado_covid_cli, 
                    AES_DECRYPT(ocupacao_hospitalar_uti, :key) as ocupacao_hospitalar_uti, 
                    AES_DECRYPT(ocupacao_hospitalar_cli, :key) as ocupacao_hospitalar_cli, 
                    AES_DECRYPT(saida_suspeita_obitos, :key) as saida_suspeita_obitos, 
                    AES_DECRYPT(saida_suspeita_altas, :key) as saida_suspeita_altas, 
                    AES_DECRYPT(saida_confirmada_obitos, :key) as saida_confirmada_obitos, 
                    AES_DECRYPT(saida_confirmada_altas, :key) as saida_confirmada_altas
                        from dados_ocupacao where id = (select max(id) from dados_ocupacao) ";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave
                    )
                );
                if(!$ok){
                    die("Falha ao executar comando de busca por último registro de dados de ocupação cifrado com AES. ");
                }
                if($ps->rowCount()>0){
                    $linhas = $ps->fetchAll();
                    $dadosOcupacao = popularDadoOcupacao($linhas);
                    
                    //echo "Sucesso ao buscar último registro de dados de ocupação cifrado com AES inserido. ";
                    return $dadosOcupacao;

                 }
                }
                catch(PDOException $e){
                    die("Falha ao buscar último registro de dados de ocupação cifrado com AES. " . $e->getMessage());
                }
        }

        public function buscarPorId(int $id, string $chave = null)
        {
            $sqlSelect = null;
            try{
                $sqlSelect = "select id, AES_DECRYPT(cnes, :key) as cnes, 
                AES_DECRYPT(ocupacao_suspeito_cli, :key) as ocupacao_suspeito_cli, 
                AES_DECRYPT(ocupacao_suspeito_uti, :key) as ocupacao_suspeito_uti, 
                AES_DECRYPT(ocupacao_confirmado_cli, :key) as ocupacao_confirmado_cli, 
                AES_DECRYPT(ocupacao_confirmado_uti, :key) as ocupacao_confirmado_uti, 
                AES_DECRYPT(ocupado_covid_uti, :key) as ocupado_covid_uti, 
                AES_DECRYPT(ocupado_covid_cli, :key) as ocupado_covid_cli, 
                AES_DECRYPT(ocupacao_hospitalar_uti, :key) as ocupacao_hospitalar_uti, 
                AES_DECRYPT(ocupacao_hospitalar_cli, :key) as ocupacao_hospitalar_cli, 
                AES_DECRYPT(saida_suspeita_obitos, :key) as saida_suspeita_obitos, 
                AES_DECRYPT(saida_suspeita_altas, :key) as saida_suspeita_altas, 
                AES_DECRYPT(saida_confirmada_obitos, :key) as saida_confirmada_obitos, 
                AES_DECRYPT(saida_confirmada_altas, :key) as saida_confirmada_altas
                    from dados_ocupacao
                    where id = :id";
                $ps = $this->conexao->prepare($sqlSelect);
                $ok = $ps->execute(
                    array(
                        "key" => $chave,
                        "id" => $id
                    )
                );
                if(!$ok){
                    die("Falha ao executar comando de busca de dados de ocupação cifrado com AES por id. ");

                }
                if($ps->rowCount()>0){
                    $linha = $ps->fetchAll();
                    $dadosOcupacao = popularDadoOcupacao($linha);
                    //echo "Sucesso ao buscar por dados de ocupação cifrado com AES por id. ";
                    return $dadosOcupacao;
                }
            }
            catch(PDOException $e){
                die("Falha ao buscar dados de ocupação cifrados com AES por ID. ");
            }

        }

        public function deletar (int $id, string $chave = null)
        {
            $sqlDelete = null;
            try{
                $sqlDelete = "DELETE from dados_ocupacao
                            where id  = :id";
                $ps = $this->conexao->prepare($sqlDelete);
                $ps->bindParam('id', $id, PDO::PARAM_INT);
                $ok = $ps->execute();
                if(!$ok){
                    die("\nFalha ao deletar dados de ocupação cifrado com AES no MariaDB");
                }
                //echo "\Sucesso ao deletar dados de ocupação cifrado com AES no MariaDB";
                return true;
            }
            catch(PDOException $e){
                die("\nFalha ao deletar dados de ocupacao cifrado com AES no MariaDB. " .$e->getMessage());
            }
        }
    }
?>