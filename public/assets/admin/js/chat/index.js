var url = window.location.origin;

$('#table-chat').DataTable({
    processing: true,
    serverSide: true,
    "ajax": {
        "url": url + "/atendimento/getChats",
        "type": "GET"
    },
    "columns": [
        { "data": "flow_stage" },
        { "data": "flow_stage" },
        { "data": "flow_stage" }
    ],
    'columnDefs': [
        {
            targets: [2],
            className: 'dt-body-center'
        }
    ],
    'rowCallback': function (row, data, index) {
        let disable = '';
        if (data['flow_stage_label'] == "finalizado") {
            disable = "disabled";
        }

        let nome = data['customer'].name;
        let telefone = data['customer'].phone.replace(/\D/g, ''); // remove tudo que não for número
        let linkWhatsapp = 'https://wa.me/55' + telefone;

        $('td:eq(0)', row).html(
            `<label>${nome} / <a href="${linkWhatsapp}" target="_blank">${data['customer'].phone}</a></label>`
        );


        // status com lógica do tempo
        let statusHtml = '<label>' + data['flow_stage_label'] + '</label>';
        if (data['flow_stage'] != "finalizado") {
            // diferença de tempo
            let updatedAt = new Date(data['updated_at']);
            let agora = new Date();
            let diffMinutos = (agora - updatedAt) / 1000 / 60;

            if (diffMinutos > 3) {
                statusHtml += `<br><span style="color:red; font-weight:bold;">
                    Cliente está a muito tempo fazendo pedido, pode desistir! Atenção!
                </span>`;
            }
        }
        $('td:eq(1)', row).html(statusHtml);

        // ações
        $('td:eq(2)', row).html(
            '<a href="javascript:;" data-toggle="modal" onClick="configModalUp(' + data["id"] + ')" data-target="#modalUp" class="btn btn-sm btn-warning ' + disable + '"> <i class="far fa-edit"></i></a>'
        );
    },
});

function configModalUp(id) {
    $('#id_chat').val(id);
}
