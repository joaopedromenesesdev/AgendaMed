<?php
/**
 * ARQUIVO: pages/listar.php
 * OBJETIVO: Executar o SELECT e carregar os registros através de uma estrutura de repetição
 */

// Define a consulta SQL ordenando os agendamentos pela proximidade cronológica
$sql = "SELECT id, paciente, medico, data_hora FROM consultas ORDER BY data_hora ASC";

// Executa a instrução SQL no banco conectado
$resultado = mysqli_query($conexao, $sql);

if ($resultado) {
    $listaConsultas = [];

    // Laço de repetição condicional utilizando a função nativa obrigatória mysqli_fetch_assoc
    while ($linha = mysqli_fetch_assoc($resultado)) {
        $listaConsultas[] = $linha; // Alimenta a coleção com cada linha lida do banco
    }

    // Retorna a coleção contendo todos os agendamentos cadastrados em formato JSON para o JavaScript
    echo json_encode($listaConsultas);
    exit;
} else {
    echo json_encode(["erro" => "Falha ao ler dados: " . mysqli_error($conexao)]);
    exit;
}
?>