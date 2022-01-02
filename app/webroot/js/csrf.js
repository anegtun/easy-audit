$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('input[name="_csrfToken"]').val() }
    });
});