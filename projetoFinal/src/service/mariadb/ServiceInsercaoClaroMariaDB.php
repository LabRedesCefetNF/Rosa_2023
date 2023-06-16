<?php
require_once '../../dao/EstadoDao.php';
require_once '../../dao/MunicipioDao.php';
require_once '../../dao/PacienteDao.php';
require_once '../../dao/DadosOcupacaoDao.php';
require_once '../../dao/NotificacaoDao.php';
require_once '../../dao/ConnectionCreatorMariaDb.php';
require_once '../../dao/ConnectionCreatorPostgreSQL.php';
require_once "../../model/Estado.php";
require_once "../../model/Municipio.php";
require_once "../../model/Paciente.php";
require_once "../../model/DadosOcupacao.php";
require_once "../../model/Notificacao.php";
require_once "../../service/ServiceArquivo.php";
require_once "../../utils/utils.php";

$cntArquivo = new ServiceArquivo("../../LeitoOcupacao_2021.csv");
$conexao = ConnectionCreatorMariaDB::createConnection();
$quantidadeLinhas = $cntArquivo->retornaQtdLinhas("../../../config/rounds.conf");
$arrayLinhas = $cntArquivo->retornaConteudoCsv($quantidadeLinhas);
$arrayNotificacao = array();
$key = "AbcdAbcdAbcdAbcd";


$estadoDao = new EstadoDao($conexao);
$municipioDao = new MunicipioDao($conexao);
$pacienteDao = new PacienteDAO($conexao);
$dadosDao = new DadosOcupacaoDao($conexao);
$notificacaoDao = new NotificacaoDao($conexao);

$tudoOk = false;
//$start = microtime(true); //iniciando contador
echo "Iniciando a execução do experimento de Inserção.... (aguarde)";
try{
    $conexao->beginTransaction();
    for ($i = 1; $i <= $quantidadeLinhas; $i++){
        $estado = new Estado($arrayLinhas[$i]["estado"]);
        $okE = $estadoDao->inserir($estado);
        $estado = $estadoDao->buscarUltimo();
        $idEstado = $estado->recuperarId();
        $municipio = new Municipio($arrayLinhas[$i]["municipio"], $idEstado);
        $okM = $municipioDao->inserir($municipio);
        $municipio2 = $municipioDao->buscarUltimo();
        $paciente = new Paciente($arrayLinhas[$i]["usuario"], $arrayLinhas[$i]["origem"]);
        $okP = $pacienteDao->inserir($paciente);
        $paciente = $pacienteDao->buscarUltimo();
        $dados = new DadosOcupacao(
            $arrayLinhas[$i]["cnes"], $arrayLinhas[$i]["ocupacaoSuspeitoCli"],
            $arrayLinhas[$i]["ocupacaoSuspeitoUti"], $arrayLinhas[$i]["ocupacaoConfirmadoCli"],
            $arrayLinhas[$i]["ocupacaoConfirmadoUti"], $arrayLinhas[$i]["ocupacaoCovidUti"],
            $arrayLinhas[$i]["ocupacaoCovidCli"], $arrayLinhas[$i]["ocupacaoHospitalarUti"],
            $arrayLinhas[$i]["ocupacaoHospitalarCli"], $arrayLinhas[$i]["saidaSuspeitaObitos"],
            $arrayLinhas[$i]["saidaSuspeitaAltas"], $arrayLinhas[$i]["saidaConfirmadaObitos"],
            $arrayLinhas[$i]["saidaConfirmadaAltas"]
        );
        $okD = $dadosDao->inserir($dados);
        $dados = $dadosDao->buscarUltimo();
        //var_dump($arrayLinhas[$i]["excluido"]);
        $notificacao = new Notificacao(
            $arrayLinhas[$i]["id"], $arrayLinhas[$i]["dataNotificacao"],
            $estado,
            $municipio2, $arrayLinhas[$i]["excluido"], $arrayLinhas[$i]["validado"],
            $arrayLinhas[$i]["created_at"], $arrayLinhas[$i]["updated_at"],
            $dados,
            $paciente
        );
         $okN =  $notificacaoDao->inserir($notificacao);
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
echo "\nFim do experimento de inserção!\n"
/*
$end = microtime(true);
$tempoGasto = ($end - $start) *1000;
echo "Tempo gasto: " . $tempoGasto . " milisegundos";
*/
?>
