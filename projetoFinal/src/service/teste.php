<?php
//require 'ControladoraArquivo.php';
require '../dao/ConnectionCreatorMariaDb.php';
require '../dao/ConnectionCreatorFirebird.php';
require '../model/Estado.php';
require '../model/Paciente.php';
require '../model/Municipio.php';
require '../model/DadosOcupacao.php';
require_once '../dao/EstadoDao.php';
require_once '../dao/MunicipioDao.php';
require_once '../dao/PacienteDao.php';
require_once '../dao/DadosOcupacaoDao.php';
require_once '../dao/NotificacaoDao.php';
require '../dao/mariadb/EstadoDaoMariaDB.php';
require '../dao/mariadb/MunicipioDaoMariaDB.php';
require '../dao/mariadb/PacienteDaoMariaDB.php';
require '../dao/mariadb/DadosOcupacaoDaoMariaDB.php';
require '../model/Notificacao.php';
require '../dao/mariadb/NotificacaoDaoMariaDB.php';
require_once '../service/ServiceArquivo.php';

   // $arquivo = new ControladoraArquivo('../LeitoOcupacao_2021.csv');
$key = 'AbcdAbcdAbcdAbcd';
    //var_dump($arquivo->retornaConteudoCsv(1));
  //$conexao = ConnectionCreatorFirebird::createConnection();
  $conexao = ConnectionCreatorMariaDB::createConnection();

//teste estado
/*
$estadoDao = new EstadoDaoMariaDB($conexao);
//$estado = new Estado("Manaus");
//$estadoDao->inserirCifrado($estado,$key);
//$estado2 = $estadoDao->buscarCifradoPorId(100,$key);
//var_dump($estado2);
$estadoDao->alterarCifrado($key);
  */ 

  //teste municipio
/*
$municipioDao = new MunicipioDaoMariaDB($conexao);
$municipio = new Municipio("Nova Friburgo", '101');
//$municipioDao->inserirCifrado($municipio, $key);
$m2 = $municipioDao->buscarCifradoPorId(96,$key);
var_dump($m2);
*/
//$estadoNovo = $estadoDao->buscarUltimoEstadoInserido();
//var_dump($estadoNovo);

    //testes com paciente
    /*
    $paciente = new Paciente('Andre', 'Nova Friburgo');
    $paciente3 = new Paciente('Andre3', 'Cachoeira');
    $pacienteDao = new PacienteDAOMariaDB($conexao);
    //$pacienteDao->inserirCifrado($paciente3, $key);
    $p1 = $pacienteDao->buscarCifradoPorId(98,$key);
    var_dump($p1);
   // $arrayP = null;
    //$arrayP = $pacienteDao->buscarPacientesCifradosAES('password');
   // var_dump($arrayP);
    */

    /*teste municipio


    $municipioDAO = new MunicipioDao($conexao);
    $estadoDao = new EstadoDao($conexao);
    $estado = $estadoDao->buscarUltimoEstadoInserido();
    //$arrayMunicipio = $municipioDAO->buscarMunicipios();
    //var_dump($arrayMunicipio);
    
$id = $estado->recuperarId();
echo "$id";
    $municipio = new Municipio("Bom Jardim", $id);
    $municipioDAO->inserirMunicipioCifradoAES($municipio, 'password');
    $arrayMunicipio = array();
    var_dump($municipioDAO->buscarUltimoMunicipioCifradoAES('password'));
    //$arrayMunicipio = $municipioDAO->buscarMunicipiosCifradosAES('password');
//var_dump($arrayMunicipio);

*/

    //teste dados ocupação
    /*
   $dadosDao = new DadosOcupacaoDaoMariaDB($conexao);
   $dados = new DadosOcupacao( "1", "2", "3", "4", "5", "6", "7", "8", "9", "10","11","12", "13");
//echo $dados->recuperarSaidaConfirmadaAltas();

//$dadosDao->inserirCifrado($dados, $key);
$d2 = $dadosDao->buscarCifradoPorId(94,$key);
 //   $arrayDados = array();
   // $arrayDados = $dadosDao->buscarDadosOcupacaoCifradosAES('password');
//$ultimo = $dadosDao->buscaUltimoDadosOcupacao();
//$dados = $dadosDao->buscarUltimoDadoOcupacaoCifradoAES('password');
var_dump($d2);
*/

//teste notificação
/*
$notificacao = null;

$estado = new Estado("Rio");
$estadoDao = new EstadoDao($conexao);
$estadoDao->inserir($estado);
$estadoU = $estadoDao->buscarUltimo();
$idEstado= $estadoU->recuperarId();

$municipio = new Municipio("testeN", $idEstado);
$municipioDao = new MunicipioDao($conexao);
$municipioDao->inserir($municipio);
$municipio = $municipioDao->buscarUltimo();

$paciente = new Paciente('Suely', $municipio->recuperarNome());
$pacienteDao = new PacienteDAO($conexao);
$pacienteDao->inserir($paciente);
$paciente = $pacienteDao->buscarUltimo();

$dadosDao = new DadosOcupacaoDao($conexao);
$dados = new DadosOcupacao( "1", "2", "3", "4", "5", "6", "7", "8", "9", "10","11","12", "13");
$dadosDao->inserir($dados);
$dados = $dadosDao->buscarUltimo();

$notificacao = new Notificacao("1","11/01/2023", $estadoU, $municipio, TRUE, TRUE,"11/01/2023","11/01/2023",
                               $dados, $paciente );
$notificacaoDao = new NotificacaoDao($conexao);
$notificacaoDao->inserir($notificacao);
*/


//testando busca de notificações em claro
/*
$notificaçãoDao = new NotificacaoDao($conexao);
$estadoDao = new EstadoDao($conexao);
$municipioDao = new MunicipioDao($conexao);
$pacienteDao = new PacienteDAO($conexao);
$dadosDao = new DadosOcupacaoDao($conexao);

$arrayNotificacao = $notificaçãoDao->buscarNotificacoes($estadoDao, $municipioDao, $pacienteDao, $dadosDao);
var_dump($arrayNotificacao);
*/

//teste inserir notificação cifrada


$stringChave = 'AbcdAbcdAbcdAbcd';
$estado = new Estado("Rio");
$estadoDao = new EstadoDaoMariaDB($conexao);
$estadoDao->inserirCifrado($estado, $stringChave);
$estadoU = $estadoDao->buscarUltimo($stringChave);
$idEstado= $estadoU->recuperarId();

$municipio = new Municipio("testeJ", $idEstado);
$municipioDao = new MunicipioDaoMariaDB($conexao);
$municipioDao->inserirCifrado($municipio, $stringChave);
$municipio2 = $municipioDao->buscarUltimo($stringChave);

$paciente = new Paciente('Ju', $municipio->recuperarNome());
$pacienteDao = new PacienteDAOMariaDB($conexao);
$pacienteDao->inserirCifrado($paciente, $stringChave);
$paciente2 = $pacienteDao->buscarUltimo($stringChave);

$dadosDao = new DadosOcupacaoDaoMariaDB($conexao);
$dados = new DadosOcupacao( "1", "2", "3", "4", "5", "6", "7", "8", "9", "10","11","12", "13");
$dadosDao->inserirCifrado($dados, $stringChave);
$dados2 = $dadosDao->buscarUltimo($stringChave);

$notificacao = new Notificacao("1","16/01/2023", $estadoU, $municipio2,  False, False,"16/1/2023","16/01/2023",
                               $dados2, $paciente2 );
$notificacaoDao = new NotificacaoDaoMariaDB($conexao);
$notificacaoDao->inserirCifrado($notificacao, $stringChave);



//TESTE BUSCA NOTIFICAÇÕES CIFRADAS AES
/*
$estadoDao = new EstadoDaoMariaDB($conexao);
$municipioDao = new MunicipioDaoMariaDB($conexao);
$dadosDao = new DadosOcupacaoDaoMariaDB($conexao);
$pacienteDao = new PacienteDAOMariaDB($conexao);
$notificacaoDao = new NotificacaoDaoMariaDB($conexao);
$arrayNotificacao = array();
$arrayNotificacao = $notificacaoDao->buscarNotificacoesCifradas($estadoDao, $municipioDao, $pacienteDao, $dadosDao, $key);
var_dump($arrayNotificacao);
*/

//teste alterar notificação
/*
$notificacaoDao = new NotificacaoDao($conexao);
$limite = (int) 2;
$notificacaoDao->alterarNotificacoesCifradasMariaDBAes('15/03/2023', $key, $limite);
*/

//teste deletar notificacao
/*
$notificacaoDao = new NotificacaoDao($conexao);
//$notificacaoDao->deletarNotificacoesCifradasMariaDBAES('Rio', $key, 1);
$estadoDao = new EstadoDao($conexao);
$estadoDao->alterarEstadoCifradoAesMariaDB($key, 3);
*/

//alterar estado cifrado
/*
$estadoDao = new EstadoDaoMariaDB($conexao);
$estadoDao->alterar($key);
*/

//teste ler qtd de linhas
/*
$servArquivo = new ServiceArquivo("../LeitoOcupacao_2021.csv");
echo $servArquivo->retornaQtdLinhas("../../config/rounds.conf");
*/
?>