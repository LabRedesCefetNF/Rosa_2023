<?php

require_once "../../dao/firebird/EstadoDaoFirebird.php";
require_once "../../dao/firebird/MunicipioDaoFirebird.php";
require_once "../../dao/firebird/NotificacaoDaoFirebird.php";
require_once "../../dao/firebird/PacienteDaoFirebird.php";
require_once "../../dao/firebird/DadosOcupacaoDaoFirebird.php";
require_once "../../model/Estado.php";
require_once "../../model/Municipio.php";
require_once "../../model/Paciente.php";
require_once "../../model/DadosOcupacao.php";
require_once "../../model/Notificacao.php";
require_once "../../service/ServiceArquivo.php";
require_once "../../dao/ConnectionCreatorFirebird.php";

$conexao = ConnectionCreatorFirebird::createConnection();
$cntArquivo = new ServiceArquivo("../../LeitoOcupacao_2021.csv");
$quantidadeLinhas = $cntArquivo->retornaQtdLinhas("../../../config/rounds.conf");
$arrayLinhas = $cntArquivo->retornaConteudoCsv($quantidadeLinhas);
$arrayNotificacao = array();
$key = "AbcdAbcdAbcdAbcd";

$estadoDao = new EstadoDaoFirebird($conexao);
$municipioDao = new MunicipioDaoFirebird($conexao);
$dadosDao = new DadosOcupacaoDaoFirebird($conexao);
$pacienteDao = new PacienteDaoFirebird($conexao);
$notificacaoDao = new NotificacaoDaoFirebird($conexao);
$tudoOk = false;
echo "Iniciando a execução do experimento de Inserção.... (aguarde)";
//$start = microtime(true); //iniciando contador
try{
    $conexao->beginTransaction();
    for ($i = 1; $i <= $quantidadeLinhas; $i++){
        $estado = new Estado($arrayLinhas[$i]["estado"]);
        $okE = $estadoDao->inserirCifrado($estado, $key);
        $estado = $estadoDao->buscarUltimo($key);
        $idEstado = $estado->recuperarId();
        $municipio = new Municipio($arrayLinhas[$i]["municipio"], $idEstado);
        $okM = $municipioDao->inserirCifrado($municipio, $key);
        $municipio = $municipioDao->buscarUltimo($key);
        $paciente = new Paciente($arrayLinhas[$i]["usuario"], $arrayLinhas[$i]["origem"]);
        $okP = $pacienteDao->inserirCifrado($paciente, $key);
        $paciente = $pacienteDao->buscarUltimo($key);
        $dados = new DadosOcupacao(
            $arrayLinhas[$i]["cnes"], $arrayLinhas[$i]["ocupacaoSuspeitoCli"],
            $arrayLinhas[$i]["ocupacaoSuspeitoUti"], $arrayLinhas[$i]["ocupacaoConfirmadoCli"],
            $arrayLinhas[$i]["ocupacaoConfirmadoUti"], $arrayLinhas[$i]["ocupacaoCovidUti"],
            $arrayLinhas[$i]["ocupacaoCovidCli"], $arrayLinhas[$i]["ocupacaoHospitalarUti"],
            $arrayLinhas[$i]["ocupacaoHospitalarCli"], $arrayLinhas[$i]["saidaSuspeitaObitos"],
            $arrayLinhas[$i]["saidaSuspeitaAltas"], $arrayLinhas[$i]["saidaConfirmadaObitos"],
            $arrayLinhas[$i]["saidaConfirmadaAltas"]
        );
        $okD = $dadosDao->inserirCifrado($dados, $key);
        $dados = $dadosDao->buscarUltimo($key);
        //var_dump($arrayLinhas[$i]["excluido"]);
        $notificacao = new Notificacao(
            $arrayLinhas[$i]["id"], $arrayLinhas[$i]["dataNotificacao"],
            $estado,
            $municipio, $arrayLinhas[$i]["excluido"], $arrayLinhas[$i]["validado"],
            $arrayLinhas[$i]["created_at"], $arrayLinhas[$i]["updated_at"],
            $dados,
            $paciente
        );
         $okN =  $notificacaoDao->inserirCifrado($notificacao, $key);
        $tudoOk = $okD && $okE && $okM && $okN && $okP; //conferindo se todos os registros foram inseridos com sucesso
    }
    if ($tudoOk){
        $conexao->commit();
    }
    else{
        echo "falha na transação de inserção de notificação cifrada com AES. ";
        $conexao->rollBack();
    }
}
catch(PDOException $e){
    $conexao->rollBack();
    echo $e->getMessage();
}
$conexao = null;
//$end = microtime(true);
//$tempoGasto = ($end - $start) / 1000000;
//echo "\nTempo gasto: " . $tempoGasto . " segundos. ";
echo "\nFim do experimento de inserção!\n"
  
//var_dump($arrayLinhas);

?>