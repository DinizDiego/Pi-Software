<?php

class Entrada_saida {

    # ATRIBUTOS	
	public $pdo;
    
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    /**
     * Listar todas as entradas e saídas
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM entrada_saida');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }      

    /**
     * Cadastrar uma nova entrada e saída
     * @param array $dados    
     * @return int
     * @example $Obj->cadastrar($_POST);
     * 
     */
    public function cadastrar(array $dados)
    {
        $sql = $this->pdo->prepare('INSERT INTO entrada_saida 
                                    (id_usuario, id_kit, data_entrada, data_saida)
                                    VALUES
                                    (:id_usuario, :id_kit, :data_entrada, :data_saida)
                                ');

        $sql->bindParam(':id_usuario', $dados['id_usuario']);
        $sql->bindParam(':id_kit', $dados['id_kit']);
        $sql->bindParam(':data_entrada', $dados['data_entrada']);
        $sql->bindParam(':data_saida', $dados['data_saida']);
        $sql->execute();
    }

    /**
     * Retorna os dados de uma entrada e saída
     * @param int $id_entrada_saida
     * @return object
     * @example $variavel = $Obj->mostrar($id_entrada_saida);
     */
    public function mostrar(int $id_entrada_saida)
    {
    	// Montar o SELECT ou o SQL
    	$sql = $this->pdo->prepare('SELECT * FROM entrada_saida WHERE id_entrada_saida = :id_entrada_saida LIMIT 1');
        $sql->bindParam(':id_entrada_saida', $id_entrada_saida);
    	// Executar a consulta
    	$sql->execute();
    	// Pega os dados retornados
        // Como será retornado apenas UMA entrada e saída, usamos fetch.
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    /**
     * Atualiza uma determinada entrada e saída
     *
     * @param array $dados   
     * @return int id - do ITEM
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE entrada_saida SET
            id_usuario = :id_usuario,
            id_kit = :id_kit,
            data_entrada = :data_entrada,
            data_saida = :data_saida             
        WHERE id_entrada_saida = :id_entrada_saida
        ");

        $id_usuario = $dados['id_usuario'];
        $id_kit = $dados['id_kit'];
        $data_entrada = $dados['data_entrada'];
        $data_saida = $dados['data_saida'];
        $id_entrada_saida = $dados['id_entrada_saida'];

        $sql->bindParam(':id_usuario', $id_usuario);  
        $sql->bindParam(':id_kit', $id_kit);
        $sql->bindParam(':data_entrada', $data_entrada);
        $sql->bindParam(':data_saida', $data_saida);
        $sql->bindParam(':id_entrada_saida', $id_entrada_saida);

        $sql->execute();
    }

    /**
     * Excluir entrada e saída
     *
     * @param integer $id_entrada_saida
     * @return void (esse metodo não retorna nada)
     */
    public function excluir(int $id_entrada_saida)
    {
        $sql = $this->pdo->prepare("DELETE FROM entrada_saida WHERE id_entrada_saida = :id_entrada_saida");

        $sql->bindParam(':id_entrada_saida', $id_entrada_saida);

        $sql->execute();
    }

    public function retirar($dados)
    {
        $codigo_barras = $dados['codigo_barras'];
        $codigo_barras_kit = $dados['codigo_barras_kit'];
        $observacao_saida = $dados['observacao_saida'];
        $data_saida = date("Y-m-d H:i:s");

        // Verifica quantos kits disponíveis
        $sql = $this->pdo->prepare('SELECT count(id_entrada_saida) FROM kits k INNER JOIN entradas_saidas es ON k.id_kit = es.id_kit WHERE k.codigo_barras_kit = :codigo_barras_kit AND es.data_entrada IS NULL;');
        $sql->bindParam(':codigo_barras_kit', $codigo_barras_kit); // Vincula o parâmetro
        $sql->execute();
        $kitDisponivel = $sql->fetchColumn();

        // Verifica quantos docentes disponíveis
        $sql = $this->pdo->prepare('SELECT count(id_entrada_saida) FROM docentes d INNER JOIN entradas_saidas es ON d.id_docente = es.id_docente WHERE d.id_docente = :id_docente AND es.data_entrada IS NULL;');
        $sql->bindParam(':id_docente', $id_docente); // Precisamos ter o id_docente aqui
        $sql->execute();
        $docenteDisponivel = $sql->fetchColumn();

        $total = $kitDisponivel + $docenteDisponivel;

        if ($total == 0) {
            // Busca o id_docente
            $sql = $this->pdo->prepare('SELECT id_docente FROM docentes WHERE codigo_barras = :codigo_barras');    
            $sql->bindParam(':codigo_barras', $codigo_barras);
            $sql->execute();
            $id_docente = $sql->fetchColumn();

            if ($id_docente === false) {
                exit; // id_docente não encontrado
            } else {
                // Busca o id_kit
                $sql = $this->pdo->prepare('SELECT id_kit FROM kits WHERE codigo_barras_kit = :codigo_barras_kit');     
                $sql->bindParam(':codigo_barras_kit', $codigo_barras_kit);
                $sql->execute();
                $id_kit = $sql->fetchColumn();

                if ($id_kit === false) {
                    exit; // id_kit não encontrado
                } else {   
                    // Inserção na tabela
                    $sql = $this->pdo->prepare('INSERT INTO entradas_saidas (id_docente, id_kit, data_saida, observacao_saida) VALUES (:id_docente, :id_kit, :data_saida, :observacao_saida)');
                    $sql->bindParam(':id_docente', $id_docente);
                    $sql->bindParam(':id_kit', $id_kit);
                    $sql->bindParam(':data_saida', $data_saida);
                    $sql->bindParam(':observacao_saida', $observacao_saida);
                    $sql->execute();

                    // Atualiza a situação do kit
                    $sql = $this->pdo->prepare('UPDATE kits SET situacao = 2 WHERE codigo_barras_kit = :codigo_barras_kit');
                    $sql->bindParam(':codigo_barras_kit', $codigo_barras_kit);
                    $sql->execute();

                    // Exibe alerta de sucesso
                    echo "<script>alert('Retirado com sucesso');</script>";
                }
            }
        } else {
            echo "<script>alert('Kit está Indisponível!');</script>";
        }
    }

    public function devolver($dados)
    {
        $codigo_barras = $dados['codigo_barras'];
        $codigo_barras_kit = $dados['codigo_barras_kit'];
        $observacao_entrada = $dados['observacao_entrada'];
        $data_entrada = date("Y-m-d H:i:s");

        // Verifica quantos kits disponíveis
        $sql = $this->pdo->prepare('SELECT count(id_entrada_saida) FROM kits k INNER JOIN entradas_saidas es ON k.id_kit = es.id_kit WHERE k.codigo_barras_kit = :codigo_barras_kit AND es.data_entrada IS NULL;');
        $sql->bindParam(':codigo_barras_kit', $codigo_barras_kit); // Vincula o parâmetro
        $sql->execute();
        $kitDisponivel = $sql->fetchColumn();

        if ($kitDisponivel == 1) {
            // Busca o docente que está entregando
            $sql = $this->pdo->prepare('SELECT id_docente FROM docentes WHERE codigo_barras = :codigo_barras');    
            $sql->bindParam(':codigo_barras', $codigo_barras);
            $sql->execute();
            $id_docente_entregador = $sql->fetchColumn();
            
            // Busca o id_kit
            $sql = $this->pdo->prepare('SELECT * FROM kits WHERE codigo_barras_kit = :codigo_barras_kit');     
            $sql->bindParam(':codigo_barras_kit', $codigo_barras_kit);
            $sql->execute();
            $kit = $sql->fetch(PDO::FETCH_OBJ);
            $id_kit = $kit->id_kit;

            // Busca o docente que está entregando
            $sql = $this->pdo->prepare('SELECT id_docente FROM docentes WHERE id_docente = :id_docente');    
            $sql->bindParam(':id_docente', $kit->id_docente);
            $sql->execute();
            $id_docente = $sql->fetchColumn();

            if($id_docente == $id_docente_entregador)
            {
                $sql = $this->pdo->prepare('UPDATE entradas_saidas SET data_entrada = :data_entrada, observacao_entrada = :observacao_entrada WHERE id_kit = :id_kit');
                $sql->bindParam(':id_kit', $id_kit);
                $sql->bindParam(':data_entrada', $data_entrada);
                $sql->bindParam(':observacao_entrada', $observacao_entrada);
                $sql->execute();

                // Atualiza a situação do kit
                $sql = $this->pdo->prepare('UPDATE kits SET situacao = 1 WHERE codigo_barras_kit = :codigo_barras_kit');
                $sql->bindParam(':codigo_barras_kit', $codigo_barras_kit);
                $sql->execute();

                // Exibe alerta de sucesso
                echo "<script>alert('Devolvido com sucesso!');</script>";
            }else{
                $sql = $this->pdo->prepare('UPDATE entradas_saidas SET data_entrada = :data_entrada, id_docente2 = :id_docente2, observacao_entrada = :observacao_entrada WHERE id_kit = :id_kit');
                $sql->bindParam(':id_kit', $id_kit);
                $sql->bindParam(':data_entrada', $data_entrada);
                $sql->bindParam(':id_docente2', $id_docente_entregador);
                $sql->bindParam(':observacao_entrada', $observacao_entrada);
                $sql->execute();

                // Atualiza a situação do kit
                $sql = $this->pdo->prepare('UPDATE kits SET situacao = 1 WHERE codigo_barras_kit = :codigo_barras_kit');
                $sql->bindParam(':codigo_barras_kit', $codigo_barras_kit);
                $sql->execute();

                // Exibe alerta de sucesso
                echo "<script>alert('Devolvido com sucesso!');</script>";
            }
        }else{
            echo "<script>alert('Kit não pode ser devolvido!');</script>";
        }
    }
}

?>
