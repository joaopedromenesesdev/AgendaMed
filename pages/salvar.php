<?php
// 1. Inclui o arquivo de conexão com o banco
require_once 'config/conexao.php';

// 2. Verifica se os dados foram enviados via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. Captura os dados enviados pelo formulário
    $paciente = $_POST['paciente'] ?? '';
    $medico = $_POST['medico'] ?? '';
    $data_hora = $_POST['data_hora'] ?? '';

    // Validação simples: garante que nenhum campo veio vazio
    if (!empty($paciente) && !empty($medico) && !empty($data_hora)) {
        try {
            // 4. Prepara o comando SQL (usando Prepared Statements contra invasões)
            $sql = "INSERT INTO consultas (paciente, medico, data_hora) VALUES (:paciente, :medico, :data_hora)";
            $stmt = $conexao->prepare($sql);
            
            // 5. Substitui os parâmetros pelos valores reais
            $stmt->bindValue(':paciente', $paciente);
            $stmt->bindValue(':medico', $medico);
            $stmt->bindValue(':data_hora', $data_hora);
            
            // 6. Executa a gravação no banco de dados
            $stmt->execute();
            
            // Retorna uma resposta de sucesso para o JavaScript saber que deu certo
            echo json_encode(['sucesso' => true]);
            exit;
            
        } catch (PDOException $erro) {
            echo json_encode(['sucesso' => false, 'erro' => $erro->getMessage()]);
            exit;
        }
    }
}

// Se tentarem acessar esse arquivo direto ou com dados incompletos
echo json_encode(['sucesso' => false, 'erro' => 'Dados inválidos']);
?>