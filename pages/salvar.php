<?php
/**
 * ARQUIVO: pages/salvar.php
 * OBJETIVO: Validar e sanitizar a requisição via POST e gravar um novo agendamento
 */

// Verifica se a requisição atual é do tipo POST para garantir conformidade
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Captura os dados aplicando trim() para remover espaços e htmlspecialchars() contra XSS
    $paciente  = isset($_POST['paciente'])  ? htmlspecialchars(trim($_POST['paciente']))  : '';
    $medico    = isset($_POST['medico'])    ? htmlspecialchars(trim($_POST['medico']))    : '';
    $data_hora = isset($_POST['data_hora']) ? htmlspecialchars(trim($_POST['data_hora'])) : '';

    // Verifica se todos os campos obrigatórios foram devidamente preenchidos
    if (!empty($paciente) && !empty($medico) && !empty($data_hora)) {
        
        // Escape dos dados de string com mysqli_real_escape_string contra injeções SQL indesejadas
        $pacienteClean  = mysqli_real_escape_string($conexao, $paciente);
        $medicoClean    = mysqli_real_escape_string($conexao, $medico);
        $data_horaClean = mysqli_real_escape_string($conexao, $data_hora);

        // Constrói a consulta SQL estruturada para inserção
        $sql = "INSERT INTO consultas (paciente, medico, data_hora) VALUES ('$pacienteClean', '$medicoClean', '$data_horaClean')";

        // Executa a consulta no banco de dados
        if (mysqli_query($conexao, $sql)) {
            echo json_encode(["sucesso" => true]);
            exit;
        } else {
            echo json_encode(["sucesso" => false, "erro" => "Falha na inserção: " . mysqli_error($conexao)]);
            exit;
        }
    } else {
        echo json_encode(["sucesso" => false, "erro" => "Preencha todos os campos obrigatórios."]);
        exit;
    }
}
?>