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

//$start = microtime(true); //iniciando contador
echo "Iniciando a execução do experimento de alteração.... (aguarde)";

try{
    $conexao->beginTransaction();
    $ok = $notificacaoDao->alterarEstadoNotificacoesCifradas($key,$estadoDao,$municipioDao,$dadosDao,$pacienteDao);
    if($ok){
        $conexao->commit();
    }else{
        echo "\nFalha na transação de alteração de estado no MariaDB.";
        $conexao->rollBack();
    }

}
catch(PDOException $e){
    $conexao->rollBack();
    echo $e->getMessage();
}
$conexao = null; //fechando conexao
echo "\nFim do experimento de alteração!\n"

/*
$end = microtime(true);
    $tempoGasto = ($end - $start) *1000;    
    echo "\nTempo gasto: " . $tempoGasto . " milisegundos. ";
*/
?>