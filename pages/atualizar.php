<?php
/**
 * ARQUIVO: pages/atualizar.php
 * OBJETIVO: Tratar os novos dados passados via formulário e efetivar a alteração do registro
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados e sanitiza os inputs do usuário contra scripts maliciosos
    $id        = isset($_POST['id'])        ? intval($_POST['id'])                        : 0;
    $paciente  = isset($_POST['paciente'])  ? htmlspecialchars(trim($_POST['paciente']))  : '';
    $medico    = isset($_POST['medico'])    ? htmlspecialchars(trim($_POST['medico']))    : '';
    $data_hora = isset($_POST['data_hora']) ? htmlspecialchars(trim($_POST['data_hora'])) : '';

    // Avalia se as dependências e chaves obrigatórias constam na requisição
    if ($id > 0 && !empty($paciente) && !empty($medico) && !empty($data_hora)) {
        
        // Limpa as variáveis contra vulnerabilidades de injeção SQL
        $pacienteClean  = mysqli_real_escape_string($conexao, $paciente);
        $medicoClean    = mysqli_real_escape_string($conexao, $medico);
        $data_horaClean = mysqli_real_escape_string($conexao, $data_hora);

        // Define a query SQL utilizando a cláusula de restrição WHERE para atingir somente o ID alvo
        $sql = "UPDATE consultas SET paciente = '$pacienteClean', medico = '$medicoClean', data_hora = '$data_horaClean' WHERE id = $id";

        if (mysqli_query($conexao, $sql)) {
            echo json_encode(["sucesso" => true]);
            exit;
        } else {
            echo json_encode(["sucesso" => false, "erro" => "Erro na alteração do registro: " . mysqli_error($conexao)]);
            exit;
        }
    } else {
        echo json_encode(["sucesso" => false, "erro" => "Inconsistência nos parâmetros enviados."]);
        exit;
    }
}
?>