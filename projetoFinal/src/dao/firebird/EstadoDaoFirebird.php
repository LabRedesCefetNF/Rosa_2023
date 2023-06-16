<?php
set_include_path('/var/www/html/projetoFinal/src/interfaces/');

require_once 'InterfacePDO.php';
require_once '/var/www/html/projetoFinal/src/utils/utils.php';

use interfaces\InterfacePDO;

    class EstadoDaoFirebird implements InterfacePDO
    {
        private $conexao = null;

        public function __construct(PDO $conexao)
        {
            $this->conexao = $conexao;
        }

        public function inserirCifrado(Estado $estado, string $chave)
        {
            $sqlInsert = null;
            $nome = paddingIn($estado->recuperarNome());
            $sqlInsert = "INSERT INTO estado (nome) VALUES (ENCRYPT(:nome USING aes MODE OFB key :chave iv '0123456789123456'))";

            try{
                  
                    $stmt = $this->conexao->prepare($sqlInsert);
                    $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
                    $stmt->bindValue(':chave', $chave, PDO::PARAM_STR);
                   //$stmt->bindValue(':iv', $iv, PDO::PARAM_STR);
                    $stmt->execute(); 
                    if(!$stmt){
                        die("\nFalha ao executar comando para inserir estado cifrado com AES no modo OFB no firebird" );
                    }
                    if($stmt->rowCount()>0){
                       // echo "\nSucesso ao inserir estado cifrado com AES no  modo OFB no firebird";
                        return true;
                    }
                }
                catch(PDOException $e){
                    die('Falha ao inserir estado cifrado com AES usando modo OFB' . $e->getMessage());
                }
                
            }

            public function buscarUltimo ( string $chave = null) 
            {
                $sqlSelect = null;
                $estado = null;
                try{
                    $sqlSelect = "SELECT id, DECRYPT(nome using aes mode OFB key :chave iv '0123456789123456') as nome
                            from estado where id = (select max(id) from estado)";
                    $ps = $this->conexao->prepare($sqlSelect);
                    $ps->bindParam('chave', $chave, PDO::PARAM_STR);
                    $ps->execute();
                    if(!$ps){
                        die("\nFalha ao executar buscar por último estado cifrado com  AES modo OFB no Firebird. ");
                    }
                    $linha = $ps->fetchAll();
                    foreach($linha as $l){
                        $estado = new Estado(paddingOut($l[1]), $l[0]); // paddingOut retira lixo que vem junto do resultado, implementação está no utils.php
                    }
                   // echo "\nUltimo estado cifrado no firebird retornado com sucesso";
                    return $estado;
                
                }
                catch(PDOException $e){
                    die("Falha ao buscar último estado inserido cifrado com AES e modo de operação OFB no firebird. ". $e->getMessage());
                }
            }

            public function buscarPorId(int $id, string $chave = null)
            {
                $sqlSelect = null;
                $estado = null;
                try{
                    $sqlSelect = "SELECT DECRYPT(nome using aes mode OFB key :chave iv '0123456789123456') as nome 
                                    from estado where id = :id";
                    $ps = $this->conexao->prepare($sqlSelect);
                    $ps->bindParam('chave', $chave, PDO::PARAM_STR);
                    $ps->bindValue('id', $id, PDO::PARAM_INT);
                    $ps->execute();
                    if(!$ps){
                        die("\nFalha ao executar comando para buscar estado cifrado com AES modo OFB por id no fiebird.");
    
                    }
                    $linha = $ps->fetchAll();
                    foreach($linha as $l){
                        $estado = new Estado(paddingOut($l[0]), $id);
                    }
                    //echo "\nSucesso ao buscar estado cifrado com AES modo OFB no firebird por id";
                    return $estado;
                }
                catch(PDOException $e){
                    die("Falha ao buscar estado cifrado com AES modo OFB no firebird por id " . $e->getMessage() );
                }
            }

            public function alterar(string $chave, int $id) //rever sql
            {
                $sqlUpdate = null;
                $alagoas = bin2hex("Alagoas");
                try{
                    $sqlUpdate = "UPDATE estado
                    set nome = ENCRYPT(:nome1 using aes mode OFB key :key iv '0123456789123456')
                    where id in (select e.id 
                                  from notificacao n, estado e
                                  where n.id_estado = e.id
                                  and (select DECRYPT(nome using aes mode OFB key :key2 iv '0123456789123456') as nome
                                        from estado where id = :idEstado)  
                                      not like :nome2
                                      and e.id = :id
                                  )";
                    $ps = $this->conexao->prepare($sqlUpdate);
                    $ps->bindParam('key', $chave, PDO::PARAM_STR);
                    $ps->bindParam('key2', $chave, PDO::PARAM_STR);
                    $ps->bindParam('nome1', $alagoas, PDO::PARAM_STR);
                    $ps->bindParam('nome2', $alagoas, PDO::PARAM_STR);
                    $ps->bindParam('idEstado', $id, PDO::PARAM_INT);
                    $ps->bindParam('id', $id, PDO::PARAM_INT);

                    
                    $ok = $ps->execute();
                    if(!$ok){
                        die("\nFalha ao executar comando de alterar estado cifrado com AES modo OFB no firebird. ");
                    }
                    //echo "\nSucesso ao alterar estado cifrado com AES modo OFB no firebird. ";
                    return true;
                }
                catch(PDOException $e){
                    die("\nFalha ao alterar estado cifrado com AES modo OFB no firebird. " . $e->getMessage());
                }
            }
           public function  deletar(int $id, string $chave = null)
            {
                $sqlDelete = null;
                try{
                    $sqlDelete = "DELETE from estado
                                where id  = :id";
                    $ps = $this->conexao->prepare($sqlDelete);
                    $ps->bindParam('id', $id, PDO::PARAM_INT);
                    $ok = $ps->execute();
                    if(!$ok){
                        die("\nFalha ao deletar estado cifrado com AES no Firebird");
                    }
                    //echo "\Sucesso ao deletar estado cifrado com AES no Firebird";
                }
                catch(PDOException $e){
                    die("\nFalha ao deletar estado cifrado com AES no Firebird. " .$e->getMessage());
                }
            }
    }
?>