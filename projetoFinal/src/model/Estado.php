<?php
    class Estado {
        private int $id;
        private string $nome;
        
       

        public function __construct( string $nome, int $id =0 ){
                $this->id = $id;
                $this->nome = $nome;
                

        }

        public function setarId(int $id)
        {
            $this->id = $id;
        }

        public function setarNome(string $nome)
        {
            $this->nome = $nome;
        }

        


        public function recuperarId()
        {
            return $this->id;
        }

        public function recuperarNome()
        {
            return $this->nome;
        }


       
         
    }

?>