<?php
    class Municipio
    {
        private int $id;
        private string $nome;
        private int $idEstado;

        public function __construct(string $nome, int $idEstado, int $id=0)
        {
            $this->id = $id;
            $this->nome = $nome;
            $this->idEstado = $idEstado;
        }

        public function setarId(int $id)
        {
            $this->id = $id;
        }

        public function setarNome(string $nome)
        {
            $this->nome = $nome;
        }

        public function setarIdEstado (int $idEstado)
        {
            $this->idEstado = $idEstado;
        }

        public function recuperarNome()
        {
            return $this->nome;
        }

        public function recuperaId()
        {
            return $this->id;
        }

        public function recuperarIdEstado()
        {
            return $this->idEstado;
        }
    }

?>