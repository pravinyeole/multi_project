$(function () {
    var jqForm = $('#jquery-val-form');
    if (jqForm.length) {
        jqForm.validate({
            rules: {
                'first_name': {
                    required: true
                },
                'last_name': {
                    required: true
                },
                'organization_name': {
                    required: true
                },
                'email': {
                    required: true,
                    email: true
                },
                'password': {
                    required: true
                }
            }
        });
    }
});
