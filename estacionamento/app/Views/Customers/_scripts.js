// Função para formatar o CPF
function formatarCPF(cpf) {
    cpf = cpf.replace(/\D/g, ''); // Remove caracteres não numéricos

    // Adiciona ponto e traço no CPF
    cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
    cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
    cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

    return cpf;
}

// Função para atualizar o valor do input conforme o CPF é digitado
function atualizarInput(event) {
    let input = event.target;
    let valorAtual = input.value;

    // Limita o número máximo de caracteres para 14
    if (valorAtual.length > 14) {
        valorAtual = valorAtual.slice(0, 14);
    }

    const novoValor = formatarCPF(valorAtual);

    input.value = novoValor;
}

// Função para formatar o CPF inicialmente
function formatarCPFInicial() {
    const cpfInput = document.getElementById('cpf');
    cpfInput.value = formatarCPF(cpfInput.value);
}

// Seleciona o input de CPF
const cpfInput = document.getElementById('cpf');

// Adiciona um ouvinte de evento de entrada para o input de CPF
cpfInput.addEventListener('input', atualizarInput);

// Chamada da função para formatar o CPF inicialmente
formatarCPFInicial();
