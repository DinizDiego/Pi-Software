<?php

class Docente
    {

        private $pdo;

        public function __construct()
        {
            $this->pdo = Conexao::conexao();               
        }

        /**
         * Listar todos os docentes
         * @return array
         */
        public function listar(){
            $sql = $this->pdo->prepare('SELECT * FROM docentes');        
            $sql->execute();
        
            return $sql->fetchAll(PDO::FETCH_OBJ);
        }      

        /**
         * Cadastra um novo docente
         * @param array $dados    
         * @return void
         */
        public function cadastrar(array $dados)
        {
            $sql = $this->pdo->prepare('INSERT INTO docentes (nome, data_nascimento, telefone, cep, turno, codigo_barras, cpf) VALUES (:nome, :data_nascimento, :telefone, :cep, :turno, :codigo_barras, :cpf)');
            
            $nome = $dados['nome'];
            $data_nascimento = $dados['data_nascimento'];
            $telefone = $dados['telefone'];
            $cep = $dados['cep'];
            $turno = $dados['turno'];
            $codigo_barras = $dados['codigo_barras'];
            $cpf = $dados['cpf'];

            $sql->bindParam(':nome', $nome);
            $sql->bindParam(':data_nascimento', $data_nascimento);
            $sql->bindParam(':telefone', $telefone);
            $sql->bindParam(':cep', $cep);
            $sql->bindParam(':turno', $turno);
            $sql->bindParam(':codigo_barras', $codigo_barras);
            $sql->bindParam(':cpf', $cpf);
            $sql->execute();

            return header('location: index.php?sc=true');
        }

        /**
         * Retorna os dados de um docente
         * @param int $id_docente
         * @return object
         */
        public function mostrar(int $id_docente)
        {
            $sql = $this->pdo->prepare('SELECT * FROM docentes WHERE id_docente = :id_docente LIMIT 1');
            $sql->bindParam(':id_docente', $id_docente);
            $sql->execute();
            
            return $sql->fetch(PDO::FETCH_OBJ);
        }

        /**
         * Atualiza um determinado docente
         * @param array $dados   
         * @return void
         */
        public function editar(array $dados)
        {
            $sql = $this->pdo->prepare("UPDATE docentes SET nome = :nome, cpf = :cpf WHERE id_docente = :id_docente");

            $id_docente = $dados['id_docente'];
            $nome = $dados['nome'];
            $data_nascimento = $dados['data_nascimento'];
            $telefone = $dados['telefone'];
            $cep = $dados['cep'];
            $turno = $dados['turno'];
            $codigo_barras = $dados['codigo_barras'];
            $cpf = $dados['cpf'];

            $sql->bindParam(':id_docente', $id_docente);
            $sql->bindParam(':nome', $nome);
            $sql->bindParam(':data_nascimento', $data_nascimento);
            $sql->bindParam(':telefone', $telefone);
            $sql->bindParam(':cep', $cep);
            $sql->bindParam(':turno', $turno);
            $sql->bindParam(':codigo_barras', $codigo_barras);
            $sql->bindParam(':cpf', $cpf);
            $sql->execute();
        }

        /**
         * Exclui um docente
         * @param int $id_docente
         * @return void
         */
        public function excluir(int $id_docente)
        {
            $sql = $this->pdo->prepare("DELETE FROM docentes WHERE id_docente = :id_docente");
            $sql->bindParam(':id_docente', $id_docente);
            $sql->execute();
        }
    }

?>
