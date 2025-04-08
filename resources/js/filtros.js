function configurarFiltroAjax(formId, resultadoId, rota) {
    $(formId).on('submit', function (e) {
        e.preventDefault();

        let dados = $(this).serialize();

        $.ajax({
            url: rota,
            type: 'GET',
            data: dados,
            success: function (html) {
                $(resultadoId).html(html);
            },
            error: function () {
                alert('Erro ao aplicar o filtro.');
            }
        });
    });

    $(formId).find('button[type="reset"]').on('click', function () {

        $(formId).find('input, select').val('');

        setTimeout(() => {
            $(formId).submit();
        }, 100);
    });
}