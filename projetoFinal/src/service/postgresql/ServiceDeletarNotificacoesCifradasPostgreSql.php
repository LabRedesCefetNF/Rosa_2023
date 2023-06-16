<?php
require_once '../../dao/ConnectionCreatorPostgreSQL.php';
require_once '../../dao/postgresql/EstadoDaoPostgreSql.php';
require_once '../../dao/postgresql/MunicipioDaoPostgreSql.php';
require_once '../../dao/postgresql/DadosOcupacaoDaoPostgreSql.php';
require_once '../../dao/postgresql/PacienteDaoPostgreSql.php';
require_once '../../dao/postgresql/NotificacaoDaoPostgreSql.php';
require_once "../../model/Estado.php";
require_once "../../model/Municipio.php";
require_once "../../model/Paciente.php";
require_once "../../model/DadosOcupacao.php";
require_once "../../model/Notificacao.php";

$conexao = ConnectionCreateorPostgreSQL::createConnection();
$estadoDao = new EstadoDaoPostgreSql($conexao);
$municipioDao = new MunicipioDaoPostgreSql($conexao);
$dadosDao = new DadosOcupacaoDaoPostgreSql($conexao);
$pacienteDao = new PacienteDaoPostgreSql($conexao);
$notificacaoDao = new NotificacaoDaoPostgreSql($conexao);
$key = "AbcdAbcdAbcdAbcd";


$ok = false;
//$start = microtime(true); //iniciando contador
echo "Iniciando a execução do experimento de remoção.... (aguarde)";

try{
    $conexao->beginTransaction();
    $ok = $notificacaoDao->deletarNotificacoesCifradas($key, $estadoDao, $municipioDao, $pacienteDao, $dadosDao);
    if($ok){
        $conexao->commit();
    }else{
        echo "\nFalha na transação de remoção de notificacao no PostgreSql.";
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
$tempoGasto = ($end - $start) *1000;
echo "\nTempo gasto: " . $tempoGasto . " milisegundos. ";
*/
?>