<?php
/**
 * ARQUIVO: pages/excluir.php
 * OBJETIVO: Remover de forma definitiva uma linha da tabela baseado no ID de referência
 */

// Valida o parâmetro ID vindo na URL por meio de requisição do tipo GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Constrói a instrução SQL DELETE com o filtro pelo ID correspondente
    $sql = "DELETE FROM consultas WHERE id = $id";

    if (mysqli_query($conexao, $sql)) {
        echo json_encode(["sucesso" => true]);
        exit;
    } else {
        echo json_encode(["sucesso" => false, "erro" => "Erro ao remover registro: " . mysqli_error($conexao)]);
        exit;
    }
} else {
    echo json_encode(["sucesso" => false, "erro" => "Identificador inválido fornecido."]);
    exit;
}
?>