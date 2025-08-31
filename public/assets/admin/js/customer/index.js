var url = window.location.origin;
$('#table-customer').DataTable({
    processing: true,
    serverSide: true,
    "ajax": {
        "url": url + "/clientes/getCustomers",
        "type": "GET"
    },
    "columns": [
        { "data": "name" },
        { "data": "phone" },
        { "data": "display_created_at" },
        { 
            "data": "id",
            "orderable": false,
            "searchable": false,
            "render": function(data, type, row) {
                var deleteButton = '<div class="btn-acoes"><a href="javascript:;" data-toggle="modal" onClick="configModalDelete(' + data + ')" data-target="#modalDelete" class="btn btn-sm btn-danger delete"><i class="far fa-trash-alt"></i></a>';
                var editButton = '<a href="' + url + '/clientes/editar/' + data + '" class="btn btn-sm btn-info edit"><i class="far fa-edit"></i></a></div>';
                return deleteButton + editButton;
            }
        }
    ],
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
    },
    "order": [[2, "desc"]] // Order by created date descending
});

function configModalDelete(id) {
    // Defina o valor do input hidden com o ID do registro a ser excluído
    document.getElementById('id_survey_deleta').value = id;

    // Atualize a ação do formulário para incluir o ID correto
    var form = document.querySelector('#modalDelete form');
    form.action = form.action.replace('{id}', id);
}

