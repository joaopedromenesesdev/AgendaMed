/**
 * ARQUIVO: js/main.js
 * OBJETIVO: Controlar as transições da interface visual e realizar requisições assíncronas Fetch API
 */

// Seleção e mapeamento de elementos do DOM
const btnMenuCadastro = document.getElementById('btn-menu-cadastro');
const btnMenuListar   = document.getElementById('btn-menu-listagem');
const secaoCadastro   = document.getElementById('secao-cadastro');
const secaoListagem   = document.getElementById('secao-listagem');
const formCadastro    = document.getElementById('form-cadastro');
const corpoTabela     = document.getElementById('corpo-tabela');
const inputId         = document.getElementById('consulta-id'); 
const tituloForm      = document.getElementById('titulo-form'); 

// Registra os eventos utilizando obrigatoriamente addEventListener, sem onclick direto no HTML
btnMenuCadastro.addEventListener('click', () => {
    secaoCadastro.style.display = 'block';
    secaoListagem.style.display = 'none';
    formCadastro.reset(); // Limpa dados residuais dos campos
    inputId.value = '';   // Define o estado do formulário como inserção (ID vazio)
    tituloForm.innerText = 'Agendar Nova Consulta'; 
});

btnMenuListar.addEventListener('click', () => {
    secaoCadastro.style.display = 'none';
    secaoListagem.style.display = 'block';
    atualizarTabela(); // Aciona a busca de dados em tempo real no banco
});

// Evento disparado no envio do formulário (Submissão)
formCadastro.addEventListener('submit', (event) => {
    event.preventDefault(); // Impede o recarregamento nativo da página HTML

    // Consolida e empacota todos os dados do formulário nativamente utilizando FormData
    const dadosFormulario = new FormData(formCadastro);
    
    // Define dinamicamente o parâmetro da rota baseado na existência de um ID no campo oculto
    const acao = inputId.value ? 'atualizar' : 'salvar';

    // Dispara a requisição assíncrona POST enviando o corpo das informações coletadas
    fetch(`index.php?acao=${acao}`, {
        method: 'POST',
        body: dadosFormulario
    })
    .then(resposta => resposta.json())
    .then(dados => {
        if (dados.sucesso) {
            alert(acao === 'salvar' ? 'Consulta cadastrada com sucesso!' : 'Consulta atualizada com sucesso!');
            formCadastro.reset();
            inputId.value = '';
            tituloForm.innerText = 'Agendar Nova Consulta';
            btnMenuListar.click(); // Força a navegação visual automática até a tabela
        } else {
            alert('Erro operacional: ' + dados.erro);
        }
    })
    .catch(erro => console.error('Falha na comunicação de rede:', erro));
});

// Busca os registros de forma dinâmica no banco para renderização em tela
const atualizarTabela = () => {
    fetch('index.php?acao=listar')
    .then(resposta => resposta.json())
    .then(dados => {
        corpoTabela.innerHTML = ''; // Limpa os dados contidos na tabela antes do preenchimento

        if (dados.erro) {
            alert('Falha interna ao carregar: ' + dados.erro);
            return;
        }

        // Percorre a lista de registros recebidos por meio de um laço forEach
        dados.forEach((consulta) => {
            const linha = document.createElement('tr');
            
            // Realiza a formatação localizada da data e hora vindas do banco
            const dataFormatada = new Date(consulta.data_hora).toLocaleString('pt-BR', {
                dateStyle: 'short',
                timeStyle: 'short'
            });

            // Estrutura a linha do HTML injetando atributos de identificação do elemento clicado
            linha.innerHTML = `
                <td>${consulta.id}</td>
                <td>${consulta.paciente}</td>
                <td>${consulta.medico}</td>
                <td>${dataFormatada}</td>
                <td>
                    <button class="btn-editar" data-id="${consulta.id}">Editar</button>
                    <button class="btn-excluir" data-id="${consulta.id}">Cancelar</button>
                </td>
            `;
            corpoTabela.appendChild(linha);
        });
    })
    .catch(erro => console.error('Erro na requisição Fetch:', erro));
};

// Implementação de Delegação de Eventos no elemento pai para gerenciamento seguro dos cliques dos botões
corpoTabela.addEventListener('click', (event) => {
    const id = event.target.getAttribute('data-id');
    
    // Intercepta e gerencia o clique no botão de edição
    if (event.target.classList.contains('btn-editar')) {
        fetch(`index.php?acao=editar&id=${id}`)
        .then(resposta => resposta.json())
        .then(resultado => {
            if (resultado.sucesso) {
                // Alimenta os inputs do formulário com os respectivos dados encontrados
                inputId.value = resultado.dados.id;
                document.getElementById('paciente').value = resultado.dados.paciente;
                document.getElementById('medico').value = resultado.dados.medico;
                
                // Converte a formatação da string para aceitação do input nativo datetime-local
                const dataOriginal = resultado.dados.data_hora.replace(' ', 'T').substring(0, 16);
                document.getElementById('data_hora').value = dataOriginal;

                tituloForm.innerText = 'Editar Consulta'; // Altera o contexto textual visualmente

                // Transiciona a tela para exibição imediata do formulário carregado
                secaoCadastro.style.display = 'block';
                secaoListagem.style.display = 'none';
            } else {
                alert('Erro ao resgatar informações: ' + resultado.erro);
            }
        });
    }

    // Intercepta e gerencia o clique no botão de exclusão
    if (event.target.classList.contains('btn-excluir')) {
        if (confirm('Deseja realmente remover esta consulta do sistema?')) {
            fetch(`index.php?acao=excluir&id=${id}`)
            .then(resposta => resposta.json())
            .then(resultado => {
                if (resultado.sucesso) {
                    alert('Consulta removida com sucesso do sistema.');
                    atualizarTabela(); // Atualiza a visualização da listagem de dados em tela
                } else {
                    alert('Erro na remoção do item: ' + resultado.erro);
                }
            });
        }
    }
});