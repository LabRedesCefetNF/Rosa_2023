<?php
require_once '../dao/ConnectionCreatorPostgreSQL.php';
require_once '../dao/postgresql/EstadoDaoPostgreSql.php';
require_once '../model/Estado.php';
require_once '../dao/postgresql/MunicipioDaoPostgreSql.php';
require_once '../model/Municipio.php';
require_once '../dao/postgresql/PacienteDaoPostgreSql.php';
require_once '../model/Paciente.php';
require_once '../model/DadosOcupacao.php';
require_once '../dao/postgresql/DadosOcupacaoDaoPostgreSql.php';
require_once "../model/Notificacao.php";
require_once "../dao/postgresql/NotificacaoDaoPostgreSql.php";
require_once "../dao/EstadoDao.php";
require_once "../dao/MunicipioDao.php";
require_once "../dao/PacienteDao.php";
require_once "../dao/DadosOcupacaoDao.php";
require_once "../dao/NotificacaoDao.php";

$conexao = ConnectionCreateorPostgreSQL::createConnection();
//$estadoDao = new EstadoDao($conexao);
$key = 'AbcdAbcdAbcdAbcd';
//teste estado

/*
$estado = new Estado('São Paulo');
$estadoDao = new EstadoDaoPostgreSql($conexao);

//$key = 'AbcdAbcdAbcdAbcd';
//$estadoDao->inserirCifrado($estado,$key);
//$estado2 = $estadoDao->buscarUltimoCifrado($key);
//$estado2 = $estadoDao->buscarCifradoPorId(98, $key);
//var_dump($estado2);
*/


//teste municipio
/*
//$estadoM = $estadoDao->buscarUltimoEstadoCifradoPostgreSql('aes192', $key);
$municipioDao = new MunicipioDaoPostgreSql($conexao);
$idEstado = 98;
$municipio = new Municipio('Macuco', $idEstado);
//$municipioDao->inserirCifrado($municipio, $key);
//$municipio2 = $municipioDao->buscarUltimoCifrado($key);
$municipio2 = $municipioDao->buscarCifradoPorId(67, $key);
var_dump($municipio2);
*/

//teste paciente
/*
$pacienteDao = new PacienteDaoPostgreSql($conexao);
$paciente = new Paciente("Andre", 'NF');
//$pacienteDao->inserirCifrado($paciente, $key);
//$paciente2 = $pacienteDao->buscarUltimoCifrado($key);
$paciente2 = $pacienteDao->buscarCifradoPorId(68, $key);
var_dump($paciente2);
*/

//teste dados ocupacao
/*
$dadosDao = new DadosOcupacaoDaoPostgreSql($conexao);
$dados = new DadosOcupacao( "1", "2", "3", "4", "5", "6", "7", "8", "9", "10","11","12", "13");
//$dadosDao->inserirCifrado($dados,$key);
$dados2 = $dadosDao->buscarUltimoCifrado($key);
//$dados2 = $dadosDao->buscarCifradoPorId(67,$key);
var_dump($dados2);

*/
//teste notificaco
/*
$estado = new Estado("Sao Paulo");
$estadoDao = new EstadoDaoPostgreSql($conexao);
$estadoDao->inserirCifrado($estado, $key);
$estadoU = $estadoDao->buscarUltimo( $key);
$idEstado= $estadoU->recuperarId();

$municipio = new Municipio("Friburgo", $idEstado);
$municipioDao = new MunicipioDaoPostgreSql($conexao);
$municipioDao->inserirCifrado($municipio, $key);
$municipio = $municipioDao->buscarUltimo( $key);

$paciente = new Paciente('MARIO', $municipio->recuperarNome());
$pacienteDao = new PacienteDaoPostgreSql($conexao);
$pacienteDao->inserirCifrado($paciente, $key);
$paciente = $pacienteDao->buscarUltimo($key);


$dadosDao = new DadosOcupacaoDaoPostgreSql($conexao);
$dados = new DadosOcupacao( "1", "2", "3", "4", "5", "6", "7", "8", "9", "10","11","12", "13");
$dadosDao->inserirCifrado($dados, $key);
$dados = $dadosDao->buscarUltimo( $key);

$notificacaoDao = new NotificacaoDaoPostgreSql($conexao);

$notificacao = new Notificacao("1","16/01/2023", $estadoU, $municipio,  False, False,"16/1/2023","16/01/2023",
                               $dados, $paciente );
$notificacaoDao->inserirCifrado($notificacao, $key);
*/


/*
$estadoDao = new EstadoDaoPostgreSql($conexao);
$municipioDao = new MunicipioDaoPostgreSql($conexao);
$pacienteDao = new PacienteDaoPostgreSql($conexao);
$dadosDao = new DadosOcupacaoDaoPostgreSql($conexao);
$notificacaoDao = new NotificacaoDaoPostgreSql($conexao);
$arrayNotificacoes = [];
//$notificacao = new Notificacao("1","16/01/2023", $estadoU, $municipio,  False, False,"16/1/2023","16/01/2023",
 //                              $dados, $paciente );
//$n2 = $notificacaoDao->buscarUltimaNotificacaoCifradaPostgreSql("aes", $key, $estadoDao, $municipioDao, $pacienteDao, $dadosDao);
//$notificacaoDao->inserirNotificacaoCifradaPostgreSql($notificacao, "aes", $key);
//$arrayNotificacoes = $notificacaoDao->buscarNotificacoesCifradasPostgreSql("aes", $key, $estadoDao, $municipioDao, $pacienteDao, $dadosDao );
//var_dump($arrayNotificacoes);
//$notificacao = $notificacaoDao->buscarUltimaNotificacaoCifrada($key, $estadoDao, $municipioDao,$pacienteDao,$dadosDao);
$arrayNotificacoes = $notificacaoDao->buscarNotificacoesCifradas($key, $estadoDao, $municipioDao,$pacienteDao,$dadosDao);
var_dump($arrayNotificacoes);
//var_dump($notificacao);


//$notificacaoDao->alterarNotificacoesCifradasPostgreSql('23/03/2023','aes', $key, 2);
*/



//alterar notificações em claro
/*
$notificacaoDao = new NotificacaoDao($conexao);
$notificacaoDao->alterarEstadoNotificacoesCifradasPostgreSql("aes",$key,5);
*/

//teste de alterar estado
/*
$estadoDao = new EstadoDaoPostgreSql($conexao);
$estadoDao->alterarCifrado($key);
*/

//teste em claro
//municipio
/*
$mDao = new MunicipioDao ($conexao);
$mDao->deletar(66);
*/

//paciente
/*
$pDao = new PacienteDAO($conexao);
$pDao->deletar(66);
*/

//dados_ocupacao
/*
$dadosDao = new DadosOcupacaoDao($conexao);
$dadosDao->deletar(66);
*/
 
//notificacao

$estado = new Estado("Rio");
$estadoDao = new EstadoDao($conexao);
$estadoDao->inserir($estado);
$estadoU = $estadoDao->buscarUltimo();
$idEstado= $estadoU->recuperarId();

$municipio = new Municipio("Friburgo", $idEstado);
$municipioDao = new MunicipioDao($conexao);
$municipioDao->inserir($municipio);
$municipio = $municipioDao->buscarUltimo();

$paciente = new Paciente('MARIO', $municipio->recuperarNome());
$pacienteDao = new PacienteDao($conexao);
$pacienteDao->inserir($paciente);
$paciente = $pacienteDao->buscarUltimo();


$dadosDao = new DadosOcupacaoDao($conexao);
$dados = new DadosOcupacao( "1", "2", "3", "4", "5", "6", "7", "8", "9", "10","11","12", "13");
$dadosDao->inserir($dados);
$dados = $dadosDao->buscarUltimo();

$notificacaoDao = new NotificacaoDao($conexao);

$notificacao = new Notificacao("1","16/01/2023", $estadoU, $municipio,  False, False,"16/1/2023","16/01/2023",
                               $dados, $paciente );
$notificacaoDao->inserir($notificacao);


//deletar notificacao em claro
/*
$estadoDao = new EstadoDao($conexao);
$municipioDao = new MunicipioDao($conexao);
$pacienteDao = new PacienteDao($conexao);
$dadosDao = new DadosOcupacaoDao($conexao);
$notificacaoDao = new NotificacaoDao($conexao);
$notificacaoDao->deletarNotificacoes($estadoDao,$municipioDao,$pacienteDao,$dadosDao);
*/

/*
$estadoDao = new EstadoDaoPostgreSql($conexao);
$municipioDao = new MunicipioDaoPostgreSql($conexao);
$pacienteDao = new PacienteDaoPostgreSql($conexao);
$dadosDao = new DadosOcupacaoDaoPostgreSql($conexao);
$notificacaoDao = new NotificacaoDaoPostgreSql($conexao);
$notificacaoDao->deletarNotificacoesCifradas($key, $estadoDao,$municipioDao,$pacienteDao,$dadosDao);
*/

//alterar estado em claro
/*
$estadoDao = new EstadoDaoPostgreSql($conexao);
$estadoDao->alterar($key, 4843);
*/
?>