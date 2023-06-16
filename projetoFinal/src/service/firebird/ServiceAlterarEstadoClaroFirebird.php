<?php
require_once '../../dao/EstadoDao.php';
require_once '../../dao/MunicipioDao.php';
require_once '../../dao/PacienteDao.php';
require_once '../../dao/DadosOcupacaoDao.php';
require_once '../../dao/NotificacaoDao.php';
require_once '../../dao/ConnectionCreatorMariaDb.php';
require_once '../../dao/ConnectionCreatorFirebird.php';
require_once "../../model/Estado.php";
require_once "../../model/Municipio.php";
require_once "../../model/Paciente.php";
require_once "../../model/DadosOcupacao.php";
require_once "../../model/Notificacao.php";
require_once "../../service/ServiceArquivo.php";
require_once "../../utils/utils.php";

$conexao = ConnectionCreatorFirebird::createConnection();
$estadoDao = new EstadoDao($conexao);
$municipioDao = new MunicipioDao($conexao);
$dadosDao = new DadosOcupacaoDao($conexao);
$pacienteDao = new PacienteDao($conexao);
$notificacaoDao = new NotificacaoDao($conexao);

//$start = microtime(true); //iniciando contador
echo "Iniciando a execução do experimento de Alteração.... (aguarde)";
try{
    $conexao->beginTransaction();
    $ok = $notificacaoDao->alterarNotificacoes($estadoDao,$municipioDao,$dadosDao,$pacienteDao);
    if($ok){
        $conexao->commit();
    }else{
        echo "\nFalha na transação de alteração de estado. ";
        $conexao->rollBack();
    }
    

}
catch(PDOException $e){
     $conexao->rollBack();
    echo $e->getMessage();
}
$conexao = null; //fechando conexao
/*$end = microtime(true);
    $tempoGasto = ($end - $start) *1000;    
    echo "\nTempo gasto: " . $tempoGasto . " milisegundos. ";
*/

echo "\nFim do experimento de alteração!\n"



?>
