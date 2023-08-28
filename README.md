# Aplicacao utilizada nos testes do artigo "Uma análise de desempenho de funções de encriptação nativas de SGDBs Open Source"
Essa foi a aplicação utilizada para realizar a conexão com os SGBDs e executar os experimentos. Ela se encontra no diretório 'projetoFinal'.

*A conexão com os SGBDs é configurada nas classes ConnectionCreator de cada SGBD. Atualmente estão configuradas para localhost.  

*A aplicação tem como finalidade ler o conteúdo de um arquivo .csv e inserir na base de dados. Para realizar os testes, foram feitos scripts em shell script
que executam as classes de serviço de inserção, leitura, alteração e remoção dos dados na base que o script referencia. Existe um script para
operação com dados em claro e um script aplicando funções criptográficas sobre os dados a serem inseridos para cada um dos SGBDs.

*O arquivo .csv utilizado para leitura da aplicação é um conjunto de dados públicos, disponível em :https://opendatasus.saude.gov.br/dataset/registro-de-ocupacao-hospitalar-covid-19/resource/1a1e9932-253f-441b-8d9c-aaf35f3022a0
No projeto, esse arquivo desse ser colocado no diretório src. A versão utilizada no projeto teve o nome do arquivo alterado para "LeitoOcupacao_2021.csv",
dessa forma, as services de inserção de dados fazem referência a esse nome. Caso utilize outro nome para o arquivo, há necessidade da modificação delas.
O arquivo não foi disponibilizado junto ao projeto por ultrapassar o tamanho permitido pelo Github. Ele está anexado nesse projeto com o nome 'esus-vepi.LeitoOcupacao_2021.csv', sendo necessário alterar o nome após a extração para LeitoOcupacao_2021.csv.

*Os arquivos com o dump das estruturas das tabelas utilizadas nos SGBDs estão disponíveis no diretório 'baseDeDados'.

*Para realizar os experimentos, após feita a configuração do ambiente, deve-se navegar via terminal até o direórtio raiz do projeto é digitar
"./ nome_do_script <quantidade de linhas a serem lidas do arquivo .csv>"
  ex: ./firebirdClaro.sh 1000
  
  Os scripts para execução dos experimentos se encontram no diretório raiz do projeto. Os testes foram realizados em uma máquina virtual para cada SGBD. O sistema operacional que os experimentos forma executados para realização dos experimentos foi o Debian GNU/Linux 11. Para que os scripts sejam executados, é necessário instalar a ferramenta Atop, disponível em : https://www.atoptool.nl/ As máquinas contavam com 4GB de memória RAM e foram rodadas num cluster, pertencente ao laboratório de Redes do CEFET/RJ - campus Nova Friburgo, que possui as as CPUs: 4 x Intel(R) Core(TM) i5-3470 CPU @ 3.20GHz (1 Socket) — 8 x Intel(R) Xeon(R) CPU E5450 @ 3.00GHz (2 Sockets). Para os experimentos, a CPU foi limitada em uso de apenas um núcleo.


