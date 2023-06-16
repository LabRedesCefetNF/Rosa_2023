<?php

require_once "../../dao/mariadb/EstadoDaoMariaDB.php";
require_once "../../dao/mariadb/MunicipioDaoMariaDB.php";
require_once "../../dao/mariadb/NotificacaoDaoMariaDB.php";
require_once "../../dao/mariadb/PacienteDaoMariaDB.php";
require_once "../../dao/mariadb/NotificacaoDaoMariaDB.php";
require_once "../../dao/mariadb/DadosOcupacaoDaoMariaDB.php";
require_once "../../model/Estado.php";
require_once "../../model/Municipio.php";
require_once "../../model/Paciente.php";
require_once "../../model/DadosOcupacao.php";
require_once "../../model/Notificacao.php";
require_once "../../service/ServiceArquivo.php";
require_once "../../dao/ConnectionCreatorMariaDb.php";

$conexao = ConnectionCreatorMariaDB::createConnection();
$cntArquivo = new ServiceArquivo("../../LeitoOcupacao_2021.csv");
$quantidadeLinhas = $cntArquivo->retornaQtdLinhas("../../../config/rounds.conf");
$arrayLinhas = $cntArquivo->retornaConteudoCsv($quantidadeLinhas);
$arrayNotificacao = array();
$key = "AbcdAbcdAbcdAbcd";

$estadoDao = new EstadoDaoMariaDB($conexao);
$municipioDao = new MunicipioDaoMariaDB($conexao);
$dadosDao = new DadosOcupacaoDaoMariaDB($conexao);
$pacienteDao = new PacienteDAOMariaDB($conexao);
$notificacaoDao = new NotificacaoDaoMariaDB($conexao);
$tudoOk = false;
//$start = microtime(true); //iniciando contador
echo "Iniciando a execução do experimento de Inserção.... (aguarde)";
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
/*
$end = microtime(true);
$tempoGasto = ($end - $start) *1000;
echo "Tempo gasto: " . $tempoGasto . " milisegundos. ";
*/
echo "\nFim do experimento de inserção!\n"

  
//var_dump($arrayLinhas);

?>