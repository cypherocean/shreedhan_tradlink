$(function () {
    $('#carrier').select2();
}).on('submit', '#form', async function (e) {
    e.preventDefault();
    var form = $(this);
    $('.kt-form__help').html('');
    var formData = new FormData(form[0]);
    // Get the selected value from the Select2 element
    var carrierValue = $('#carrier').val();
    // Add the selected value to the FormData object
    formData.append('carrier', carrierValue);

    let action = form.attr('action');
    let method = form.attr('method');
    try {
        const response = await axios({
            method: method,
            url: action,
            data: formData
        });
        toastr.success('Data inserted successfully.', 'Success');

        // Redirect after a delay
        setTimeout(function () {
            window.location.href = APP_URL + 'users';
        }, 3000); // 3000 milliseconds = 3 seconds

    } catch (error) {
        var error = error.response.data.message
        // console.error(error);
        if (error.response && error.response.data.status === 422) {
            var errors = error.response.data.errors;
            $('.kt-form__help').html('');
            $.each(errors, function (key, value) {
                $('.' + key).html(value);
            });
        }
    }
});