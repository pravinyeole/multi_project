$(document).on('click', '.status', function(e) {
    // var endpoint = base_url+'/'+$(this).data('url')+'/status';
    var endpoint = '{{ route("users.update-status") }}';
    var token = $("input[name='_token']").val();
    var message = "Are you sure you want to change the status?";
    var id = $(this).data('id');
    var type = $(this).data('type');
    bootbox.confirm({
        title: "Status",
        message: message,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-danger'
            },
            cancel: {
                label: 'No',
                className: 'btn-secondary'
            }
        },
        callback: function (result) {
            if (result == true) {
                $('#loader').show();
                $.ajax({
                    url: endpoint,
                    method: 'POST',
                    data: {
                        '_token': token,
                        'id': id,
                        'type': type,
                    },
                    dataType: "json",
                    success: function (data) {
                        if(data.title == 'Error'){
                            $('#loader').hide();
                            toastr.error(data.message, data.title);
                        }else{
                            toastr.success(data.message, data.title);
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        }
                    }
                })
            }
        }
    });
});


// Delete record
$(document).on('click', '.delete', function (e) {
    $('nav').addClass('adjust_navbar');
    var model = $(this).data('model');
    console.log(model);
    var token = jQuery("input[name='_token']").val();
    var message = "Are you sure you want to delete this record?";
    var id = $(this).data('id');
    var endpoint = base_url + '/common/delete';

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
                        'id': id,
                        'model': model,
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

// File Master -> Add/Update
$(document).on('change', '.column_master', function(e) {
    var columns = $('.column_master').map((_,el) => el.value).get();
    var val = $(this).val();
    var valcount = getOccurrence(columns, val);
    if(valcount > 1){
        $(this).val('');
        bootbox.alert("You have already selected this column. Please select another");
    }
});

//
$(document).on('change', '.company_id', function(e) {
    var columns = $('.company_id').map((_,el) => el.value).get();
    var val = $(this).val();
    var id = $(this).data('id');
    var valcount = getOccurrence(columns, val);
    if(valcount > 1){
        $(this).val('');
        bootbox.alert("You have already selected this company. Please select another");
    }
    else{
        activateSelect2(id);
        var token = $("input[name='_token']").val();
        $.post(base_url + '/organization-company/getCarriers', 
            {
                company_id: $(this).val(), _token: token
            }, 
            function (result) {
                jQuery('.carrier_ids'+id).empty().append(result); 
            // $('.carrier_ids'+id).html(result);
        });
    }
});

// count value in array
function getOccurrence(array, value) {
    return array.filter((v) => (v === value)).length;
}

function activateSelect2(id){
    var select = $("#column_master"+id);
    select.attr('multiple','true');
    select.each(function () {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>');
        $this.select2({
          // the following code is used to disable x-scrollbar when click in select input and
          // take 100% width in responsive also
          dropdownAutoWidth: true,
          width: '100%',
          placeholder: 'Select',
          dropdownParent: $this.parent()
        });
    });
}

$('.select2').on("select2:select", function (e) { 
    var data = e.params.data.text;
    if(data=='all'){           
        $(".select2 > option").prop("selected","selected");
        $('.select2 option[value=all]').prop('selected', false);
        $(".select2").trigger("change");
    }
});

// get client according to client
$(document).on('change', '.carrier_id', function(e){
    $('#loader').show();
    var val = $(this).val();
    var endpoint = base_url + '/carrier/getClients';
    var token = jQuery("input[name='_token']").val();
    $.ajax({
        url: endpoint,
        method: 'POST',
        data: {
            '_token': token,
            'carrier_id': val,
        },
        dataType: "json",
        success: function (result) {
            var len =(result.data).length;
            $('#client_id').empty();
            if(len !== 0){  
                $('#client_id').append('<option value="">Select</option>');
                for(var i=0; i<len; i++){
                    $('#client_id').append('<option value="'+result.data[i].client_id+'">'+result.data[i].client_name+'-'+result.data[i].client_id+'</option>');
                }
            }else{
                $('#client_id').append('<option value="" selected>No clients</option>');
            } 
            $('#loader').hide();
        }
    });
});

// update payment method default status
$(document).on('click', '.pdefault', function(e) {
    var endpoint = base_url+'/'+$(this).data('url')+'/status';
    var token = $("input[name='_token']").val();
    var message = "Are you sure you want to make this as default payment method?";
    var id = $(this).data('id');
    var type = $(this).data('type');
    bootbox.confirm({
        title: "Payment Method",
        message: message,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-danger'
            },
            cancel: {
                label: 'No',
                className: 'btn-secondary'
            }
        },
        callback: function (result) {
            if (result == true) {
                $('#loader').show();
                $.ajax({
                    url: endpoint,
                    method: 'POST',
                    data: {
                        '_token': token,
                        'id': id,
                        'type': type,
                    },
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        if(data.title == 'Error'){
                            $('#loader').hide();
                            toastr.error(data.message, data.title);
                        }else{
                            toastr.success(data.message, data.title);
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        }
                    }
                })
            }
        }
    });
});

$(document).on('click', '.pdelete', function(e) {
    var endpoint = base_url + '/' + $(this).data('url') + '/delete';
    var token = jQuery("input[name='_token']").val();
    var message = "Are you sure you want to delete this payment method?";
    var id = $(this).data('id');
    var type = $(this).data('type');
    bootbox.confirm({
        title: "Delete",
        message: message,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-danger'
            },
            cancel: {
                label: 'No',
                className: 'btn-secondary'
            }
        },
        callback: function (result) {
            if (result == true) {
                $('#loader').show();
                $.ajax({
                    url: endpoint,
                    method: 'POST',
                    data: {
                        '_token': token,
                        'id': id,
                        'type': type,
                    },
                    dataType: "json",
                    success: function (data) {
                        $('#loader').hide();
                        if(data.title == 'Error'){
                            toastr.error(data.message, data.title);
                        }else{
                            toastr.success(data.message, data.title);
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        }
                    }
                })
            }
        }
    });
});

$(document).on('click', '.resend_invitaion', function(e) {
    var endpoint = base_url + '/' + $(this).data('url');
    var token = jQuery("input[name='_token']").val();
    var message = "Are you sure you want to resend invitation to user?";
    var id = $(this).data('id');
    // var type = $(this).data('type');
    bootbox.confirm({
        title: "Resend Invitation",
        message: message,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-danger'
            },
            cancel: {
                label: 'No',
                className: 'btn-secondary'
            }
        },
        callback: function (result) {
            if (result == true) {
                $('#loader').show();
                $.ajax({
                    url: endpoint,
                    method: 'POST',
                    data: {
                        '_token': token,
                        'id': id,
                        // 'type': type,
                    },
                    dataType: "json",
                    success: function (data) {
                        $('#loader').hide();
                        if(data.title == 'Error'){
                            toastr.error(data.message, data.title);
                        }else{
                            toastr.success(data.message, data.title);
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        }
                    }
                })
            }
        }
    });
});