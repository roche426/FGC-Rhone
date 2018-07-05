$(".alert-delete").click(function(e) {
    e.preventDefault();

    let $this = $(this);
    let $message = $this.attr('data-confirm-message') || 'Voulez-vous continuer votre action ?';

    bootbox.confirm({
        message: $message,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
                //redirection
                window.location = $this.attr('href');
            }}
    });
});
