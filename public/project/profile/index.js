$(function () {

}).on('submit', '#form', async function (e) {
    e.preventDefault();
    var form = $(this);
    $('.kt-form__help').html('');
    var formData = new FormData(form[0]);

    // Manually append the file input to FormData if needed
    var fileInput = document.getElementById('customFile');
    if (fileInput.files.length > 0) {
        formData.append('customFile', fileInput.files[0]);
    }

    let action = form.attr('action');
    let method = form.attr('method');
    try {
        await axios({
            method: method,
            url: action,
            data: formData
        });
        toastr.success('Profile updated successfully.', 'Success');

        // Redirect after a delay
        setTimeout(function () {
            window.location.href = APP_URL + '/dashboard';
        }, 3000); // 3000 milliseconds = 3 seconds

    } catch (error) {
        if (error.response && error.response.status === 422) {
            var errors = error.response.data.errors;
            $('.kt-form__help').html('');
            $.each(errors, function (key, value) {
                $('.' + key).html(value);
            });
        }
    }
}).on('change', '#customFile', function (e) {
    var file = this.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.previewImage').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
    } else {
        $('.previewImage').attr('src', defaultImage);
    }
});