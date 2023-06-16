#! /bin/bash
BD="POSTGRESQL-Claro"
TIME=`date +%y%m%d%H%M%S`
LOGFILE="../../../log/$BD-$TIME"
REGISTROS=$1
echo -n $REGISTROS > config/rounds.conf
######################## INSERT ##############
#iniciando o monitor de consumo
atop -M 1 10000 > /tmp/atop &
pid=$!
#executando o experimento
echo "[$BD] INSERT (Registros = $REGISTROS)"
cd src/service/postgresql/
start=`date +%s.%N`
php ServiceInsercaoClaroPostgreSql.php
end=`date +%s.%N`
runtime=$( echo "$end - $start" | bc -l )
# parando o monitor de consumo
kill $pid

#gerando relatório
echo "Registros: $REGISTROS" >> $LOGFILE
echo "Tempo de execução INSERT: $runtime" >> $LOGFILE
egrep '^MEM' /tmp/atop >> $LOGFILE-INSERT-MEM.csv
egrep '^CPU' /tmp/atop >> $LOGFILE-INSERT-CPU.csv
egrep '^SWP' /tmp/atop >> $LOGFILE-INSERT-SWP.csv


######################################### BUSCA
#iniciando o monitor de consumo
atop -M 1 10000 > /tmp/atop &
pid=$!
#executando o experimento
echo "Iniciando a busca"
start=`date +%s.%N`
php ServiceBuscarNotificacoesClaroPostgreSql.php
end=`date +%s.%N`
runtime=$( echo "$end - $start" | bc -l )
# parando o monitor de consumo
kill $pid

#gerando relatório
echo "Tempo de execução BUSCA: $runtime" >> $LOGFILE
egrep '^MEM' /tmp/atop >> $LOGFILE-SELECT-MEM.csv
egrep '^CPU' /tmp/atop >> $LOGFILE-SELECT-CPU.csv
egrep '^SWP' /tmp/atop >> $LOGFILE-SELECT-SWP.csv


######################################### ALTERACAO
#iniciando o monitor de consumo
atop -M 1 10000 > /tmp/atop &
pid=$!
#executando o experimento
echo "Iniciando a fase de alteracao"
start=`date +%s.%N`
php ServiceAlterarEstadoClaroPostgreSql.php
end=`date +%s.%N`
runtime=$( echo "$end - $start" | bc -l )
# parando o monitor de consumo
kill $pid

#gerando relatório
echo "Tempo de execução ALTERACAO: $runtime" >> $LOGFILE
egrep '^MEM' /tmp/atop >> $LOGFILE-UPDATE-MEM.csv
egrep '^CPU' /tmp/atop >> $LOGFILE-UPDATE-CPU.csv
egrep '^SWP' /tmp/atop >> $LOGFILE-UPDATE-SWP.csv

######################################### DELETE
#iniciando o monitor de consumo
atop -M 1 10000 > /tmp/atop &
pid=$!
#executando o experimento
echo "Iniciando a fase de DELETE"
start=`date +%s.%N`
php ServiceDeletarNotificacoesClaroPostgreSql.php
end=`date +%s.%N`
runtime=$( echo "$end - $start" | bc -l )
# parando o monitor de consumo
kill $pid

#gerando relatório
echo "Tempo de execução DELETE: $runtime" >> $LOGFILE
egrep '^MEM' /tmp/atop >> $LOGFILE-DELETE-MEM.csv
egrep '^CPU' /tmp/atop >> $LOGFILE-DELETE-CPU.csv
egrep '^SWP' /tmp/atop >> $LOGFILE-DELETE-SWP.csv
