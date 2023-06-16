<?php
   
namespace interfaces;
    interface InterfacePDO {
        
        public function buscarUltimo(string $chave = null );
        public function buscarPorId(int $id, string $chave = null);

        public function deletar(int $id, string $chave = null);

    }

?>