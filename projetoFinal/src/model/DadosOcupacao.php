<?php
    class DadosOcupacao
    {
        private int $id;
        
        private string $cnes;
        private string $ocupacaoSuspeitoCli;
        private string $ocupacaoSuspeitoUti;
        private string $ocupacaoConfirmadoCli;
        private string $ocupacaoConfirmadoUti;
        private string $ocupacaoCovidUti;
        private string $ocupacaoCovidCli;
        private string $ocupacaoHospitalarUti;
        private string $ocupacaoHospitalarCli;
        private string $saidaSuspeitaObitos;
        private string $saidaSuspeitaAltas;
        private string $saidaConfirmadaObitos;
        private string $saidaConfirmadaAltas;

        public function __construct(string $cnes, string $ocupacaoSuspeitoCli,
            string $ocupacaoSuspeitoUti, string $ocupacaoConfirmadoCli, string $ocupacaoConfirmadoUti,
            string $ocupacaoCovidUti, string $ocupacaoCovidCli, string $ocupacaoHospitalarUti,
            string $ocupacaoHospitalarCli, string $saidaSuspeitaObitos, string $saidaSuspeitaAltas,
            string $saidaConfirmadaObitos, string $saidaConfirmadaAltas, int  $id=0 )
        {
            $this->cnes= $cnes;
            $this->ocupacaoSuspeitoCli= $ocupacaoSuspeitoCli;
            $this->ocupacaoSuspeitoUti= $ocupacaoSuspeitoUti;
            $this->ocupacaoConfirmadoCli= $ocupacaoConfirmadoCli;
            $this->ocupacaoConfirmadoUti= $ocupacaoConfirmadoUti;
            $this->ocupacaoCovidUti= $ocupacaoCovidUti;
            $this->ocupacaoCovidCli= $ocupacaoCovidCli;
            $this->ocupacaoHospitalarUti= $ocupacaoHospitalarUti;
            $this->ocupacaoHospitalarCli= $ocupacaoHospitalarCli;
            $this->saidaSuspeitaObitos= $saidaSuspeitaObitos;
            $this->saidaSuspeitaAltas= $saidaSuspeitaAltas;
            $this->saidaConfirmadaObitos= $saidaConfirmadaObitos;
            $this->saidaConfirmadaAltas= $saidaConfirmadaAltas;
            $this->id= $id;
            
        }
        
        public function setarId(int $id)
        {
            $this->id = $id;
        }

        public function setarCnes(string $cnes)
        {
            $this->cnes = $cnes;
        }

        public function setarOcupacaoSuspeitoCli(string $ocupacao)
        {
            $this->ocupacaoSuspeitoCli = $ocupacao;
        }

        public function setarOcupacaoSuspeitoUti(string $ocupacao)
        {
            $this->ocupacaoSuspeitoUti = $ocupacao;
        }

        public function setarOcupacaoConfirmadoCli(string $ocupacao)
        {
            $this->ocupacaoConfirmadoCli = $ocupacao;
        }

        public function setarOcupacaoConfirmadoUti(string $ocupacao)
        {
            $this->ocupacaoConfirmadoUti = $ocupacao;
        }
    
        public function setarOcupacaoCovidUti(string $ocupacao)
        {
            $this->ocupacaoCovidUti = $ocupacao;
        }
       
        public function setarOcupacaoCovidCli(string $ocupacao)
        {
            $this->ocupacaoCovidCli = $ocupacao;
        }

        public function setarOcupacaoHospitalarUti(string $ocupacao)
        {
            $this->ocupacaoHospitalarUti = $ocupacao;
        }


        public function setarOcupacaoHospitalarCli (string $ocupacao)
        {
            $this->ocupacaoHospitalarCli = $ocupacao;

        }

        public function setarSaidaSuspeitaObitos (string $suspeito)
        {
            $this->saidaSuspeitaObitos = $suspeito;
        }

        public function setarSaidaSuspeitaAltas (string $suspeito)
        {
            $this->saidaSuspeitaAltas = $suspeito;
        } 

        public function setarSaidaConfirmadaObtidos (string $obtido)
        {
            $this->saidaConfirmadaObitos = $obtido;
        }

        public function setarSaidaConfirmadaAltas (string $altas)
        {
            $this->saidaConfirmadaAltas = $altas;
        }

        public function recuperarId()
        {
            return $this->id;
        }

        public function recuperarCnes()
        {
            return $this->cnes;
        }

        public function recuperarOcupacaoSuspeitoCli()
        {
            return $this->ocupacaoSuspeitoCli;
        }

        public function recuperarOcupacaoSuspeitoUti()
        {
            return $this->ocupacaoSuspeitoUti;
        }

        public function recuperarOcupacaoConfirmadoCli()
        {
            return $this->ocupacaoConfirmadoCli;
        }

        public function recuperarOcupacaoConfirmadoUti()
        {
            return $this->ocupacaoConfirmadoUti;
        }

        public function recuperarOcupacaoCovidUti()
        {
            return $this->ocupacaoCovidUti;
        }

        public function recuperarOcupacaoCovidCli()
        {
            return $this->ocupacaoCovidCli;
        }

        public function recuperarOcupacaoHospitalarUti()
        {
            return $this->ocupacaoHospitalarUti;
        }

        public function recuperarOcupacaoHospitalarCli()
        {
            return $this->ocupacaoHospitalarCli;
        }

        public function recuperarSaidaSuspeitaObitos()
        {
            return $this->saidaSuspeitaObitos;
        }

        public function recuperarSaidaSuspeitaAltas()
        {
            return $this->saidaSuspeitaAltas;
        }

        public function recuperarSaidaConfirmadaObitos()
        {
            return $this->saidaConfirmadaObitos;
        }

        public function recuperarSaidaConfirmadaAltas()
        {
            return $this->saidaConfirmadaAltas;
        }

    }
?>