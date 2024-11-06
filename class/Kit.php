<?php

class Kit {

    # ATRIBUTOS	
	public $pdo;
    
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    /**
     * Listar todos os kits
     * @return array
     * @example $variavel = $Obj->listar()
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM kits');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }      

    /**
     * Cadastrar um novo kit
     * @param array $dados    
     * @return int
     * @example $Obj->cadastrar($_POST);
     * 
     */
    public function cadastrarKit(array $dados)
    {
        $sql = $this->pdo->prepare('INSERT INTO kits 
                                    (n_sala, situacao, codigo_barras_kit, descricao)
                                    VALUES
                                    (:n_sala, :situacao, :codigo_barras_kit, :descricao)
                                ');

        $n_sala = $dados['n_sala'];
        $situacao = 1;
        $descricao = $dados['descricao'];
        $codigo_barras_kit = $dados['codigo_barras_kit'];

        $sql->bindParam(':n_sala', $n_sala);
        $sql->bindParam(':situacao', $situacao);
        $sql->bindParam(':descricao', $descricao);
        $sql->bindParam(':codigo_barras_kit', $codigo_barras_kit);
        $sql->execute();

        
        return header('location: index.php?sc=true');
    }

    /**
     * Retorna os dados de um kit
     * @param int $id_kit
     * @return object
     * @example $variavel = $Obj->mostrar($id_kit);
     */
    public function mostrar(int $id_kit)
    {
    	// Montar o SELECT ou o SQL
    	$sql = $this->pdo->prepare('SELECT * FROM kits WHERE id_kit = :id_kit LIMIT 1');
        $sql->bindParam(':id_kit', $id_kit);
    	// Executar a consulta
    	$sql->execute();
    	// Pega os dados retornados
        // Como será retornado apenas UM kit, usamos fetch.
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    public function mostrarObservacao(int $id_kit)
    {
        try {
            // Consulta SQL para obter a observação do kit
            $sql = $this->pdo->prepare('SELECT observacao FROM kits WHERE id_kit = :id_kit LIMIT 1');
            $sql->bindParam(':id_kit', $id_kit);
            $sql->execute();
            
            // Recupera o resultado da consulta
            $dados = $sql->fetch(PDO::FETCH_OBJ);

            // Verifica se a observação foi encontrada
            if ($dados && isset($dados->observacao)) {
                return $dados->observacao;  // Retorna a observação
            } else {
                return 'Nenhuma observação encontrada para este kit.';  // Caso não exista observação
            }
        } catch (Exception $e) {
            // Se houver um erro na consulta, captura a exceção
            return 'Erro ao buscar a observação: ' . $e->getMessage();
        }
    }   


    /**
     * Atualiza um determinado kit
     *
     * @param array $dados   
     * @return int id - do ITEM
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE kits SET
            n_sala = :n_sala             
        WHERE id_kit = :id_kit
        ");

        $n_sala = $dados['n_sala'];
        $id_kit = $dados['id_kit'];

        $sql->bindParam(':n_sala', $n_sala);  
        $sql->bindParam(':id_kit', $id_kit);

        $sql->execute();
    }

    /**
     * Excluir kit
     *
     * @param integer $id_kit
     * @return void (esse metodo não retorna nada)
     */
    public function excluir(int $id_kit)
    {
        $sql = $this->pdo->prepare("DELETE FROM kits WHERE id_kit = :id_kit");

        $sql->bindParam(':id_kit', $id_kit);

        $sql->execute();
    }

    public function obterDocenteEmUso(int $id_kit)
    {
        $sql = $this->pdo->prepare('
            SELECT d.nome 
            FROM entradas_saidas es
            JOIN docentes d ON es.id_docente = d.id_docente
            WHERE es.id_kit = :id_kit AND es.data_entrada IS NULL
        ');
        
        $sql->bindParam(':id_kit', $id_kit);
        $sql->execute();

        // Pega os dados retornados
        $docente = $sql->fetch(PDO::FETCH_OBJ);

        // Retorna o nome do docente se existir, ou null se não houver
        return $docente ? $docente->nome : null;
    }

    public function contarKitsDisponiveis()
    {
        $sql = $this->pdo->prepare('SELECT COUNT(*) as total FROM kits WHERE situacao = 1');
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        return $resultado->total;
    }

    public function contarKitsIndisponiveis()
    {
        $sql = $this->pdo->prepare('SELECT COUNT(*) as total FROM kits WHERE situacao = 2');
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_OBJ);
        return $resultado->total;
    }


    
 }

?>
