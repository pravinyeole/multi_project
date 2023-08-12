$(document).ready(function () {
    if (document.getElementById("editEmail")) {
        // Function for required field.
        jQuery.validator.addMethod("require_field", function(value, element) {
            if(value.trim() ==''){
                return false;
            }
            return true;
        }, "This field is required.");
        // Function for validate email.
        jQuery.validator.addMethod("validate_email", function(value, element) {
            if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
                return true;
            } else {
                return false;
            }
        }, "Please enter valid email.");
        // Form Validation for add Section
        $('.editEmail').validate({
            rules: {
                email_from  : {
                    required: true,
                    validate_email: true,
                },
                email_bcc  : {
                    required: true,
                    validate_email: true,
                },
                name_from  : {
                    required: true,
                },
                email_subject  : {
                    required: true,
                },
            },
        });
    }




    // Databale for Emails
    if (document.getElementById("table_emails")) {
            var table = $('#table_emails').DataTable({
                processing: true,
                serverSide: true,
                order: [0, 'DESC'],
                dom:'<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-3"l><"row col-sm-12  col-md-5 customDropDown"><"col-sm-12 col-md-4"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                ajax: {
                    url : base_url + "/emails",
                    data: function ( data ) {
                    }
                },
                "columns": [
                    {data: 'email_group_action', name: 'email_group_action', searchable: true},
                    {data: 'description', name: 'description', searchable: true},
                    {data: 'options', name: 'options', orderable: false, searchable: false},
                ],
            });

    }
});