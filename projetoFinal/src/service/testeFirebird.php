<?php
require_once '/var/www/html/projetoFinal/src/utils/utils.php';

require_once "../dao/ConnectionCreatorFirebird.php";
require_once "../model/Estado.php";
require_once "../dao/firebird/EstadoDaoFirebird.php";
require_once "../dao/firebird/MunicipioDaoFirebird.php";
require_once "../model/Municipio.php";
require_once "../model/Paciente.php";
require_once "../dao/firebird/PacienteDaoFirebird.php";
require_once "../model/DadosOcupacao.php";
require_once "../dao/firebird/DadosOcupacaoDaoFirebird.php";
require_once "../model/Notificacao.php";
require_once "../dao/firebird/NotificacaoDaoFirebird.php";
require_once "../dao/EstadoDao.php";

//var_dump(php_ini_loaded_file());


$conexao = ConnectionCreatorFirebird::createConnection();
$key = (string) "AbcdAbcdAbcdAbcd";
$iv = (string) "0123456789123456";

/*
$estado = new Estado("RJ");

$estadoDao = new EstadoDaoFirebird($conexao);
//$estadoDao->inserirEstadoCifrado($estado, $key);
//$estado1 = $estadoDao->buscarUltimoEstadoCifrado($key);
//$idEstado = $estado1->recuperarId();
$estado1 = $estadoDao->buscarEstadoCifradoPorId(80, $key);
var_dump($estado1);
*/

//teste municipio
/*
$municipio = new Municipio("Bom Jardim",81);
$municipioDao = new MunicipioDaoFirebird($conexao);
//$municipioDao->inserirMunicipioCifrado($municipio, $key);
//$municipio2 = $municipioDao->buscarUltimoMunicipioCifrado($key);
$municipio2 = $municipioDao->buscarMunicipioCifradoPorId(4, $key);
var_dump($municipio2);
*/

//teste Paciente

/*
$paciente = new Paciente("Juliana", "NF");
$pacienteDao = new PacienteDaoFirebird($conexao);

//$pacienteDao->inserirPacienteCifrado($paciente, $key);
//$paciente2 = $pacienteDao->buscarPacienteCifradoPorId(2, $key);
$paciente2 = $pacienteDao->buscarUltimoCifrado($key);
var_dump($paciente2);
*/

//teste dados ocupacao
/*
  $dados = new DadosOcupacao( "1", "2", "3", "4", "5", "6", "7", "8", "9", "10","11","12", "13");
$dadosDao = new DadosOcupacaoDaoFirebird($conexao);
//$dadosDao->inserirDadosOcupacaoCifrado($dados,$key );
$dados2 = $dadosDao->buscarDadosOcupacaoCifradoPorId(6, $key);
var_dump($dados2);
*/

//teste notificacao

$estadoDao = new EstadoDaoFirebird($conexao);
$municipioDao = new MunicipioDaoFirebird($conexao);
$pacienteDao = new PacienteDaoFirebird($conexao);
$dadosDao = new DadosOcupacaoDaoFirebird($conexao);
$notificacaoDao = new NotificacaoDaoFirebird($conexao);
$est = new Estado("Rio");

$estadoDao->inserirCifrado($est, $key);
$estado = $estadoDao->buscarUltimo($key);

$mun = new Municipio("NF", $estado->recuperarId());
$municipioDao->inserirCifrado($mun, $key);

$municipio = $municipioDao->buscarUltimo($key);

$pac = new Paciente("Andre", "nf");
$pacienteDao->inserirCifrado($pac, $key);
$paciente = $pacienteDao->buscarUltimo($key);

$da = new DadosOcupacao( "1", "2", "3", "4", "5", "6", "7", "8", "9", "10","11","12", "13");
$dadosDao->inserirCifrado($da, $key);
$dados = $dadosDao->buscarUltimo($key);

$notificacao = new Notificacao("1","16/01/2023", $estado, $municipio,  False, False,"16/1/2023","16/01/2023",
  $dados, $paciente );
$notificacaoDao->inserirCifrado($notificacao, $key);



//teste buscar todas notificações cifradas
/*
$estadoDao = new EstadoDaoFirebird($conexao);
$municipioDao = new MunicipioDaoFirebird($conexao);
$dadosDao = new DadosOcupacaoDaoFirebird($conexao);
$pacienteDao = new PacienteDaoFirebird($conexao);
$notificacaoDao = new NotificacaoDaoFirebird($conexao);
$arrayNotificacao = array();
$arrayNotificacao = $notificacaoDao->buscarNotificacoesCifradas($key, $estadoDao,$municipioDao,$dadosDao,$pacienteDao);
var_dump($arrayNotificacao);
/*

/*$estadoDao = new EstadoDao($conexao);
$estadoDao->alterarEstadoCifradoAesFirebird(3);
*/

/*
$estadoDao = new EstadoDaoFirebird($conexao);
$municipioDao = new MunicipioDaoFirebird($conexao);
$pacienteDao = new PacienteDaoFirebird($conexao);
$dadosDao = new DadosOcupacaoDaoFirebird($conexao);
$notificacaoDao = new NotificacaoDaoFirebird($conexao);
$notificacaoDao->alterarEstadoNotificacoesCifradas($key, $estadoDao, $municipioDao, $pacienteDao, $dadosDao);
*/

/*
$estadoDao = new EstadoDaoFirebird($conexao);
$nome = ("Teste6");
$estado = new Estado($nome);
//$estadoDao->inserirCifrado($estado, $key);
//$estado2 = $estadoDao->buscarUltimo($key);
//$estadoDao->alterar($key, 12300);
//$estado3 = $estadoDao->buscarUltimo($key);
$estado3 = $estadoDao->buscarPorId(12299, $key);
var_dump($estado3);
*/

/*
echo "\n".($estado2->recuperarNome());
$estadoDao->alterar($key);
$estado3 = $estadoDao->buscarPorId(1142,$key);
var_dump($estado3);
*/
//$array =explode('#',$estado2->recuperarNome() );
//echo "\n". $array[0];
//$estado3 = new Estado($array[0]);
//$eDao = new EstadoDao($conexao);
//$eDao->inserir($estado3);

?>