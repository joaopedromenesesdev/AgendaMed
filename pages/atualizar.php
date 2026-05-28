<?php
// Arquivo: pages/atualizar.php
// Responsabilidade: Receber os dados alterados via POST e executar a query de UPDATE

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura o ID e os novos dados digitados
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $paciente = isset($_POST['paciente']) ? htmlspecialchars(trim($_POST['paciente'])) : '';
    $medico = isset($_POST['medico']) ? htmlspecialchars(trim($_POST['medico'])) : '';
    $data_hora = isset($_POST['data_hora']) ? htmlspecialchars(trim($_POST['data_hora'])) : '';

    if ($id > 0 && !empty($paciente) && !empty($medico) && !empty($data_hora)) {
        // Limpa os dados contra SQL Injection utilizando funções da biblioteca mysqli
        $pacienteClean = mysqli_real_escape_string($conexao, $paciente);
        $medicoClean = mysqli_real_escape_string($conexao, $medico);
        $data_horaClean = mysqli_real_escape_string($conexao, $data_hora);

        // Monta a query de atualização com a cláusula WHERE obrigatória
        $sql = "UPDATE consultas SET paciente = '$pacienteClean', medico = '$medicoClean', data = '$data_horaClean' WHERE id = $id";

        if (mysqli_query($conexao, $sql)) {
            echo json_encode(["sucesso" => true]);
            exit;
        } else {
            echo json_encode(["sucesso" => false, "erro" => mysqli_error($conexao)]);
            exit;
        }
    } else {
        echo json_encode(["sucesso" => false, "erro" => "Dados incompletos para atualização."]);
        exit;
    }
}
?>