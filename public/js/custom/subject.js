$(document).ready(function () {
    if (document.getElementById("addSubject") || document.getElementById("editSubject")) {
        // Function for required field. 
        jQuery.validator.addMethod("require_field", function(value, element) {
            if(value.trim() == ''){
                return false;
            }
            return true;
        }, "This field is required.");

        // Form Validation for add organization
        $('.addSubject').validate({
            rules: {
                department_name        : {
                    require_field: true,
                },
                department_status: {
                    require_field: true,
                }
            },
        });

        // Form Validation for edit organization
        $('.editSubject').validate({
            rules: {
                department_name        : {
                    require_field: true,
                },
                department_status        : {
                    require_field: true,
                }
            },
        });
        
        // // get state according to country id
        // $(".country_id").change(function () {
        //     var endpoint = base_url + '/common/getStateById';
        //     var id = $(this).val();
        //     getState(id);
        // });

        // // get state according to selected country id
        // if ($("#country_id").val()) {
        //     var id = $("#country_id").val();
        //     getState(id);
        // }
    }
    
    // Databale for organization
    if (document.getElementById("table_department")) {
        var table = $('#table_department').DataTable({
            processing: true,
            serverSide: true,
            order: [1, 'ASC'],
            dom:'<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-3"l><"row col-sm-12  col-md-5 customDropDown"><"col-sm-12 col-md-4"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            ajax: {
                url : base_url + "/subject",
                data: function (data) {
                }
            },
            "columns": [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                {data: 'department_name', name: 'department_id'},
                {data: 'class_name', name: 'class_id'},
                {data: 'subject_name', name: 'subject_name'},
                {data: 'subject_status', name: 'status', searchable: false, orderable: false,},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
        });
    }

    $('.addSubject').on('submit', function(e) {
        if($(".addSubject").valid()){
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

      $(document).on('click', '.delete_subject', function (e) {
        $('nav').addClass('adjust_navbar');
        var token = jQuery("input[name='_token']").val();
        var message = "Are you sure you want to delete All the faculties";
        var id = $(this).data('id');
        var endpoint = base_url + '/subject/delete_all';

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