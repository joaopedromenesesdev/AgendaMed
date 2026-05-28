<?php
// Arquivo: pages/editar.php
// Responsabilidade: Buscar apenas UM registro pelo ID para preencher os campos de edição

// Captura e valida o ID recebido via GET na URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Escapa o ID por segurança extra
    $idClean = mysqli_real_escape_string($conexao, $id);
    
    // Consulta para buscar apenas a linha correspondente ao ID
    $sql = "SELECT id, paciente, medico, data FROM consultas WHERE id = $idClean LIMIT 1";
    $resultado = mysqli_query($conexao, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $consulta = mysqli_fetch_assoc($resultado);
        // Retorna os dados da consulta em JSON para o JavaScript jogar nos inputs do formulário
        echo json_encode(["sucesso" => true, "dados" => $consulta]);
        exit;
    } else {
        echo json_encode(["sucesso" => false, "erro" => "Agendamento não encontrado."]);
        exit;
    }
} else {
    echo json_encode(["sucesso" => false, "erro" => "ID inválido para edição."]);
    exit;
}
?>