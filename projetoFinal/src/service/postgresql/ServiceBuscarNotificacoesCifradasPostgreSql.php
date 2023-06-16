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
    $pacienteDao = new PacienteDaoPostgreSql($conexao);
    $dadosDao = new DadosOcupacaoDaoPostgreSql($conexao);
    $notificacaoDao = new NotificacaoDaoPostgreSql($conexao);
    $key = "AbcdAbcdAbcdAbcd";
    $arrayNotificacoes = [];
    //$start = microtime(true); //iniciando contador
    echo "\nIniciando a execução do experimento de Busca.... (aguarde)";

    try{
        $conexao->beginTransaction();
        $arrayNotificacoes = $notificacaoDao->buscarNotificacoesCifradas($key,$estadoDao,$municipioDao,$pacienteDao,$dadosDao);
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