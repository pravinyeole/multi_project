$(document).ready(function () {
    if (document.getElementById("updateProfile")) {
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
        $('.updateProfile').validate({
            rules: {
                first_name  : {
                    required: true,
                },
                last_name  : {
                    required: true,
                },
                email  : {
                    required: true,
                    validate_email: true,
                },
                user_phone_no:{
                    minlength: 14
                }
            },
            messages:{
                user_phone_no:{
                    minlength: "Please enter valid phone no."
                }
            }
        });
    }
});