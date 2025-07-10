var url = window.location.origin;

$(document).on('click', '.btn-ver-pedido', function () {
    const pedido = $(this).data('pedido');
    // console.log(pedido);
    // return

    // Preenche dados do cliente
    $('#customer-name').val(pedido.customer_name);
    $('#customer-phone').val(pedido.customer_phone);
    $('#customer-address').text(pedido.customer_address ?? '---');


    // Limpa tabela antes
    $('#table-items').empty();

    // Adiciona itens
    pedido.items.forEach(function (item) {
        const row = `
                <tr>
                    <td>${item.name} ${item.quantity > 1 ? `x${item.quantity}` : ''}</td>
                    <td>R$ ${parseFloat(item.total).toFixed(2).replace('.', ',')}</td>
                </tr>`;
        $('#table-items').append(row);
    });

    // Adiciona linha com formas de pagamento
    $('#table-items').append(`
            <tr>
                <td><strong>Pagamento</strong></td>
                <td>${pedido.formas_pagamento}</td>
            </tr>
        `);

    // Adiciona entrega se houver
    if (pedido.delivery_fee > 0) {
        $('#table-items').append(`
                <tr>
                    <td><strong>Taxa de entrega</strong></td>
                    <td>R$ ${parseFloat(pedido.delivery_fee).toFixed(2).replace('.', ',')}</td>
                </tr>
            `);
    }

    // Adiciona total geral
    $('#table-items').append(`
            <tr class="table-success">
                <td><strong>Total Geral</strong></td>
                <td><strong>R$ ${parseFloat(pedido.total_geral).toFixed(2).replace('.', ',')}</strong></td>
            </tr>
        `);

    // Abre o modal
    $('#modalInfo').modal('show');
});
$(document).ready(function () {
    let pedidoIdSelecionado = null;

    // Abrir modal e carregar motoboys
    $(document).on('click', '.btn-add-motoboy, .btn-alterar-motoboy', function () {
        pedidoIdSelecionado = $(this).data('id');

        $.get('/pedidos/motoboys/lista', function (motoboys) {
            let lista = '';
            motoboys.forEach(function (motoboy) {
                lista += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        🛵 ${motoboy.name} - ${motoboy.phone}
                        <button class="btn btn-sm btn-primary selecionar-motoboy" data-id="${motoboy.id}" data-nome="${motoboy.name}">
                            Selecionar
                        </button>
                    </li>
                `;
            });
            $('#lista-motoboys').html(lista);
            $('#modalMotoboy').modal('show');
        });
    });

    // Quando clicar no botão "Selecionar" do motoboy
    $(document).on('click', '.selecionar-motoboy', function () {
        const motoboyId = $(this).data('id');
        const motoboyNome = $(this).data('nome');

        $.post('/pedidos/atribuir-motoboy', {
            _token: $('meta[name="csrf-token"]').attr('content'),
            order_id: pedidoIdSelecionado,
            motoboy_id: motoboyId
        }, function (res) {
            if (res.success) {
                const btn = $(`button[data-id="${pedidoIdSelecionado}"]`);
                btn.removeClass('btn-warning')
                   .addClass('btn-success btn-alterar-motoboy')
                   .html('🛵 ' + motoboyNome);

                $('#modalMotoboy').modal('hide');
            } else {
                alert('Erro ao atribuir motoboy');
            }
        });
    });
});


