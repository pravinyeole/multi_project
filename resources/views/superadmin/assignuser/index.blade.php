<!-- common_template/contentLayoutMaster -->
@extends('layouts/common_template')
<style>
    .scrolldiv {
        max-height: 450px;
        overflow: auto;
        overflow-y: auto;
        ;
    }

    /*----------------genealogy-scroll----------*/
    .genealogy-scroll::-webkit-scrollbar {
        width: 5px;
        height: 8px;
    }

    .genealogy-scroll::-webkit-scrollbar-track {
        border-radius: 10px;
        background-color: #e4e4e4;
    }

    .genealogy-scroll::-webkit-scrollbar-thumb {
        background: #212121;
        border-radius: 10px;
        transition: 0.5s;
    }

    .genealogy-scroll::-webkit-scrollbar-thumb:hover {
        background: #d5b14c;
        transition: 0.5s;
    }

    /*----------------genealogy-tree----------*/
    .genealogy-body {
        white-space: nowrap;
        overflow-y: hidden;
        min-height: 500px;
        padding-top: 10px;
        text-align: center;
    }

    .genealogy-tree {
        display: inline-block;
    }

    .genealogy-tree ul {
        position: relative;
        padding-left: 0px;
        display: flex;
        justify-content: center;
    }

    .genealogy-tree li {
        float: left;
        text-align: center;
        list-style-type: none;
        position: relative;
        padding: 15px 35px 0px 35px;
    }

    .genealogy-tree li::before,
    .genealogy-tree li::after {
        content: '';
        position: absolute;
        top: 0;
        right: 50%;
        border-top: 2px solid #ccc;
        width: 50%;
        height: 18px;
    }

    .genealogy-tree li::after {
        right: auto;
        left: 50%;
        border-left: 2px solid #ccc;
    }

    .genealogy-tree li:only-child::after,
    .genealogy-tree li:only-child::before {
        display: none;
    }

    .genealogy-tree li:only-child {
        padding-top: 0;
    }

    .genealogy-tree li:first-child::before,
    .genealogy-tree li:last-child::after {
        border: 0 none;
    }

    .genealogy-tree li:last-child::before {
        border-right: 2px solid #ccc;
        border-radius: 0 5px 0 0;
        -webkit-border-radius: 0 5px 0 0;
        -moz-border-radius: 0 5px 0 0;
    }

    .genealogy-tree li:first-child::after {
        border-radius: 5px 0 0 0;
        -webkit-border-radius: 5px 0 0 0;
        -moz-border-radius: 5px 0 0 0;
    }

    .genealogy-tree ul ul::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        border-left: 2px solid #ccc;
        width: 0;
        height: 20px;
    }

    .genealogy-tree li a {
        text-decoration: none;
        color: #666;
        font-family: arial, verdana, tahoma;
        font-size: 11px;
        display: inline-block;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
    }

    .genealogy-tree li a:hover+ul li::after,
    .genealogy-tree li a:hover+ul li::before,
    .genealogy-tree li a:hover+ul::before,
    .genealogy-tree li a:hover+ul ul::before {
        border-color: #fbba00;
    }

    /*--------------memeber-card-design----------*/
    .member-view-box {
        padding: 0px 20px;
        text-align: center;
        border-radius: 4px;
        position: relative;
    }

    .member-image {
        width: 60px;
        position: relative;
    }

    .member-image img {
        width: 60px;
        height: 60px;
        border-radius: 6px;
        background-color: #000;
        z-index: 1;
    }
</style>
@section('title', $title)
@section('vendor-style')
@endsection

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                @if(isset($getOldUser))
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" id="autocheck" value="0">
                        <button type="button" class="btn btn-danger col-sm-3" id="auto-assign-checkbox">Auto Assign User</button>
                        <button class="btn btn-outline-info btn-fw col-sm-5" type="button" style="text-transform: uppercase;text-align: center;margin: 0px 10px 0px 10px;">Assign Users Form </button>
                        <button type="button" form="manaualAssign" class="btn btn-success col-sm-3" id="assign_user_submit">Submit Assigned User</button>
                    </div>
                    <div class="card-body">
                        <div id="message_div"></div>
                        @if (Session::has('invalidId'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <strong>Invalid ID !</strong> {{ str_replace(['[',']','"',"'"],'',session('invalidId')) }}
                        </div>
                        @endif
                        @if (Session::has('validMapped'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <strong>Valid ID !</strong> {{ str_replace(['[',']','"',"'"],'',session('validMapped')) }}
                        </div>
                        @endif
                        @if (Session::has('alreadyMapped'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <strong>ALready Mapped !</strong> {{ str_replace(['[',']','"',"'"],'',session('alreadyMapped')) }}
                        </div>
                        @endif
                        <div class="col-sm-12 row">
                            <div class="col-sm-4 scrolldiv">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <!-- {{$from_date.'-'.$to_date}} -->
                                            <th>Added On {{date('d F Y',strtotime($from_date))}}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="manaualAssignRadio">
                                        @foreach ($getOldUser as $key => $user)
                                            @if(!in_array($user->mobile_id,$userIds))
                                            <tr>
                                                <td>
                                                    <input type="radio" name="user_id" id="{{ $user->id }}_{{ $user->mobile_id }}" data-username="{{ $user->mobile_id }}" data-oid="{{ $user->id }}">
                                                    <label radiovalue="{{ $user->id }}_{{ $user->mobile_id }}" radioname="{{ $user->user_fname }}_{{ $user->user_lname }}" class="col-sm-4 control-label" id="manaual_radio_{{$key}}">
                                                        <center>{{ $user->mobile_id }} ({{ $user->user_fname }} {{ $user->user_lname }})</center>
                                                    </label>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-4 scrolldiv" id="htmlassign">
                            </div>
                            <div class="col-sm-4 scrolldiv">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <!-- $from_date_one.'-'.$to_date_one -->
                                        <th>Added On {{date('d F Y',strtotime($from_date_one))}}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="manaualAssignCheck">
                                        @php $r=0; @endphp
                                        @foreach ($getRecentlyJoinUser as $recentUser)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="new_user_id" value="{{ $recentUser->id }}" data-checkname="{{ $recentUser->user_fname }}_{{ $recentUser->user_lname }}" data-nid="{{ $recentUser->id }}">
                                                <label checkvalue="{{ $recentUser->id }}" checkname="{{ $recentUser->user_fname }}_{{ $recentUser->user_lname }}" class="col-sm-4 control-label" id="manuel_check_{{$r}}">
                                                    <center>{{ $recentUser->user_fname }} {{ $recentUser->user_lname }} ({{ $recentUser->mobile_number }})</center>
                                                </label>
                                            </td>
                                        </tr>
                                        @php $r++;@endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                @else
                <div class="card-header">
                    <h4 class="card-title">No Users for Assigned</h4>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<form class="form-horizontal" action="{{ route('superadmin.save-assigne-user') }}" method="POST" id="manaualAssign">
    @csrf
    <input type="hidden" name="type" value="GH">
    <div id="assign_inputs">

    </div>
</form>
@endsection

@section('page-script')
<script>
    var assignee_user_array = [];
    var assignee_user_one = [];
    var htmlassign_one = '<div class="body genealogy-body genealogy-scroll"><div class="genealogy-tree">';
    $(document).ready(function() {
        $('#auto-assign-checkbox').on('click', function() {
            if($('#assign_inputs input').length >= 1){
                var msg = 'You Lost Manual Assign Data, Confirm ? ';
                var result = confirm(msg);
                if (result) {
                    $('#assign_inputs').empty();
                    $('#htmlassign').html('');
                    $('#htmlassign').empty();
                    var htmlassign_one = '';
                    htmlassign_one = '<div class="body genealogy-body genealogy-scroll"><div class="genealogy-tree">';
                    $('#assign_inputs').empty();
                    $( 'input[type="checkbox"]' ).each(function( index ) {
                        $('input[type="checkbox"]').attr("disabled", false);
                        $('input[type="checkbox"]').prop('checked', false);
                    });
                    if ($('#autocheck').val() == 1) {
                        location.reload();
                    }
                }else{
                    return false;
                }
            }
                var i = 1;
                var fr = 0;
                var to = 2;
                var htmlassign = '';
                if ($('#autocheck').val() == 0) {
                    $('#auto-assign-checkbox').text('Reset Values');
                    $('#autocheck').val(1);
                    $('#htmlassign').html();
                    var labelCount = $('#manaualAssignRadio label').length;
                    htmlassign += '<div class="body genealogy-body genealogy-scroll"><div class="genealogy-tree">';
                    for (j = 0; labelCount > j;) {
                        var assignee_user = [];
                        var user = $('#manaual_radio_' + j).attr('radiovalue');
                        var user_name = $('#manaual_radio_' + j).attr('radioname');
                        var userArray = user.split("_");
                        assignee_user[user] = [];
                        if (i == 1) {
                            i++;
                        } else {
                            // fr = fr + 1;
                            to = fr + 2;
                        }
                        htmlassign += '<ul class="active"><li><div class="member-details"><h5>' + userArray[1] + '</h5></div><ul>';
                        for (var k = fr; fr < to;) {
                            var assignee = $('#manuel_check_' + fr).attr('checkvalue');
                            if (assignee != undefined) {
                                var assignee_name = $('#manuel_check_' + fr).attr('checkname');
                                htmlassign += '<li><div class="member-details"><h5>' + assignee_name + '</h5></div></li>';
                                assignee_user[user].push(assignee);
                            }
                            fr++;
                        }
                        htmlassign += '</ul></li></ul>';
                        // break;
                        j++;
                        assignee_user_array.push(assignee_user);
                        $('#manaualAssign #assign_inputs').append('<input type="text" name="' + user + '[]" value="' + assignee_user[user] + '">');
                    }
                    htmlassign += '</div></div>';
                    $('#htmlassign').html('');
                    $('#htmlassign').append(htmlassign);
                    $( 'input[type="checkbox"]' ).each(function( index ) {
                        $('input[type="checkbox"]').attr("disabled", true);
                        $('input[type="checkbox"]').prop('checked', true);
                    });
                    $( 'input[type="radio"]' ).each(function( index ) {
                        $('input[type="radio"]').attr("disabled", true);
                        $('input[type="radio"]').prop('checked', true);
                    });
                } else {
                    assignee_user_array = [];
                    $('#autocheck').val(0);
                    $('#htmlassign').html('');
                    $('#manaualAssign #assign_inputs').html('');
                    $('#auto-assign-checkbox').text('Auto Assign User');
                }
            }),
            $('#assign_user_submit').on('click', function() {
                var manual_form = $('#assign_inputs').find('input[type=text]').filter(':input:first').attr('data-checkcount');
                if (assignee_user_array.length) {
                    // Submit Form
                    $('#manaualAssign').submit();
                } else if(manual_form == 2){
                    $('#manaualAssign').submit();
                } else {
                    var msg = 'Select Users and Assignee to submit form';
                    var classname = 'danger';
                    showMessage(msg, classname);
                    return false;
                }
            })
    });
    $(document).ready(function() {
        $('input[type="checkbox"][name="new_user_id"]').change(function() {
            if ($('input[type="radio"]').is(':checked') != false) {
                if ($('input[type="radio"]').is(":checked")) {
                    var us_radioname = $('input[type="radio"]:checked').attr('data-username');
                    var usid = $('input[type="radio"]:checked').attr('data-oid');
                    var ass_usid = $(this).attr('data-nid');
                    var ass_checkname = $(this).attr('data-checkname');
                    var common_id = usid+'_'+us_radioname;
                    var checkcount;
                    // if ($.inArray(usid,assignee_user_array) >  -1){
                    //     assignee_user_array[usid].push(ass_usid);
                    //     console.log("If==="+assignee_user_array[usid]);
                    // }else{
                    //     assignee_user_array.push(usid);
                    //     console.log("else==="+assignee_user_array);
                    //     assignee_user_array[usid]=[];
                    // }
                    if($('.'+common_id).length == 0){
                        $('#manaualAssign #assign_inputs').append('<input type="text" class="'+common_id+'" name="'+common_id+'[]" value="' + ass_usid + '" data-uname="'+ass_checkname+'" data-checkcount="1">');
                        htmlassign_one += '<ul class="active"><li><div class="member-details"><h5>' + common_id + '</h5></div><ul>';
                    }else{
                        var old_val = $('.'+common_id).val();
                        var old_name = $('.'+common_id).attr('data-uname');
                        checkcount = $('.'+common_id).attr('data-checkcount');
                        if(checkcount != 2){
                            $('.'+common_id).remove();;
                            $('#manaualAssign #assign_inputs').append('<input type="text" class="'+common_id+'" name="'+common_id+'[]" value="' +old_val+','+ ass_usid + '" data-checkcount="2">');
                            htmlassign_one += '<li><div class="member-details"><h5>' + old_name + '</h5></div></li>';
                            htmlassign_one += '<li><div class="member-details"><h5>' + ass_checkname + '</h5></div></li>';
                            htmlassign_one += '</ul></li></ul>';
                            $('#htmlassign').html(htmlassign_one);
                        }else{
                            var msg = 'You Can select only 2 ID`s per User';
                            var classname = 'danger';
                            showMessage(msg, classname);
                            $(this).prop('checked', false);
                            return false;                            
                        }
                    }
                    $(this).attr("disabled", true);
                } else {
                    var msg = 'Select One User to select Assignee';
                    var classname = 'danger';
                    showMessage(msg, classname);
                    return false;
                }
            } else {
                var msg = 'Select One User to select Assignee';
                var classname = 'danger';
                showMessage(msg, classname);
                $(this).prop('checked', false);
                return false;
            }
        })
    });
    function showMessage(msg, classname) {
        $('#message_div').html('<button type="button" class="btn btn-outline-' + classname + ' btn-fw" style="text-align: left;width:auto">' + msg + '</button>');
        setTimeout(function() {
            $('#message_div').html('');
        }, 3000);
    }
</script>
@endsection

@section('page-style')
@endsection