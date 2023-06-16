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

$key = "AbcdAbcdAbcdAbcd";

$estadoDao = new EstadoDaoFirebird($conexao);
$municipioDao = new MunicipioDaoFirebird($conexao);
$dadosDao = new DadosOcupacaoDaoFirebird($conexao);
$pacienteDao = new PacienteDaoFirebird($conexao);
$notificacaoDao = new NotificacaoDaoFirebird($conexao);
//$start = microtime(true); //iniciando contador
echo "Iniciando a execução do experimento de alteração.... (aguarde)";
try{
    $conexao->beginTransaction();
    $ok = $notificacaoDao->alterarEstadoNotificacoesCifradas($key, $estadoDao,$municipioDao, $dadosDao, $pacienteDao);
    if($ok){
        $conexao->commit();
    }else{
        echo "\nFalha na transação de alteração de estado no Firebird.";
        $conexao->rollBack();
    }
    

}
catch(PDOException $e){
     $conexao->rollBack();
    echo $e->getMessage();
}
$conexao = null; //fechando conexao
/*$end = microtime(true);
    $tempoGasto = ($end - $start) /1000000;    
    echo "\nTempo gasto: " . $tempoGasto . " segundos. ";
*/
    echo "\nFim do experimento de alteração!\n"

?>