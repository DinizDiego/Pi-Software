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

// Verifica se a data foi recebida
if ($data) {
    $historico = getHistorico($data);
    if ($historico) {
        foreach ($historico as $row) {
            echo "<tr>
                    <td>" . htmlspecialchars(date('d/m/Y h:i:s', strtotime($row['data_saida']))) . "</td>
                    <td>" . htmlspecialchars($Docente->mostrar($row['id_docente'])->nome) . "</td>
                    <td>" . htmlspecialchars(!empty($row['observacao_saida']) ? $row['observacao_saida'] : "") . "</td>
                    <td>" . htmlspecialchars(!empty($row['data_entrada']) ? date('d/m/Y h:i:s', strtotime($row['data_entrada'])) : "") . "</td>
                    <td>" . htmlspecialchars(!empty($row['id_docente2']) ? $Docente->mostrar($row['id_docente2'])->nome : "") . "</td>
                    <td>" . htmlspecialchars(!empty($row['observacao_entrada']) ? $row['observacao_entrada'] : "") . "</td>
                  </tr>";
        }        
    } else {
        echo "<tr><td colspan='3'>Nenhum registro encontrado.</td></tr>";
    }
} else {
    echo "<tr><td colspan='3'>Data não informada.</td></tr>";
}
?>
