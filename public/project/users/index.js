var datatable;

$(function () {
    if ($('#data-table').length > 0) {
        datatable = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            "responsive": true,
            "aaSorting": [],
            "ajax": {
                "url": APP_URL + "users",
                "type": "POST",
                "dataType": "json",
            },
            columnDefs: [{
                // For Responsive
                className: 'control',
                orderable: false,
                responsivePriority: 2,
                targets: 0
            },
            {
                responsivePriority: 1,
                targets: 4
            },
            {
                // Label
                targets: -2,
                render: function (data, type, full, meta) {
                    var $status_number = full['status'];
                    var $status = {
                        1: {
                            title: 'Active',
                            class: 'badge-light-success'
                        },
                        2: {
                            title: 'In-Active',
                            class: ' badge-light-danger'
                        },
                    };
                    if (typeof $status[$status_number] === 'undefined') {
                        return data;
                    }
                    return (
                        '<span class="badge rounded-pill ' +
                        $status[$status_number].class +
                        '">' +
                        $status[$status_number].title +
                        '</span>'
                    );
                }
            },
            {
                // Actions
                targets: -1,
                title: 'Actions',
                orderable: false,
                render: function (data, type, full, meta) {
                    return (
                        '<div class="d-inline-flex">' +
                        '<a class="pe-1 dropdown-toggle hide-arrow text-primary" data-bs-toggle="dropdown">' +
                        feather.icons['more-vertical'].toSvg({
                            class: 'font-small-4'
                        }) +
                        '</a>' +
                        '<div class="dropdown-menu dropdown-menu-end">' +
                        '<a href="javascript:;" data-id="' + full.id + '" data-status="active" class="dropdown-item change-status">Active</a>' +
                        '<a href="javascript:;" data-id="' + full.id + '" data-status="inactive" class="dropdown-item change-status">Inactive</a>' +
                        '<a href="javascript:;" data-id="' + full.id + '" data-status="deleted" class="dropdown-item change-status">Delete</a>' +
                        '</div>' +
                        '</div>' +
                        '<a href="' + APP_URL + 'users/edit/' + full.id + '" class="item-edit">' +
                        feather.icons['edit'].toSvg({
                            class: 'font-small-4'
                        }) +
                        '</a>'
                    );
                }
            }
            ],
            language: {
                paginate: {
                    // remove previous & next text from pagination
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            },
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'carrier',
                name: 'carrier'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
            },
            ]
        });
    }
}).on('click', '.change-status', function () {
    var id = $(this).data("id");
    var status = $(this).data("status");
    changeStatus(id, status);
});

function changeStatus(id, status) {
    var msg = "Are you sure?";

    if (confirm(msg)) {
        $.ajax({
            "url": APP_URL + "users/change-status",
            "dataType": "json",
            "type": "POST",
            "data": {
                id: id,
                status: status,
            },
            success: function (response) {
                if (response.code == 200) {
                    datatable.ajax.reload();
                    toastr.success('Record status changed successfully.', 'Success');
                } else {
                    toastr.error('Failed to delete record.', 'Error');
                }
            }
        });
    }
}