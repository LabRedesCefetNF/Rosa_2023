<?php
    class Paciente
    {
        private int $id;
        private string $nome;
        private string $origem;

        public function __construct(string $nome, string $origem, int $id=0)
        {
            $this->id = $id;
            $this->nome = $nome;
            $this->origem = $origem;
        }

        public function setarNome(string $nome)
        {
            $this->nome = $nome;
        }

        public function setarId(int $id)
        {
            $this->id = $id;
        }

        public function setarOrigem(string $origem)
        {
            $this->origem = $origem;
        }

        public function recuperarNome()
        {
            return $this->nome;
        }

        public function recuperarId()
        {
            return $this->id;
        }

        public function recuperarOrigem()
        {
            return $this->origem;
        }
    }
?>