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

$key = "AbcdAbcdAbcdAbcd";
$conexao = ConnectionCreatorMariaDB::createConnection();
$estadoDao = new EstadoDaoMariaDB($conexao);
$municipioDao = new MunicipioDaoMariaDB($conexao);
$dadosDao = new DadosOcupacaoDaoMariaDB($conexao);
$pacienteDao = new PacienteDAOMariaDB($conexao);
$notificacaoDao = new NotificacaoDaoMariaDB($conexao);
$ok = false;
//$start = microtime(true); //iniciando contador
echo "Iniciando a execução do experimento de remoção.... (aguarde)";


try{
    $conexao->beginTransaction();
    $ok = $notificacaoDao->deletarNotificacoesCifradas($key, $estadoDao, $municipioDao, $pacienteDao, $dadosDao);
    if($ok){
        $conexao->commit();
    }else{
        echo "\nFalha na transação de remoção de notificacao no MariaDB.";
        $conexao->rollBack();
    }
}
catch(PDOException $e){
    $conexao->rollBack();
    echo $e->getMessage();
}
$conexao = null;
echo "\nFim do experimento de remoção!\n";
/*
$end = microtime(true);
$tempoGasto = ($end - $start) *1000;
echo "Tempo gasto: " . $tempoGasto . " milisegundos. ";
*/
?>