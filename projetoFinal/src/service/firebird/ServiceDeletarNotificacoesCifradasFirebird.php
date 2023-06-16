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
$dadosDao = new DadosOcupacaoDaoFirebird($conexao);
$pacienteDao = new PacienteDaoFirebird($conexao);
$notificacaoDao = new NotificacaoDaoFirebird($conexao);
$key = "AbcdAbcdAbcdAbcd";

$ok = false;
//$start = microtime(true); //iniciando contado
echo "Iniciando a execução do experimento de remoção.... (aguarde)";
try{
    $conexao->beginTransaction();
    $ok = $notificacaoDao->deletarNotificacoesCifradas($key,$estadoDao, $municipioDao, $pacienteDao, $dadosDao);
    if($ok){
        $conexao->commit();
    }else{
        echo "\nFalha ao realizar transação para remoção de notificações.";
        $conexao->rollBack();
    }
}
catch(PDOException $e){
    $conexao->rollBack();
    echo $e->getMessage();
}
$conexao = null; //fechando conexao
echo "\nFim do experimento de remoção!\n"
/*
$end = microtime(true);
$tempoGasto = ($end - $start) /1000000;
echo "\nTempo gasto: " . $tempoGasto . " segundos. ";
*/
?>