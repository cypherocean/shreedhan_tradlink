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
                "url": APP_URL + "get-upcoming-recharges",
                "type": "POST",
                "dataType": "json",
            },
            columnDefs: [
                {
                    // Actions
                    targets: -1,
                    title: 'Actions',
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return (
                            `<div class="d-inline-flex justify-content-center align-items-center demo-inline-spacing">
                            <button
                                class="btn btn-icon rounded-circle btn-outline-primary waves-effect repeat-recharge"
                                data-id="${full.id}"
                                data-bs-toggle="tooltip"
                                title=""
                                data-bs-original-title="Repeat Recharge"
                            >
                            ${feather.icons['check'].toSvg({
                                class: 'font-small-4'
                            })}
                            </button>
                            <button class="btn btn-icon rounded-circle btn-outline-danger waves-effect skip-recharge" data-id="${full.id}" data-bs-toggle="tooltip" title="" data-bs-original-title="Skip for this month">
                            ${feather.icons['x'].toSvg({
                                class: 'font-small-4'
                            })}
                            </button>
                        </div>`
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
                data: 'amount',
                name: 'amount'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
            },
            ]
        });
    }
    calculateTime();
    setInterval(calculateTime, 5000);
}).on('click', '.repeat-recharge', function () {
    var id = $(this).data('id');
    var msg = "Are you sure?";
    Swal.fire({
        title: msg,
        text: "This will repeat the last recharge.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Proceed.',
        allowOutsideClick: false,
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-outline-danger ms-1'
        },
        buttonsStyling: false
    }).then(function (result) {
        if (result.value) {
            axios.post(APP_URL + "recharge/repeat-recharge", {
                id: id,
            }).then(response => {
                var response = response.data;
                datatable.ajax.reload();
                toastr.success(response.message, 'Success');
            }).catch(error => {
                var error = error.response.data;
                toastr.error(error.message, 'Error');
            });
        }
    });
}).on('click', '.skip-recharge', function () {
    var id = $(this).data('id');
    alert(id)
});

function calculateTime() {
    var fetchedTime = $('#currentTime').val();
    var timeAgo = moment(fetchedTime).fromNow();
    $('.fetchedTime').html(`Updated ${timeAgo}`);
}