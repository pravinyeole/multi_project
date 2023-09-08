@include('layouts.header')

@php
$configData = Helper::applClasses();
$commonValues = Helper::commonValues();
print_r($commonValues);
@endphp

@yield('content')
<script>
    var base_url = "{{url('/')}}";
</script>    
@include('layouts.footer')