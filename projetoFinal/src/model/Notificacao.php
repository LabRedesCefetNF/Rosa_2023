<?php

    class Notificacao
    {
        private int $id;
        private string $idDataSus;
        private string $dataNotificacao;
        private Estado $estadoNotificacao;
        private Municipio $municipioNotificacao;
        private bool $excluida;
        private bool $validado;
        private string $createdAt;
        private string $updatedAt;
        private DadosOcupacao $dadosOcupacao;
        private Paciente $paciente;

        public function __construct(string $idDataSus,
         string $dataNotificacao,
        
         Estado $estadoNotificacao, 
         Municipio $municipioNotificacao, 
         bool $excluida,
        
         bool $validado, 
         string $createdAt, 
         string $updatedAt,
            
         DadosOcupacao $dadosOcupacao, 
         
         Paciente $paciente, int $id=0)
        {
                $this->id= $id;
                $this->idDataSus= $idDataSus;
                $this->dataNotificacao= $dataNotificacao;
                $this->estadoNotificacao = $estadoNotificacao;
                $this->municipioNotificacao= $municipioNotificacao;
                $this->excluida= $excluida;
                $this->validado= $validado;
                $this->createdAt= $createdAt;
                $this->updatedAt= $updatedAt;
                $this->dadosOcupacao= $dadosOcupacao;
                $this->paciente= $paciente;
            
        }

        public function setarId( int $id)
        {
            $this->id = $id;
        }

        public function setarIdDataSus(string $id)
        {
            $this->idDataSus = $id;
        }

        public function setarDataNotificacao (string $data)
        {
            $this->dataNotificacao = $data;
        }

        public function setarEstadoNotificacao (Estado $estado)
        {
            $this->estadoNotificacao = $estado;

        }

        public function setarMunicipioNotificacao(Municipio $municipio)
        {
            $this->municipioNotificacao =    $municipio;
        }

        public function setarExcluida(bool $excluida)
        {
            $this->excluida = $excluida;
        }

        public function setarValidado (bool $validado)
        {
            $this->validado = $validado;
        }

        public function setarDataCriacao(string $data)
        {
            $this->createdAt =  $data;
        }

        public function setarDataAtualizacao(string $data)
        {
            $this->updatedAt = $data;
        }

        public function setarDadosOcupacao(DadosOcupacao $dados)
        {
            $this->dadosOcupacao = $dados;
        }

        public function recuperarId()
        {
            return $this->id;
        }

        public function recuperarIdDataSus()
        {
            return $this->idDataSus;
        }

        public function recuperarDataNotificacao()
        {
            return $this->dataNotificacao;
        }

        public function recuperarEstado()
        {
            return $this->estadoNotificacao;
        }

        public function recuperarMunicipio()
        {
            return $this->municipioNotificacao;
        }

        public function recuperarExcluida()
        {
            return $this->excluida;
        }

        public function recuperarValidacao()
        {
            return  $this->validado;
        }

        public function recuperarDataCriacao()
        {
            return $this->createdAt;
        }

        public function recuperarDataAtualizacao()
        {
            return $this->updatedAt;
        }

        public function recuperarPaciente()
        {
            return $this->paciente;
        }

        public function recuperarDadosOcupacao()
        {
            return $this->dadosOcupacao;
        }
    }
?>