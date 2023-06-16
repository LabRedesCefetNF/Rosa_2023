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
require_once "../../dao/ConnectionCreatorFirebird.php";

$conexao = ConnectionCreatorFirebird::createConnection();
$estadoDao = new EstadoDaoFirebird($conexao);
$municipioDao = new MunicipioDaoFirebird($conexao);
$pacienteDao = new PacienteDaoFirebird($conexao);
$dadosDao = new DadosOcupacaoDaoFirebird($conexao);
$notificacaoDao = new NotificacaoDaoFirebird($conexao);
$key = "AbcdAbcdAbcdAbcd";
$arrayNotificacoes = [];
//$start = microtime(true); //iniciando contador
echo "\nIniciando a execução do experimento de Busca.... (aguarde)";
try{
    $conexao->beginTransaction();
    $arrayNotificacoes = $notificacaoDao->buscarNotificacoesCifradas($key,$estadoDao,$municipioDao,$dadosDao, $pacienteDao);
    $conexao->commit();
}
catch(PDOException $e){
    $conexao->rollBack();
    echo $e->getMessage();
}
$conexao = null; //fechando conexao
echo "\nFim do experimento de busca!\n"
/*
$end = microtime(true);
$tempoGasto = ($end - $start) *1000;    
echo "\nTempo gasto: " . $tempoGasto . " milisegundos. ";
*/
?>