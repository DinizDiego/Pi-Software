<?php
    include('class/classes.php');
    
    $Usuario = new Usuario();
        
    if(isset($_POST['btnEntrar'])){
        $Usuario->cadastrar($_POST);
    }

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">

    <title>Tela de Login</title>
</head>
<body>
    <div class="container">
        <form method="POST" action="?">
            <h1>Cadastrar</h1>

            <div class="input-email">
                <input type="text" name="nome" placeholder="Nome" required>
            </div>

            <div class="input-email">
                <input type="text" name="cpf" placeholder="Cpf" required>
            </div>

            <div class="input-email">
                <input type="date" name="data_nascimento" placeholder="Data de Nascimento" required>
            </div>

            <div class="input-email">
                <input type="text" name="cep" placeholder="CEP" required>
            </div>

            <div class="input-email">
                <input type="text" name="telefone" placeholder="Telefone" required>
            </div>

            <div class="input-email">
                <input type="text" name="login" placeholder="Login">
            </div>

            <div class="input-email">
                <input type="password" name="senha" placeholder="Senha">
            </div>

            <button type="submit" name="btnEntrar" class="botao">Entrar</button>

            <div class="registro">
                <p>Já tem conta? <a href="login.php">Login</a></p>
            </div>

        </form>
    </div>    
</body>
</html>