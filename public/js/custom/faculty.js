$(document).ready(function () {
    
    if (document.getElementById("addDepartment") || document.getElementById("editDepartment")) {
        // Function for required field. 
        jQuery.validator.addMethod("require_field", function(value, element) {
            if(value.trim() == ''){
                return false;
            }
            return true;
        }, "This field is required.");

        // Form Validation for add organization
        $('.addDepartment').validate({
            rules: {
                name        : {
                    require_field: true,
                },
                email: {
                    require_field: true,
                    email:true
                },
                mobile: {
                    require_field: true,
                },
                 mobile: {
                     require_field: true,
                    number: true,
                    minlength: 10,
                    maxlength: 10
                },
                  department        : {
                    require_field: true,
                },
                   bank_account        : {
                       require_field: true,
                        number: true,
                        minlength: 6,
                        maxlength: 16
                },
                    ac_type        : {
                        require_field: true,
                        pattern: /^(savings|current)$/i
                },
                     ifsc_code        : {
                        require_field: true,
                        pattern: /^[A-Za-z]{4}\d{7}$/i,
                        maxlength: 11
                },
                       designation        : {
                    require_field: true,
                },
                 file: {
        required: true,
        extension: 'xls|xlsx'
      }
            },
            messages: {
                ifsc_code: {
                    required: "Please enter your IFSC code",
                    pattern: "Please enter a valid IFSC code",
                    maxlength: "IFSC code should be of maximum 11 characters"
                },
                file: {
        required: 'Please select a file',
        extension: 'Only Excel files are allowed'
      }
                }

        });

        // Form Validation for edit organization
        $('.editDepartment').validate({
            rules: {
                department_name        : {
                    require_field: true,
                },
                department_status        : {
                    require_field: true,
                }
            },
        });
        
    }
    
    // Databale for organization
    if (document.getElementById("table_department")) {
        var table = $('#table_department').DataTable({
            processing: true,
            serverSide: true,
            order: [1, 'ASC'],
            dom:'<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-3"l><"row col-sm-12  col-md-5 customDropDown"><"col-sm-12 col-md-4"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            ajax: {
                url : base_url + "/faculty",
                data: function ( data ) {
                }
            },
            "columns": [
                {data: 'DT_RowIndex', orderable:false, searchable: false},
                {data: 'name', name: 'name', searchable: false, orderable: false,},
                {data: 'department_name', name: 'department_name'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
        });
    }

    $('.addDepartment').on('submit', function(e) {
        if($(".addDepartment").valid()){
            $('#loader').show();
            return true;
        }
    });
    $('.editDepartment').on('submit', function(e) {
        if($(".editDepartment").valid()){
            $('#loader').show();
            return true;
        }
    });

    // Delete record
    $(document).on('click', '.delete_faculty', function (e) {
        $('nav').addClass('adjust_navbar');
        var token = jQuery("input[name='_token']").val();
        var message = "Are you sure you want to delete All the faculties";
        var id = $(this).data('id');
        var endpoint = base_url + '/faculty/delete_all';

        bootbox.confirm({
            title: "Delete",
            message: message,
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
                $('nav').removeClass('adjust_navbar');
                if (result == true) {
                    $('#loader').show();
                    $.ajax({
                        url: endpoint,
                        method: 'POST',
                        data: {
                            '_token': token,
                        },
                        dataType: "json",
                        success: function (data) {
                            $('#loader').hide();
                            if (data.title == 'Error') {
                                toastr.error(data.message, data.title);
                            } else {
                                toastr.success(data.message, data.title);
                            }
                            window.location.reload();
                        }
                    })
                }
            }
        });
    });
});