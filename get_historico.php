<?php
// Incluindo o arquivo de conexão
require_once $_SERVER['DOCUMENT_ROOT'] . '/Pi-Software/class/classes.php';

$Docente = new Docente();

// Definindo o fuso horário
date_default_timezone_set('America/Sao_Paulo');

// Função para buscar o histórico de kits
function getHistorico($data)
{
    // Obtém a conexão com o banco de dados
    $pdo = Conexao::conexao();
    
    // Prepara a consulta
    $stmt = $pdo->prepare("SELECT * FROM entradas_saidas es INNER JOIN kits k ON es.id_kit = k.id_kit WHERE DATE(es.data_saida) = :data_saida");
    
    // Executa a consulta passando a data
    $stmt->execute(['data_saida' => $data]);
    
    // Busca os resultados
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function status($status)
{
    switch($status){
        case 1:
            return 'Disponível';
        break;
        case 2:
            return 'Indisponível';
        break;
    }
}

// Captura a data recebida via GET
$data = isset($_GET['data']) ? $_GET['data'] : null;

// Função para formatar a string de itens
function formatarItens($itens) {
    // Se não houver itens, retorna uma string vazia
    if (empty($itens)) {
        return '';
    }

    // Substitui os underscores por espaços
    $itens = str_replace('_', ' ', $itens);
    
    // Coloca todas as palavras com a primeira letra maiúscula
    $itensFormatados = ucwords(strtolower($itens));

    return $itensFormatados;
}

if ($data) {
    $historico = getHistorico($data);
    if ($historico) {
        foreach ($historico as $row) {
            // Usa a função formatarItens para formatar item_kit e item_kit2
            $itemKitFormatado = formatarItens($row['item_kit']);
            $itemKit2Formatado = formatarItens($row['item_kit2']);

            echo "<tr>
                    <td>" . htmlspecialchars(date('d/m/Y H:i:s', strtotime($row['data_saida']))) . "</td>
                    <td>" . htmlspecialchars($Docente->mostrar($row['id_docente'])->nome) . "</td>
                    <td>" . htmlspecialchars($itemKitFormatado) . "</td>
                    <td>" . htmlspecialchars(!empty($row['data_entrada']) ? date('d/m/Y H:i:s', strtotime($row['data_entrada'])) : "") . "</td>
                    <td>" . htmlspecialchars(!empty($row['id_docente2']) ? $Docente->mostrar($row['id_docente2'])->nome : "") . "</td>
                    <td>" . htmlspecialchars($itemKit2Formatado) . "</td>
                  </tr>";
        }        
    } else {
        echo "<tr><td colspan='3'>Nenhum registro encontrado.</td></tr>";
    }
} else {
    echo "<tr><td colspan='3'>Data não informada.</td></tr>";
}


?>
