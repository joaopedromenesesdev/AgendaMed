//Criando variáveis para acessar elementos do DOM//
let btnMenuCadastro = document.getElementById('btn-menu-cadastro');
let btnMenuListar = document.getElementById('btn-menu-listagem');
let secaoCadastro = document.getElementById('secao-cadastro');
let secaoListagem = document.getElementById('secao-listagem');
let formCadastro = document.getElementById('form-cadastro');
let corpoTabela = document.getElementById('corpo-tabela');

//Função para quando clicar nos botões do menu//
btnMenuCadastro.addEventListener('click', () => {
    secaoCadastro.style.display = 'block'; //Exibir seção cadastro//
    secaoListagem.style.display = 'none'; //Bloquear seção listagem//
});

btnMenuListar.addEventListener('click', () => {
    secaoCadastro.style.display = 'none'; //Bloquear seção cadastro//
    secaoListagem.style.display = 'block'; //Exibir seção listagem//
    atualizarTabela(); //Chama a função para exibir a lista atualizada ao mudar de tela
});

//Função pro usuário enviar o formulário de cadastro (Conectada ao Banco de Dados)//
formCadastro.addEventListener('submit', (event) => {
    //Impede a página de recarregar ao enviar o formulário//
    event.preventDefault();

    //Captura os valores dos inputs HTML//
    let nomePaciente = document.getElementById('paciente').value;
    let nomeMedico = document.getElementById('medico').value;
    let dataHora = document.getElementById('data_hora').value;

    // Criamos um formato de dados que o PHP consegue entender nativamente (FormData)
    let dadosFormulario = new FormData();
    dadosFormulario.append('paciente', nomePaciente);
    dadosFormulario.append('medico', nomeMedico);
    dadosFormulario.append('data_hora', dataHora);

    // Envia os dados para o arquivo PHP usando o Fetch API
    fetch('salvar_consulta.php', {
        method: 'POST',
        body: dadosFormulario
    })
    .then(resposta => resposta.json()) // Converte a resposta do PHP para JSON
    .then(dados => {
        if (dados.sucesso) {
            // Se o PHP salvou com sucesso no MySQL:
            alert('Consulta cadastrada com sucesso no Banco de Dados!');
            formCadastro.reset(); // Limpa o formulário
        } else {
            // Se o PHP retornou algum erro do banco:
            alert('Erro ao salvar no banco: ' + dados.erro);
        }
    })
    .catch(erro => {
        // Se houver algum erro de rede ou o arquivo PHP não for encontrado:
        console.error('Erro na requisição:', erro);
        alert('Erro ao tentar se comunicar com o servidor.');
    });
});

//Função para atualizar a lista de consultas (Buscando do Banco de Dados)//
let atualizarTabela = () => {
    // Faz a requisição para o arquivo PHP que lê o banco
    fetch('listar_consultas.php')
    .then(resposta => resposta.json())
    .then(dados => {
        // Limpar o corpo da tabela antes de desenhar as novas linhas
        corpoTabela.innerHTML = '';

        // Se o PHP retornar um erro do banco, avisa o usuário
        if (dados.erro) {
            alert('Erro ao carregar consultas: ' + dados.erro);
            return;
        }

        // Loop para correr os dados vindos do MySQL
        dados.forEach((consulta) => {
            let linha = document.createElement('tr');

            // Formata a data para ficar amigável na exibição (Ex: 30/06/2026 16:00)
            let dataFormatada = new Date(consulta.data).toLocaleString('pt-BR', {
                dateStyle: 'short',
                timeStyle: 'short'
            });

            // Preenchendo a linha com os dados reais do banco
            linha.innerHTML = `
                <td>${consulta.id}</td>
                <td>${consulta.paciente}</td>
                <td>${consulta.medico}</td>
                <td>${dataFormatada}</td>
                <td>
                    <button class="btn-excluir" onclick="cancelarConsulta(${consulta.id})">Cancelar</button>
                </td>
            `;
            
            corpoTabela.appendChild(linha);
        });
    })
    .catch(erro => {
        console.error('Erro ao listar consultas:', erro);
    });
};

//Função para remover uma consulta do banco pelo ID (Deletar Real)//
let cancelarConsulta = (idParaDeletar) => {
    if (confirm('Tem certeza que deseja cancelar esta consulta?')) {
        
        // Prepara o ID para enviar ao PHP
        let dados = new FormData();
        dados.append('id', idParaDeletar);

        // Faz o fetch para o arquivo de cancelamento
        fetch('cancelar_consulta.php', {
            method: 'POST',
            body: dados
        })
        .then(resposta => resposta.json())
        .then(resultado => {
            if (resultado.sucesso) {
                alert('Consulta cancelada com sucesso!');
                atualizarTabela(); // Atualiza a tabela na tela para sumir com a linha deletada
            } else {
                alert('Erro ao cancelar no banco: ' + resultado.erro);
            }
        })
        .catch(erro => {
            console.error('Erro na requisição de cancelamento:', erro);
            alert('Erro ao tentar se comunicar com o servidor.');
        });
    }
};