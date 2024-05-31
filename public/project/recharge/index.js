var datatable;

$(function () {
    $('#user').select2({
        dropdownParent: '#recharge',
        placeholder: '-- select --',
        tags: true
    });
    if ($('#data-table').length > 0) {
        datatable = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            "responsive": true,
            "aaSorting": [],
            "ajax": {
                "url": APP_URL + "recharge",
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
                            `<div class="d-inline-flex justify-content-center align-items-center">

                            <div class="form-check form-check-inline" title="Active Recharge Notification">
                                <input class="form-check-input change-status" data-id="${full['id']}" data-status="${(full['is_notification_active'])}" type="checkbox" id="inlineCheckbox1" ${(full['is_notification_active'] == '1') ? "checked" : ''}>
                            </div>
                            <span class="item-edit recharge-edit" data-id="${full.id}">
                            ${feather.icons['edit'].toSvg({
                                class: 'font-medium-4'
                            })}
                        </span></div>`
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
}).on('click', '.change-status', function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    var status = $(this).data("status");
    changeStatus(id, status);
}).on('click', '#add_recharge', function () {
    resetForm("#rechargeForm");
    $('#recharge').modal('toggle');
}).on('change', '#user', async function () {
    $("#carrier").val(null);
    var id = $('#user').val();
    if (id != null && id != '-- Select User --') {
        await axios.post(APP_URL + 'recharge/get_user_details', {
            id: id
        }).then(response => {
            if (response.status == 200) {
                var carrier = response.data.data.carrier;
                var number = response.data.data.number;
                $("#carrier").val(carrier);
                $("#number").val(number);
            } else {
                $("#carrier").val(null);
                $("#number").val(null);
            }
        }).catch(error => {
            $("#carrier").val(null);
            $("#number").val(null);
        });
    } else {
        $("#carrier").val(null);
        $("#number").val(null);
    }
}).on('submit', '#rechargeForm', async function (e) {
    e.preventDefault();

    var form = $(this);
    $('.kt-form__help').html('');
    var formData = new FormData(form[0]);
    // Get the selected value from the Select2 element
    var user = $('#user').val();
    var activeNotification = $('#active-notification').is(":checked");

    // Add the selected value to the FormData object
    formData.append('user_id', user);
    formData.append('activeNotification', activeNotification);

    let action = form.attr('action');
    let method = form.attr('method');
    await axios({
        method: method,
        url: action,
        data: formData
    }).then(response => {
        if (response.status == 200) {
            var response = response.data;
            toastr.success(response.message, 'Success');
            $("#recharge").modal('toggle');
            resetForm("#rechargeForm");
            datatable.ajax.reload();
        } else {
            var response = response.data;
            toastr.error(response.message, 'Error');
        }
    }).catch(error => {
        if (error.response && error.response.status === 422) {
            var errors = error.response.data.errors;
            $('.kt-form__help').html('');
            $.each(errors, function (key, value) {
                $(`.${key}-error`).html(value);
            });
        }
    });
}).on('click', '.recharge-edit', async function (e) {
    resetForm("#rechargeForm");
    var rechargeId = $(this).data('id');
    await axios.get(APP_URL + 'recharge/edit/' + rechargeId)
        .then(response => {
            if (response.status == 200) {
                var data = response.data.data;
                var rechargeModal = $('#recharge');
                rechargeModal.find('.modal-title').html('Edit Recharge');
                rechargeModal.find('.modal-subtitle').html('Edit Recharge Details.');
                rechargeModal.find('#rechargeForm').append(`<input type="hidden" id="hiddenId" name="id" value="${data.id}"/>`);
                $("#user").val(null);
                if (data.subscriber_id) {
                    $("#user").val(data.subscriber_id);
                }
                $("#user").trigger('change');

                if (data.carrier) {
                    $("#carrier").val(data.carrier);
                }

                if (data.amount) {
                    $("#amount").val(data.amount);
                }

                if (data.validity) {
                    $("#validity").val(data.validity);
                }

                if (data.is_notification_active) {
                    if (data.is_notification_active === 'yes') {
                        $("#active-notification").attr('checked', true);
                    }
                }

                $('#recharge').modal('toggle');
            } else {
                toastr.error('Something went wrong!');
            }
        }).catch(error => {
            toastr.error('Something went wrong!');
        })
});

function changeStatus(id, status) {
    var msg = "Are you sure?";
    Swal.fire({
        title: msg,
        text: "You won't get notified on dashboard!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, change it!',
        allowOutsideClick: false,
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-outline-danger ms-1'
        },
        buttonsStyling: false
    }).then(function (result) {
        if (result.value) {
            axios.post(APP_URL + "recharge/change-status", {
                id: id,
                status: status,
            }).then(response => {
                if (response.status == 200) {
                    datatable.ajax.reload();
                    toastr.success('Record status changed successfully.', 'Success');
                } else {
                    toastr.error('Failed to delete record.', 'Error');
                }
            }).catch(error => {
                toastr.error('Failed to delete record.', 'Error');
            });
        }
    });
}

function resetForm(formElement) {
    $(formElement).trigger("reset");
    $(formElement).find('.modal-title').html('Add Recharge');
    $(formElement).find('.modal-subtitle').html('Add Recharge Details.');
    $(formElement).find('#hiddenId').remove();
    $('#user').val(null).trigger('change');
}