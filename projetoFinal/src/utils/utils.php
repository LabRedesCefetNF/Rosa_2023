<?php

use interfaces\InterfacePDO;

function popularDadoOcupacao(array $linha)
        {
            $dados = null;
            foreach($linha as $l){
                $dados = new DadosOcupacao(
                    $l[1],
                    $l[2],
                    $l[3],
                    $l[4],
                    $l[5],
                    $l[6],
                    $l[7],
                    $l[8],
                    $l[9],
                    $l[10],
                    $l[11],
                    $l[12],
                    $l[13],
                    $l[0]
                );
                
            }
            return $dados;
        }

         function popularListaNotificacoes( string $key = null, array $linhas, InterfacePDO $estadoDao, InterfacePDO $municipioDao, InterfacePDO $pacienteDAO, InterfacePDO $dadosDao)
        {
           $arrayNotificacoes = [];
           foreach($linhas as $l){
               $notificacao = new Notificacao($l[1],
                               $l[2],
                               $estadoDao->buscarPorId($l[3], $key),
                               $municipioDao->buscarPorId($l[4], $key),
                               $l[5],
                               $l[6],
                               $l[7],
                               $l[8],
                               $dadosDao->buscarPorId($l[10], $key),
                               $pacienteDAO->buscarPorId($l[9], $key),
                               $l[0]
                            );
               $arrayNotificacoes [] = $notificacao;
           }
           return $arrayNotificacoes;
        }

        function paddingIn(string $texto)
        {
            $texto = bin2hex($texto);
            return $texto;
        }

        function paddingOut(string $texto)
        {
           $textoClaro = hex2bin($texto);
            return $textoClaro;
        }


?>