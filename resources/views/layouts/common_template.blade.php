@include('layouts.header')

@php
$commonValues = Helper::commonValues();
$configData = Helper::applClasses();
@endphp

@yield('content')
<script>
    var base_url = "{{url('/')}}";
</script>    
@include('layouts.footer')