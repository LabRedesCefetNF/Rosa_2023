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

$conexao = ConnectionCreateorPostgreSQL::createConnection();
$estadoDao = new EstadoDao($conexao);
$municipioDao = new MunicipioDao($conexao);
$dadosDao = new DadosOcupacaoDao($conexao);
$pacienteDao = new PacienteDao($conexao);
$notificacaoDao = new NotificacaoDao($conexao);
$arrayNotificacoes = [];
echo "\nIniciando a execução do experimento de Busca.... (aguarde)";
try{
    $conexao->beginTransaction();
    $arrayNotificacoes = $notificacaoDao->buscarNotificacoes($estadoDao,$municipioDao,$pacienteDao, $dadosDao);
    $conexao->commit();
}
catch(PDOException $e){
    $conexao->rollBack();
    echo $e->getMessage();
}
$conexao = null; //fechando conexao
echo "\nFim do experimento de busca!\n"
?>