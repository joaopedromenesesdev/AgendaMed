<?php
/**
 * ARQUIVO: pages/editar.php
 * OBJETIVO: Buscar um agendamento individualizado com base em seu ID passado por parâmetro GET
 */

// Filtra e valida se o ID recebido via URL corresponde a um número inteiro válido
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Constrói a query para selecionar o registro específico com limite de um retorno único
    $sql = "SELECT id, paciente, medico, data_hora FROM consultas WHERE id = $id LIMIT 1";
    $resultado = mysqli_query($conexao, $sql);

    // Avalia se o registro correspondente foi localizado na tabela
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $consulta = mysqli_fetch_assoc($resultado);
        echo json_encode(["sucesso" => true, "dados" => $consulta]);
        exit;
    } else {
        echo json_encode(["sucesso" => false, "erro" => "Registro correspondente não foi localizado."]);
        exit;
    }
} else {
    echo json_encode(["sucesso" => false, "erro" => "Identificador fornecido é inválido para a operação."]);
    exit;
}
?>