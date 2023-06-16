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
require_once "../../dao/ConnectionCreatorMariaDb.php";


$conexao = ConnectionCreatorMariaDB::createConnection();
$key = "AbcdAbcdAbcdAbcd";
$estadoDao = new EstadoDaoMariaDB($conexao);
$municipioDao = new MunicipioDaoMariaDB($conexao);
$dadosDao = new DadosOcupacaoDaoMariaDB($conexao);
$pacienteDao = new PacienteDAOMariaDB($conexao);
$notificacaoDao = new NotificacaoDaoMariaDB($conexao);
$tudoOk = false;
//$start = microtime(true); //iniciando contador
echo "\nIniciando a execução do experimento de Busca.... (aguarde)";

try{
    $conexao->beginTransaction();
    $arrayNotificacoes = $notificacaoDao->buscarNotificacoesCifradas($key,$estadoDao,$municipioDao, $pacienteDao, $dadosDao);
    $conexao->commit();
}
catch(PDOException $e){
    $conexao->rollBack();
    echo $e->getMessage();
}
$conexao = null; //fechando conexao
/*
$end = microtime(true);
$tempoGasto = ($end - $start) *1000;    
echo "\nTempo gasto: " . $tempoGasto . " milisegundos. ";
*/
echo "\nFim do experimento de busca!\n"

?>