$(document).ready(function () {
    // jQuery.extend(jQuery.validator.messages, {
    //     equalTo: "New password and confirm password should be match.",
    // });
    if (document.getElementById("updatePasswordForm")) {
        // Function for required field.
        jQuery.validator.addMethod("require_field", function(value, element) {
            if(value.trim() ==''){
                return false;
            }
            return true;
        }, "This field is required.");
        jQuery.validator.addMethod("strongPassword", function(value) {
            if(value!=''){
                return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$@!%&*?])[A-Za-z\d#$@!%&*?]/.test(value) && /[a-z]/.test(value) && /\d/.test(value) && /[A-Z]/.test(value);
            }
            return true;
        },"The password must contain at least 1 number, 1 lower case letter, 1 upper case letter and 1 special character.");
        jQuery.validator.addMethod("emailfull", function(value, element) {
            return this.optional(element) || /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i.test(value);
        }, "Please enter a valid email address!");

        // Form Validation for edit organization
        $('.updatePasswordForm').validate({
            rules: {
                // email  : {
                //     require_field: true,
                //     emailfull: true,
                // },
                old_password  : {
                    require_field: true,
                },
                password_confirmation  : {
                    require_field: true,
                    strongPassword: true,
                    equalTo: '#password'
                },
                password  : {
                    require_field: true,
                    strongPassword: true
                },
               
            },
            messages:{
                password_confirmation  : {
                   equalTo : "Password and confirm password should be match."
               },
           },
        });
    }

});