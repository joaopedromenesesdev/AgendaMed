<?php
// 1. Inclui a conexão com o banco
require_once 'config/conexao.php';

try {
    // 2. Faz a busca de todas as consultas ordenadas por data
    $sql = "SELECT id, paciente, medico, DATE_FORMAT(data_hora, '%Y-%m-%dT%H:%i') as data FROM consultas ORDER BY data_hora ASC";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    
    // 3. Transforma o resultado em um array associativo do PHP
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 4. Devolve o array para o JavaScript em formato JSON
    echo json_encode($resultados);
    
} catch (PDOException $erro) {
    echo json_encode(['erro' => $erro->getMessage()]);
}
?>