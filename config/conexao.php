<?php
// Arquivo: config/conexao.php
// Responsabilidade: Criar a conexão nativa com o MySQL usando mysqli_connect()

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "agendamed";

// Conexão utilizando a função exigida pelo critério da AV2
$conexao = mysqli_connect($host, $usuario, $senha, $banco);

// Verifica se houve falha na conexão e interrompe o script exibindo o erro
if (!$conexao) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

// Configura o charset para UTF-8 para evitar problemas com acentos e caracteres especiais
mysqli_set_charset($conexao, "utf8");
?>