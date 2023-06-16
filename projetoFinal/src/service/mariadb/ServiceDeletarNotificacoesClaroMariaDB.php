<?php
require_once "../../dao/EstadoDao.php";
require_once "../../dao/MunicipioDao.php";
require_once "../../dao/NotificacaoDao.php";
require_once "../../dao/PacienteDao.php";
require_once "../../dao/DadosOcupacaoDao.php";
require_once "../../model/Estado.php";
require_once "../../model/Municipio.php";
require_once "../../model/Paciente.php";
require_once "../../model/DadosOcupacao.php";
require_once "../../model/Notificacao.php";
require_once "../../dao/ConnectionCreatorMariaDb.php";

$conexao = ConnectionCreatorMariaDB::createConnection();
$estadoDao = new EstadoDao($conexao);
$municipioDao = new MunicipioDao($conexao);
$dadosDao = new DadosOcupacaoDao($conexao);
$pacienteDao = new PacienteDao($conexao);
$notificacaoDao = new NotificacaoDao($conexao);

$ok = false;
//$start = microtime(true); //iniciando contado
echo "Iniciando a execução do experimento de remoção.... (aguarde)";

try{
    $conexao->beginTransaction();
    $ok = $notificacaoDao->deletarNotificacoes($estadoDao, $municipioDao, $pacienteDao, $dadosDao);
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
?>