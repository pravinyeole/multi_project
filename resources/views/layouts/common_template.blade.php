@include('layouts.header')

@php
$configData = Helper::applClasses();
@endphp

@yield('content')
<script>
    var base_url = "{{url('/')}}";
</script>    
@include('layouts.footer')