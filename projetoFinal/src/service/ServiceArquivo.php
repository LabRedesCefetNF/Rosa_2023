<?php




class ServiceArquivo {
    private $arquivoCsv;
    private $arquivoQtdLinhas;

    public function __construct(string $caminhoArquivo){
        $this->arquivoCsv = new SplFileObject($caminhoArquivo);
    }

    public function lerQuantidadeLinhas(string $caminhoScript)
    {
        //$this->arquivoQtdLinhas = new SplFileObject($caminhoScript);
    }

    public function retornaConteudoCsv(int $quantidadeLinhas) 
    {   $conteudoArquivo =[];
        for($i=1;$i<=$quantidadeLinhas;$i++){
            $linha = $this->arquivoCsv->fgetcsv(',');
            $conteudoArquivo[$i] =[
                ' ' => $linha[0],
                'id' => $linha[1],
                'dataNotificacao' => $linha[2],
                'cnes' => $linha[3],
                'ocupacaoSuspeitoCli' => $linha[4],
                'ocupacaoSuspeitoUti' => $linha[5],
                'ocupacaoConfirmadoCli' => $linha[6],
                'ocupacaoConfirmadoUti' => $linha[7],
                'ocupacaoCovidUti' => $linha[8],
                'ocupacaoCovidCli' => $linha[9],
                'ocupacaoHospitalarUti' => $linha[10],
                'ocupacaoHospitalarCli' => $linha[11],
                'saidaSuspeitaObitos' => $linha[12],
                'saidaSuspeitaAltas' => $linha[13],
                'saidaConfirmadaObitos' => $linha[14],
                'saidaConfirmadaAltas' => $linha[15],
                'origem' => $linha[16],
                'usuario' => $linha[17],
                'estadoNotificacao' =>  $linha[18],
                'municipioNotificacao' => $linha[19],
                'estado' => $linha[20],
                'municipio' => $linha[21],
                'excluido' => $linha[22],
                'validado' => $linha[23],
                'created_at' => $linha[24],
                'updated_at' => $linha[25]

            ];
        }
        return $conteudoArquivo;
    }

    public function retornaQtdLinhas($caminhoScript)
    {
        $this->arquivoQtdLinhas = new SplFileObject($caminhoScript, "r");
        $qtd = $this->arquivoQtdLinhas->fread($this->arquivoQtdLinhas->getSize());

        return  ($qtd);

    }

}
?>