# aplicacaoTCC
Essa foi a aplicação utilizada no meu Trabalho de Conclusão de Curso para realizar a conexão com os SGBDs e executar os experimentos

*A conexão com os SGBDs é configurada nas classes ConnectionCreator de cada SGBD. Atualmente estão configuradas para localhost. 

*A aplicação tem como finalidade ler o conteúdo de um arquivo .csv e inserir na base de dados. Para realizar os testes, foram feitos scripts em shell script
que executam as classes de serviço de inserção, leitura, alteração e remoção dos dados na base que o script referencia. Existe um script para
operação com dados em claro e um script aplicando funções criptográficas sobre os dados a serem inseridos para cada um dos SGBDs.

*O arquivo .csv utilizado para leitura da aplicação é um conjunto de dados públicos, disponível em :https://opendatasus.saude.gov.br/dataset/registro-de-ocupacao-hospitalar-covid-19/resource/1a1e9932-253f-441b-8d9c-aaf35f3022a0
No projeto, esse arquivo desse ser colocado no diretório src. A versão utilizada no projeto teve o nome do arquivo alterado para "LeitoOcupacao_2021.csv",
dessa forma, as services de inserção de dados fazem referência a esse nome. Caso utilize outro nome para o arquivo, há necessidade da modificação delas.
O arquivo não foi disponibilizado junto ao projeto por ultrapassar o tamanho permitido pelo Github.

*As estruturas das tabelas utilizadas nos bancos estão disponíveis em: https://github.com/ergor86/baseDeDados

*Para realizar os experimentos, após feita a configuração do ambiente, deve-se navegar via terminal até o direórtio raiz do projeto é digitar
"./ nome_do_script <quantidade de linhas a serem lidas do arquivo .csv>"
  ex: ./firebirdClaro.sh 1000
  
  Os scripts para execução dos experimentos se encontram no diretório raiz do projeto.
