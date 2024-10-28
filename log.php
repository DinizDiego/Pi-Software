<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Kits</title>
    <link rel="stylesheet" href="css/Kits.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/log.css">
</head>
<body>

<div class="input-data">
    <label for="dataNascimento">Selecione uma Data</label>
    <div class="data-container">
        <input type="date" id="dataNascimento" name="data_nascimento" class="modal-input" value="<?php echo date('Y-m-d'); ?>" required>
    </div>
</div>

<section class="tabela">
    <table>
        <thead>
            <tr>
                <th>Data de Retirada</th>
                <th>Responsável Retirada</th>
                <th>Observação Retirada</th>
                <th>Data de Devolução</th>
                <th>Responsável Devolução</th>
                <th>Observação Devolução</th>
            </tr>
        </thead>
        <tbody id="tabelaHistorico">
            <!-- Aqui você pode adicionar suas linhas da tabela -->
        </tbody>
    </table>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        // Chama a função ao carregar a página
        carregarHistorico($('#dataNascimento').val());

        // Função para carregar o histórico com base na data selecionada
        function carregarHistorico(dataSelecionada) {
            $.ajax({
                type: "GET", // Método HTTP
                url: "get_historico.php", // URL do arquivo PHP
                data: { data: dataSelecionada }, // Envia a data como parâmetro
                dataType: "text", // Tipo de dado esperado da resposta
                success: function (response) {
                    $('#tabelaHistorico').html(response); // Atualiza o corpo da tabela com a resposta
                },
                error: function (xhr, status, error) {
                    console.error("Erro ao fazer a requisição:", error); // Lida com erros
                }
            });
        }

        // Evento para mudança na data
        $('#dataNascimento').change(function (e) {
            var dataSelecionada = $(this).val(); // Obtém o valor da data selecionada
            carregarHistorico(dataSelecionada); // Chama a função com a data selecionada
        });
    });
</script>

</body>
</html>
