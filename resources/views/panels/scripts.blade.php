

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

</script>

@toastr_js
@toastr_render
@yield('page-script')
{{-- page script --}}
