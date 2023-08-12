$(document).ready(function () {
    if (document.getElementById("addPaperCost") || document.getElementById("editPaperCost")) {
        // Function for required field. 
        jQuery.validator.addMethod("require_field", function(value, element) {
            if(value.trim() == ''){
                return false;
            }
            return true;
        }, "This field is required.");

        // Form Validation for add organization
        $('.addPaperCost').validate({
            rules: {
                paper_cost_name        : {
                    require_field: true,
                },
                paper_cost: {
                    require_field: true,
                     digits: true
                }
            },
        });

        // Form Validation for edit organization
        $('.editPaperCost').validate({
            rules: {
                paper_cost_name        : {
                    require_field: true,
                },
                paper_cost        : {
                    require_field: true,
                     digits: true
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
                url : base_url + "/paper_cost",
                data: function ( data ) {
                }
            },
            "columns": [
                {data: 'DT_RowIndex', orderable:false, searchable: false},
                {data: 'paper_cost_name', name: 'paper_cost_name'},
                {data: 'paper_cost', name: 'paper_cost', searchable: false, orderable: true},
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
    $('.editPaperCost').on('submit', function(e) {
        if($(".editPaperCost").valid()){
            $('#loader').show();
            return true;
        }
    });
});