{{-- Vendor Scripts --}}
<script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>

@yield('vendor-script')
{{-- Theme Scripts --}}
<script src="{{ asset(mix('js/core/app-menu.js')) }}"></script>
<script src="{{ asset(mix('js/core/app.js')) }}"></script>
@if($configData['blankPage'] === false)
<script src="{{ asset(mix('js/scripts/customizer.js')) }}"></script>

@endif
<script type="text/javascript">
    $(document).ready(function () {
        var timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        $("#timeZone").val(timeZone);
    });
</script>
{{-- page script --}}
<script src="{{ asset('js/scripts/custom.js') }}"></script>
<script>
    $(document).on('click', '.logout', function (e ) {
       var message = "Are you sure you want to logout?";
       var id = $(this).data('id');
       bootbox.confirm({
           title: "Logout",
           message: message,
           buttons: {
               confirm: {
                   label: 'Yes',
                   className: 'btn-primary'
               },
               cancel: {
                   label: 'No',
                   className: 'btn-danger'
               }
           },
           callback: function (result) {
               if(result == true){
                   var urls="{{url('logout')}}";
                   window.location.href = urls;
               }
           }
       });
   });

   $(document).on('click', '.existAccess', function (e ) {
            var message = "Your current session will expire, Please click below to continue.";
            var id = $(this).data('logoutuserid');
            bootbox.confirm({
                title: "Session Out",
                message: message,
                buttons: {
                    confirm: {
                        label: 'Continue',
                        className: 'btn-primary'
                    },
                    cancel: {
                        label: 'Close',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if(result == true){
                        var urls="{{url('insurance-agency/get-return-login/')}}/"+id;
                        window.location.href=urls;
                    }
                }
            });
    });

   $(document).on('click', '.changeAgency', function (e ) {
        var message = "Are you sure you want to change Agency?";
        bootbox.confirm({
            title: "Change Agency",
            message: message,
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-primary'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if(result == true){
                    var urls="{{url('/changeAgency')}}";
                    window.location.href=urls;
                }
            }
        });
    });

</script>

@toastr_js
@toastr_render
<!-- <script>
      toastr.options = {
      "positionClass": "toast-top-center",
    }
</script> -->
@yield('page-script')
{{-- page script --}}
