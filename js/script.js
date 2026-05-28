/**
 * Arquivo: js/script.js
 * Função: Gerenciar as interações da interface, requisições Fetch API (CRUD)
 * e manipulação do DOM sem o uso de atributos 'onclick' no HTML.
 */

// Seleção de elementos do DOM
const btnMenuCadastro = document.getElementById('btn-menu-cadastro');
const btnMenuListar = document.getElementById('btn-menu-listagem');
const secaoCadastro = document.getElementById('secao-cadastro');
const secaoListagem = document.getElementById('secao-listagem');
const formCadastro = document.getElementById('form-cadastro');
const corpoTabela = document.getElementById('corpo-tabela');
const inputId = document.getElementById('consulta-id'); // Campo oculto para edição
const tituloForm = document.getElementById('titulo-form'); // AJUSTE: Captura o elemento do título do formulário

// Controle de navegação das seções
btnMenuCadastro.addEventListener('click', () => {
    secaoCadastro.style.display = 'block';
    secaoListagem.style.display = 'none';
    formCadastro.reset();
    inputId.value = ''; // Garante que o formulário está em modo de "cadastro"
    tituloForm.innerText = 'Agendar Nova Consulta'; // AJUSTE: Reseta o título para modo de agendamento novo
});

btnMenuListar.addEventListener('click', () => {
    secaoCadastro.style.display = 'none';
    secaoListagem.style.display = 'block';
    atualizarTabela();
});

// Envio do Formulário (Salvar Novo ou Atualizar Existente)
formCadastro.addEventListener('submit', (event) => {
    event.preventDefault();

    const dadosFormulario = new FormData(formCadastro);
    
    // Define se a ação será atualizar (caso haja ID no campo oculto) ou salvar (novo registro)
    const acao = inputId.value ? 'atualizar' : 'salvar';

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
            tituloForm.innerText = 'Agendar Nova Consulta'; // AJUSTE: Reseta o título após salvar
            btnMenuListar.click(); // Redireciona visualmente para a listagem
        } else {
            alert('Erro no processo: ' + dados.erro);
        }
    })
    .catch(erro => console.error('Erro na requisição:', erro));
});

// Buscar dados do banco e renderizar a tabela
const atualizarTabela = () => {
    fetch('index.php?acao=listar')
    .then(resposta => resposta.json())
    .then(dados => {
        corpoTabela.innerHTML = '';

        if (dados.erro) {
            alert('Erro ao carregar consultas: ' + dados.erro);
            return;
        }

        dados.forEach((consulta) => {
            const linha = document.createElement('tr');
            
            const dataFormatada = new Date(consulta.data).toLocaleString('pt-BR', {
                dateStyle: 'short',
                timeStyle: 'short'
            });

            // Cria a estrutura interna da linha sem usar 'onclick' nas tags do botão
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
    .catch(erro => console.error('Erro ao listar consultas:', erro));
};

// Escutador de Eventos centralizado na Tabela (Delegação de Eventos para evitar o onclick proibido)
corpoTabela.addEventListener('click', (event) => {
    const id = event.target.getAttribute('data-id');
    
    // Ação do Botão Editar
    if (event.target.classList.contains('btn-editar')) {
        fetch(`index.php?acao=editar&id=${id}`)
        .then(resposta => resposta.json())
        .then(resultado => {
            if (resultado.sucesso) {
                // Preenche o formulário com os dados vindos do banco
                inputId.value = resultado.dados.id;
                document.getElementById('paciente').value = resultado.dados.paciente;
                document.getElementById('medico').value = resultado.dados.medico;
                
                // Formata a data para o padrão exigido pelo input datetime-local (YYYY-MM-DDTHH:MM)
                const dataOriginal = resultado.dados.data.replace(' ', 'T').substring(0, 16);
                document.getElementById('data_hora').value = dataOriginal;

                // AJUSTE: Altera dinamicamente o título visual do formulário para edição
                tituloForm.innerText = 'Editar Consulta';

                // Muda para a seção de formulário para o usuário editar
                secaoCadastro.style.display = 'block';
                secaoListagem.style.display = 'none';
            } else {
                alert('Erro ao buscar dados: ' + resultado.erro);
            }
        });
    }

    // Ação do Botão Cancelar (Excluir)
    if (event.target.classList.contains('btn-excluir')) {
        if (confirm('Tem certeza que deseja cancelar esta consulta?')) {
            fetch(`index.php?acao=excluir&id=${id}`)
            .then(resposta => resposta.json())
            .then(resultado => {
                if (resultado.sucesso) {
                    alert('Consulta cancelada com sucesso!');
                    atualizarTabela();
                } else {
                    alert('Erro ao excluir: ' + resultado.erro);
                }
            });
        }
    }
});