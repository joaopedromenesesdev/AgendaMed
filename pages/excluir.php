<?php
require_once 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';

    if (!empty($id)) {
        try {
            $sql = "DELETE FROM consultas WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            
            echo json_encode(['sucesso' => true]);
            exit;
        } catch (PDOException $erro) {
            echo json_encode(['sucesso' => false, 'erro' => $erro->getMessage()]);
            exit;
        }
    }
}
echo json_encode(['sucesso' => false, 'erro' => 'Requisição inválida']);
?>