$(document).on('click', 'a.btn-outline-danger', function (e) {
    if (!confirm('Confirma exclusão?')) {
        e.preventDefault();
    }
});
