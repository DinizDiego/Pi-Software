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
        <form method="POST" action="?" onsubmit="return validarFormulario()">
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
    <script>
        // Função para validar o formato do CEP
        function validarCEP(cep) {
            // Expressão regular para verificar se o CEP segue o padrão XXXXX-XXX
            var regex = /^[0-9]{5}-?[0-9]{3}$/;
            return regex.test(cep);
        }

        // Evento de "blur" no campo de CEP
        document.querySelector('input[name="cep"]').addEventListener('blur', function() {
            var cep = this.value.trim(); // Retira espaços em branco no início e no fim

            // Valida o formato do CEP
            if (!validarCEP(cep)) {
                swal({
                    title: "Erro!",
                    text: "O formato do CEP está incorreto. Use o formato: XXXXX-XXX.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return;
            }

            // Caso o formato esteja correto, faz a consulta à API
            consultarCEP(cep);
        });

        // Função que consulta a API ViaCEP
        function consultarCEP(cep) {
            // Remove o hífen para enviar o CEP sem ele
            var cepSemHifen = cep.replace('-', '');

            // Faz a requisição AJAX para a API ViaCEP
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'https://viacep.com.br/ws/' + cepSemHifen + '/json/', true);
            xhr.onload = function() {
                if (xhr.status == 200) {
                    var dados = JSON.parse(xhr.responseText);

                    // Verifica se a API retornou um erro (CEP não encontrado)
                    if (dados.erro) {
                        swal({
                            title: "Erro!",
                            text: "CEP não encontrado. Verifique o CEP digitado.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    } else {
                        // Se o CEP for válido, preenche os campos de endereço (opcional)
                        document.querySelector('input[name="logradouro"]').value = dados.logradouro || '';
                        document.querySelector('input[name="bairro"]').value = dados.bairro || '';
                        document.querySelector('input[name="cidade"]').value = dados.localidade || '';
                        document.querySelector('input[name="uf"]').value = dados.uf || '';
                    }
                } else {
                    swal({
                        title: "Erro!",
                        text: "Não foi possível realizar a consulta. Tente novamente.",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            };
            xhr.send();
        }


        // Função para validar CPF (JavaScript)
        function validarCPF(cpf) {
            cpf = cpf.replace(/[^\d]+/g, ''); // Remove caracteres não numéricos

            if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
                return false; // Verifica se é uma sequência repetida (ex: 11111111111)
            }

            // Validação do 1º dígito
            let soma = 0;
            let resto;
            for (let i = 0; i < 9; i++) {
                soma += parseInt(cpf.charAt(i)) * (10 - i);
            }
            resto = soma % 11;
            if (resto < 2) resto = 0;
            else resto = 11 - resto;
            if (cpf.charAt(9) != resto) return false;

            // Validação do 2º dígito
            soma = 0;
            for (let i = 0; i < 10; i++) {
                soma += parseInt(cpf.charAt(i)) * (11 - i);
            }
            resto = soma % 11;
            if (resto < 2) resto = 0;
            else resto = 11 - resto;
            if (cpf.charAt(10) != resto) return false;

            return true;
        }

        // Função que é chamada quando o formulário é enviado
        function validarFormulario() {
            const cpf = document.querySelector('input[name="cpf"]').value;

            if (!validarCPF(cpf)) {
                alert('CPF inválido. Por favor, insira um CPF válido.');
                return false; // Impede o envio do formulário
            }

            return true; // Permite o envio do formulário
        }
    </script>
</html>