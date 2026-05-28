<?php
/**
 * ARQUIVO: index.php (Raiz)
 * OBJETIVO: Atuar como ponto centralizado de rotas (Controlador) para chamadas Fetch API
 */

// Define o cabeçalho de resposta padrão como JSON e desabilita cache para evitar dados defasados
header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-cache, no-store, must-revalidate");

// Carrega o arquivo responsável pela conexão ativa com a instância do banco de dados MySQL
require_once "config/conexao.php";

// Recupera o parâmetro 'acao' enviado via URL. Caso não exista, define como vazio
$acao = isset($_GET['acao']) ? $_GET['acao'] : '';

// Switch estruturado para ler as ações do CRUD e carregar o arquivo isolado correspondente
switch ($acao) {
    case 'salvar':
        include "pages/salvar.php";
        break;
    case 'listar':
        include "pages/listar.php";
        break;
    case 'editar':
        include "pages/editar.php";
        break;
    case 'atualizar':
        include "pages/atualizar.php";
        break;
    case 'excluir':
        include "pages/excluir.php";
        break;
    default:
        // Caso uma ação desconhecida ou inválida seja enviada, retorna erro estruturado em JSON
        echo json_encode(["erro" => "Ação não informada ou rota inválida no sistema."]);
        break;
}

// Encerra explicitamente a conexão com o banco utilizando a função nativa obrigatória mysqli
if (isset($conexao)) {
    mysqli_close($conexao);
}
?>