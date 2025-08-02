var url = window.location.origin;

$(document).on('click', '.btn-ver-pedido', function () {
    const pedido = $(this).data('pedido');
    console.log(pedido);
    // Preenche dados do cliente
    $('#customer-name').val(pedido.customer.name);
    $('#customer-phone').val(pedido.customer.phone);
    $('#customer-address').text(pedido.customer.location ?? '---');

    $('#table-items').empty();
    let subtotal = 0;

    // Se for cancelado, mostra o motivo
    if (pedido.status?.name === 'Cancelado' && pedido.cancel_reason) {
        $('#motivo-cancelamento').text(pedido.cancel_reason);
        $('#motivo-cancelamento-box').show();
    } else {
        $('#motivo-cancelamento-box').hide();
    }

    // Itens do pedido
    pedido.items.forEach(function (item) {
        let crustHtml = '';
        let obsHtml = '';
        let itemTotal = parseFloat(item.total) || 0;
        let crustPrice = parseFloat(item.crust_price) || 0;

        if (item.crust) {
            crustHtml = `<br><small class="text-muted">Borda: ${item.crust} (+R$ ${crustPrice.toFixed(2).replace('.', ',')})</small>`;
        }

        if (item.observation && item.observation.trim() !== '') {
            obsHtml = `<br><small class="text-muted">Obs: ${item.observation}</small>`;
        }

        subtotal += itemTotal;

        $('#table-items').append(`
        <tr>
            <td>
                ${item.name} ${item.quantity > 1 ? `x${item.quantity}` : ''}
                ${crustHtml}
                ${obsHtml}
            </td>
            <td>R$ ${itemTotal.toFixed(2).replace('.', ',')}</td>
        </tr>
    `);
    });


    // Pagamentos (montar com base no array)
    let pagamentosTexto = '';

    if (Array.isArray(pedido.payments) && pedido.payments.length > 0) {
        pagamentosTexto = pedido.payments.map(p => {
            const nome = p.payment_method?.name || '---';
            const valor = parseFloat(p.amount).toFixed(2).replace('.', ',');
            return `${nome} (R$ ${valor})`;
        }).join(', ');
    } else {
        pagamentosTexto = '---';
    }

    $('#table-items').append(`
    <tr>
        <td><strong>Pagamento</strong></td>
        <td>${pagamentosTexto}</td>
    </tr>
`);


    // Taxa de entrega
    if (pedido.delivery_fee > 0) {
        $('#table-items').append(`
            <tr>
                <td><strong>Taxa de entrega</strong></td>
                <td>R$ ${parseFloat(pedido.delivery_fee).toFixed(2).replace('.', ',')}</td>
            </tr>
        `);
    }
    // Troco (se houver)
    if (pedido.change_for && parseFloat(pedido.change_for) > 0) {
        $('#table-items').append(`
        <tr>
            <td><strong>Troco para</strong></td>
            <td>R$ ${parseFloat(pedido.change_for).toFixed(2).replace('.', ',')}</td>
        </tr>
    `);
    }

    // Total geral
    $('#table-items').append(`
        <tr class="table-success">
            <td><strong>Total Geral</strong></td>
          <td><strong>R$ ${(subtotal + parseFloat(pedido.delivery_fee || 0)).toFixed(2).replace('.', ',')}</strong></td>
        </tr>
    `);

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

    // Atribuir motoboy
    $(document).on('click', '.selecionar-motoboy', function () {
        const motoboyId = $(this).data('id');
        const motoboyNome = $(this).data('nome');

        $.post('/pedidos/atribuir-motoboy', {
            _token: $('meta[name="csrf-token"]').attr('content'),
            order_id: pedidoIdSelecionado,
            motoboy_id: motoboyId
        }, function (res) {
            if (res.success) {
                const btnMotoboy = $(`.btn-motoboy[data-id="${pedidoIdSelecionado}"]`);
                btnMotoboy
                    .removeClass('btn-warning')
                    .addClass('btn-success btn-alterar-motoboy')
                    .html('🛵 ' + motoboyNome);

                // Atualiza o botão de status
                const btnStatus = $(`.btn-status[data-id="${pedidoIdSelecionado}"]`);
                btnStatus
                    .text(res.status_name)
                    .css('background-color', res.status_color)
                    .data('current-status', 2)
                    .prop('disabled', false);


                $('#modalMotoboy').modal('hide');
            } else {
                alert('Erro ao atribuir motoboy');
            }
        });
    });

    // ABRIR MODAL DE STATUS AO CLICAR NO BOTÃO
    $(document).on('click', '.btn-status', function () {
        const orderId = $(this).data('id');
        const currentStatus = $(this).data('current-status');

        $('#pedidoIdStatus').val(orderId);
        $('#selectStatus').val(currentStatus); // seleciona o status atual
        $('#modalStatus').modal('show');
    });

    $('#btnSalvarStatus').on('click', function () {
        const orderId = $('#pedidoIdStatus').val();
        const selectedOption = $('#selectStatus option:selected');
        const statusId = selectedOption.val();
        const statusName = selectedOption.data('name');

        if (statusName === 'Cancelado') {
            $('#modalStatus').modal('hide');
            $('#modalMotivoCancelamento')
                .data('order-id', orderId)
                .data('status-id', statusId)
                .modal('show');
            return;
        }

        atualizarStatus(orderId, statusId);
    });


    $('#btnConfirmarCancelamento').on('click', function () {
        const motivo = $('#inputMotivoCancelamento').val().trim();
        const orderId = $('#modalMotivoCancelamento').data('order-id');
        const statusId = $('#modalMotivoCancelamento').data('status-id');

        if (motivo === '') {
            alert('Por favor, informe o motivo do cancelamento.');
            return;
        }

        $('#modalMotivoCancelamento').modal('hide');
        atualizarStatus(orderId, statusId, motivo);
    });


    function atualizarStatus(orderId, statusId, cancelReason = null) {
        const selectedOption = $(`#selectStatus option[value="${statusId}"]`);
        const color = selectedOption.data('color');
        const statusName = selectedOption.data('name');

        $.ajax({
            url: '/pedidos/alterar-status',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                order_id: orderId,
                status_id: statusId,
                cancel_reason: cancelReason
            },
            success: function () {
                const btn = $(`.btn-status[data-id="${orderId}"]`);
                btn.text(statusName);
                btn.data('current-status', statusId);
                btn.removeClass().addClass('btn btn-status')
                    .css('background-color', color)
                    .css('color', '#fff');

                if (statusName === 'Cancelado') {
                    btn.prop('disabled', true);
                }

                $('#modalStatus').modal('hide');
            },
            error: function () {
                alert('Erro ao atualizar status.');
            }
        });
    }



});

//filtros 

$(document).ready(function () {
    function filtrarTabela() {
        const busca = $('#filtro-cliente').val().toLowerCase();
        const status = $('#filtro-status').val();
        const motoboy = $('#filtro-motoboy').val();

        $('#table-order tbody tr').each(function () {
            const textoLinha = $(this).text().toLowerCase();
            const linhaStatus = $(this).find('.btn-status').text().trim();
            const linhaMotoboy = $(this).find('.btn-motoboy').text().trim();
            const dataTexto = $(this).find('td:nth-child(7)').text().trim(); // formato dd/mm/yyyy HH:ii

            let mostrar = true;

            // Filtro cliente
            if (busca && !textoLinha.includes(busca)) {
                mostrar = false;
            }

            // Filtro status
            if (status && linhaStatus !== status) {
                mostrar = false;
            }

            // Filtro motoboy
            if (motoboy && !linhaMotoboy.includes(motoboy)) {
                mostrar = false;
            }


            $(this).toggle(mostrar);
        });
    }

    $('#filtro-cliente, #filtro-status, #filtro-motoboy').on('input change', filtrarTabela);

    $('#limpar-filtros').on('click', function () {
        $('#filtro-cliente').val('');
        $('#filtro-status').val('');
        $('#filtro-motoboy').val('');
        filtrarTabela();
    });
});


