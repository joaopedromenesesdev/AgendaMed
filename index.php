<?php
/**
 * Arquivo: index.php
 * Função: Arquivo principal de inicialização e roteamento das ações do sistema.
 * Recebe a requisição e direciona para a página correspondente dentro de 'pages/'.
 */

// Define o cabeçalho para responder no formato JSON, já que o JavaScript usará Fetch API
header('Content-Type: application/json; charset=utf-8');

// Inclui o arquivo de conexão com o banco de dados utilizando mysqli
require_once 'config/conexao.php';

// Captura a ação enviada pela URL (via GET) ou define 'listar' como padrão
$acao = isset($_GET['acao']) ? $_GET['acao'] : 'listar';

// Roteador (Switch) para incluir o arquivo correto baseado na ação solicitada pelo Fetch
switch ($acao) {
    case 'salvar':
        // Direciona para o script que faz o INSERT no banco de dados
        include 'pages/salvar.php';
        break;

    case 'listar':
        // Direciona para o script que faz o SELECT e traz todas as consultas
        include 'pages/listar.php';
        break;

    case 'editar':
        // Direciona para o script que busca apenas 1 consulta para preencher o formulário
        include 'pages/editar.php';
        break;

    case 'atualizar':
        // Direciona para o script que faz o UPDATE dos dados alterados
        include 'pages/atualizar.php';
        break;

    case 'excluir':
        // Direciona para o script que faz o DELETE da consulta
        include 'pages/excluir.php';
        break;

    default:
        // Caso seja enviada uma ação desconhecida, retorna um erro em JSON
        echo json_encode([
            "sucesso" => false, 
            "erro" => "Ação inválida ou não informada."
        ]);
        break;
}

// Fecha a conexão com o banco de dados que foi aberta no arquivo conexao.php
mysqli_close($conexao);
?>