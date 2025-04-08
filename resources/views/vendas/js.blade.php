<script>
    function calcularTotal() {
        const itens = document.querySelectorAll('.item-venda');

        let total = 0;
        
        itens.forEach(item => {
            const produto = item.querySelector('.produto-select');
            const quantidade = item.querySelector('.quantidade-input');
            const preco = parseFloat(produto.selectedOptions[0]?.dataset.preco) || 0;
            const qtd = parseFloat(quantidade.value) || 0;
            total += preco * qtd;
        });

        document.getElementById('total').value = total.toFixed(2).replace('.', ',');
        validarParcelas();
    }

    function adicionarItem() {
        const item = document.querySelector('.item-venda').cloneNode(true);
        item.querySelector('.produto-select').selectedIndex = 0;
        item.querySelector('.quantidade-input').value = 1;
        document.getElementById('itens-venda').appendChild(item);
        calcularTotal();
        gerarParcelas();
    }

    function removerItem(btn) {
        const container = document.getElementById('itens-venda');
        if (container.children.length > 1) {
            btn.closest('.item-venda').remove();
            calcularTotal();
            gerarParcelas();
        }
    }

    function verificarPagamento() {
        const forma = document.getElementById('forma_pagamento').value;
        const parcelamento = document.getElementById('parcelamento');

        if (forma === 'credito') {
            parcelamento.style.display = 'block';
        } else {
            parcelamento.style.display = 'none';
            document.getElementById('parcelas-venda').innerHTML = '';
        }

        if (forma === 'credito') {
            gerarParcelas();
        }
    }

    function gerarParcelas() {
        const qtde = parseInt(document.getElementById('quantidade_parcelas').value) || 0;
        const total = parseFloat(document.getElementById('total').value.replace(',', '.')) || 0;

        if (qtde <= 0 || total <= 0) return;

        const container = document.getElementById('parcelas-venda');
        container.innerHTML = '';

        const valorBase = Math.floor((total / qtde) * 100) / 100;

        let somaParcelas = 0;
        const valores = [];

        for (let i = 0; i < qtde; i++) {
            valores.push(valorBase);
            somaParcelas += valorBase;
        }

        let diferenca = (total - somaParcelas).toFixed(2);
        valores[qtde - 1] += parseFloat(diferenca);

        const hoje = new Date();

        valores.forEach((valor, i) => {
            const vencimento = new Date(hoje.getFullYear(), hoje.getMonth() + i, hoje.getDate());
            const dataVencimento = vencimento.toISOString().split('T')[0];

            const isUltima = i === valores.length - 1;
            const readonly = isUltima ? 'readonly style="background-color:#eee"' : '';

            const html = `
                <div class="row mb-2 parcela-item">
                    <div class="col-md-6">
                        <label>Valor da Parcela</label>
                        <input type="number" name="parcelas[]" class="form-control parcela-valor" step="0.01"
                            value="${valor.toFixed(2)}" data-valor-original="${valor.toFixed(2)}" ${readonly}
                            oninput="ajustarParcelasApartirDe(${i})">
                    </div>
                    <div class="col-md-6">
                        <label>Data de Vencimento</label>
                        <input type="date" name="datas_parcelas[]" class="form-control" value="${dataVencimento}" required>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });

        validarParcelas(false);
    }

    function validarParcelas(mostrarErro = false) {
        const totalVenda = parseFloat(document.getElementById('total').value.replace(',', '.')) || 0;
        const parcelas = document.querySelectorAll('.parcela-valor');
        let soma = 0;
        let alterado = false;

        parcelas.forEach(input => {
            const valorAtual = parseFloat(input.value) || 0;
            const valorOriginal = parseFloat(input.dataset.valorOriginal) || 0;
            soma += valorAtual;

            if (Math.abs(valorAtual - valorOriginal) > 0.01) {
                alterado = true;
            }
        });

        const alerta = document.getElementById('alerta-parcelas');

        if (parcelas.length && Math.abs(soma - totalVenda) > 0.01 && alterado && mostrarErro) {
            alerta.classList.remove('d-none');
        } else {
            alerta.classList.add('d-none');
        }
    }

    function validarAntesDeEnviar() {
        const alerta = document.getElementById('alerta-parcelas');
        if (!alerta.classList.contains('d-none')) {
            alert("Corrija os valores das parcelas antes de enviar.");
            return false;
        }
        return true;
    }

    function ajustarParcelasApartirDe(index) {
        const parcelas = document.querySelectorAll('.parcela-valor');
        const totalVenda = parseFloat(document.getElementById('total').value.replace(',', '.')) || 0;

        let somaAntes = 0;
        for (let i = 0; i <= index; i++) {
            somaAntes += parseFloat(parcelas[i].value) || 0;
        }

        const restante = totalVenda - somaAntes;
        const qtdeRestante = parcelas.length - index - 2;

        if (qtdeRestante < 0) return;

        let valorBase = qtdeRestante > 0 ? Math.floor((restante / (qtdeRestante + 1)) * 100) / 100 : restante;
        let somaParcial = 0;

        for (let i = index + 1; i < parcelas.length - 1; i++) {
            parcelas[i].value = valorBase.toFixed(2);
            somaParcial += valorBase;
        }

        const valorUltima = restante - somaParcial;
        parcelas[parcelas.length - 1].value = valorUltima.toFixed(2);

        validarParcelas(true);
    }

    document.getElementById('quantidade_parcelas').addEventListener('input', calcularTotal);
    document.getElementById('forma_pagamento').addEventListener('change', function (e) {
        calcularTotal();
        verificarPagamento();
    });
    document.getElementById('itens-venda').addEventListener('change', function (e) {
        if (e.target.matches('.produto-select') || e.target.matches('.quantidade-input')) {
            calcularTotal();
            gerarParcelas();
        }
    });
    document.getElementById('parcelas-venda').addEventListener('input', function (e) {
        if (document.querySelectorAll('.parcela-valor').length === 0) {
            calcularTotal();
            gerarParcelas();
        }
    });
</script>
