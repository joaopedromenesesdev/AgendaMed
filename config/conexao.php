<?php
// Configurações de credenciais do banco de dados local (XAMPP)
$host = "localhost";
$banco = "agendamed";
$usuario = "root";
$senha = ""; // No XAMPP por padrão a senha do root é vazia

try {
    // Tentativa de conexão utilizando o PDO
    $conexao = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    
    // Configura o PDO para disparar exceções (erros) caso algo dê errado nas queries
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $erro) {
    // Se a conexão falhar, exibe uma mensagem clara e interrompe a execução
    die("Erro ao tentar conectar com o banco de dados: " . $erro->getMessage());
}
?>