<?php

class Usuario {

    # ATRIBUTOS	
	public $pdo;
    
    public function __construct()
    {
        $this->pdo = Conexao::conexao();               
    }

    /**
     * listar todos os usuarios
     * @return array
     * @example $variavel = $Obj->metodo()
     */
    public function listar(){
        $sql = $this->pdo->prepare('SELECT * FROM usuarios');        
        $sql->execute();
    
        $dados = $sql->fetchAll(PDO::FETCH_OBJ);
    
        // Retorna os dados como JSON
        return $dados;
    }      

    // Função para validar o CPF (PHP)
    public function validarCPF($cpf) {
        // Remove caracteres não numéricos
        $cpf = preg_replace('/\D/', '', $cpf);

        // Verifica se o CPF tem 11 dígitos e não é uma sequência repetida
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Validação do 1º dígito
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;

        if ($cpf[9] != $digito1) {
            return false;
        }

        // Validação do 2º dígito
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;

        return $cpf[10] == $digito2;
    }

    public function validarCEP($cep)
    {
        // Expressão regular para verificar o formato do CEP
        return preg_match('/^[0-9]{5}-?[0-9]{3}$/', $cep);
    }

    /**
     * cadastra um novo usuario
     * @param Array $dados    
     * @return int
     * @example $Obj->cadastrar($_POST);
     * 
     */
    public function cadastrar(Array $dados)
    {
        $cep = $dados['cep'];

        // Verifica se o CEP tem o formato correto
        if (!$this->validarCEP($cep)) {
            throw new Exception("O formato do CEP está incorreto.");
        }

        // Verifica se o CPF é válido
        if (!$this->validarCPF($dados['cpf'])) {
            throw new Exception("CPF inválido.");
        }

        $sql = $this->pdo->prepare('INSERT INTO usuarios 
                                    (id_cargo, nome, data_nascimento, telefone, cep, turno, login, senha, codigo_barras, cpf)
                                    VALUES
                                    (:id_cargo, :nome, :data_nascimento, :telefone, :cep, :turno, :login, :senha, :codigo_barras, :cpf)
                                ');

        $id_cargo = 1;
        $nome = $dados['nome'];
        $data_nascimento = $dados['data_nascimento'];
        $telefone = $dados['telefone'];
        $cep = $dados['cep'];
        $turno = $dados['turno'];
        $login = $dados['login'];
        $senha = $dados['senha'];
        $codigo_barras = $dados['codigo_barras']; 
        $cpf = $dados['cpf'];   
        
        $salt = '123'; 
        $senha = crypt($senha, $salt);
                
        $sql->bindParam(':id_cargo', $id_cargo);                 
        $sql->bindParam(':nome', $nome);
        $sql->bindParam(':data_nascimento', $data_nascimento);
        $sql->bindParam(':telefone', $telefone);
        $sql->bindParam(':cep', $cep);
        $sql->bindParam(':turno', $turno);
        $sql->bindParam(':login', $login);
        $sql->bindParam(':senha', $senha);
        $sql->bindParam(':codigo_barras', $codigo_barras);
        $sql->bindParam(':cpf', $cpf);
        $sql->execute();

        return header('location: login.php');
    }

    /**
     * Retorna os dados de um ITEM
     * @param int $id_do_item
     * @return object
     * @example $variavel = $Obj->mostrar($id_do_item);
     */
    public function mostrar(int $id_usuario)
    {
    	// Montar o SELECT ou o SQL
    	$sql = $this->pdo->prepare('SELECT * FROM cargos WHERE id_usuario = :id_usuario LIMIT 1');
        $sql->bindParam(':id_usuario', $id_usuario);
    	// Executar a consulta
    	$sql->execute();
    	// Pega os dados retornados
        // Como será retornado apenas UM tabela usamos fetch. para
    	$dados = $sql->fetch(PDO::FETCH_OBJ);
    	return $dados;
    }

    /**
     * Atualiza um determinado usuario
     *
     * @param array $dados   
     * @return int id - do ITEM
     * @example $Obj->editar($_POST);
     */
    public function editar(array $dados)
    {
        $sql = $this->pdo->prepare("UPDATE usuarios SET
            id_cargo = :id_cargo,
            nome = :nome,
            data_nascimento = :data_nascimento,
            telefone = :telefone,
            cep = :cep,
            turno = :turno,
            login = :login,
            senha = :senha,
            codigo_barras = :codigo_barras,
            cpf = :cpf,
             
        WHERE id_usuario = :id_usuario
        ");

        $id_cargo = $dados['id_cargo'];
        $id_usuario = $dados['id_usuario'];
        $id_cargo = $dados['id_cargo'];
        $nome = $dados['nome'];
        $data_nascimento = $dados['data_nascimento'];
        $telefone = $dados['telefone'];
        $cep = $dados['cep'];
        $turno = $dados['turno'];
        $login = $dados['login'];
        $senha = $dados['senha'];
        $codigo_barras = $dados['codigo_barras'];
        $cpf = $dados['cpf'];

        $sql->bindParam(':id_cargo',$id_cargo);  
        $sql->bindParam('id_usuario',$id_usuario);
        $sql->bindParam('id_cargo',$id_cargo);
        $sql->bindParam('nome',$nome);
        $sql->bindParam('data_nascimento',$data_nascimento);
        $sql->bindParam('telefone',$telefone);
        $sql->bindParam('cep',$cep);
        $sql->bindParam('turno',$turno);
        $sql->bindParam(':login', $login);
        $sql->bindParam(':senha', $senha);
        $sql->bindParam('codigo_barras',$codigo_barras);
        $sql->bindParam(':cpf', $cpf);

        $sql->execute();
    }

    /**
     * Excluir ITEM
     *
     * @param integer $id_usuario
     * @return void (esse metodo não retorna nada)
     */
    public function excluir(int $id_usuario)
    {
        $sql = $this->pdo->prepare("DELETE FROM usuarios WHERE id_usuario = :id_usuario");

        $sql->bindParam('id_usuario',$id_usuario);

        $sql->execute();
    }

    public function logar($login, $senha)
    {
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE login = :login AND senha = :senha');
        $sql->bindParam(':login', $login);

        $salt = '123'; 
        $senha = crypt($senha, $salt);

        $sql->bindParam(':senha', $senha);
        $sql->execute();

        $user = $sql->fetch(PDO::FETCH_OBJ);


        session_start();

        if ($user) {
            $_SESSION['logado'] = true;
            $_SESSION['nome'] = $user->nome;
            $_SESSION['id_usuario'] = $user->id_usuario;

            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error'] = 'Usuário ou senha incorreta';
            header('Location: login.php');
            exit();
        }
    }

 }
?>