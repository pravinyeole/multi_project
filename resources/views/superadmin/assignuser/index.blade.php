<!-- common_template/contentLayoutMaster -->
@extends('layouts/common_template')
<style>
.scrolldiv { max-height: 450px; overflow: auto; overflow-y: auto; ; } /*----------------genealogy-scroll----------*/ .genealogy-scroll::-webkit-scrollbar { width: 5px; height: 8px; } .genealogy-scroll::-webkit-scrollbar-track { border-radius: 10px; background-color: #e4e4e4; } .genealogy-scroll::-webkit-scrollbar-thumb { background: #212121; border-radius: 10px; transition: 0.5s; } .genealogy-scroll::-webkit-scrollbar-thumb:hover { background: #d5b14c; transition: 0.5s; } /*----------------genealogy-tree----------*/ .genealogy-body { white-space: nowrap; overflow-y: hidden; min-height: 500px; padding-top: 10px; text-align: center; } .genealogy-tree { display: inline-block; } .genealogy-tree ul { position: relative; padding-left: 0px; display: flex; justify-content: center; } .genealogy-tree li { float: left; text-align: center; list-style-type: none; position: relative; padding: 15px 35px 0px 35px; } .genealogy-tree li::before, .genealogy-tree li::after { content: ''; position: absolute; top: 0; right: 50%; border-top: 2px solid #ccc; width: 50%; height: 18px; } .genealogy-tree li::after { right: auto; left: 50%; border-left: 2px solid #ccc; } .genealogy-tree li:only-child::after, .genealogy-tree li:only-child::before { display: none; } .genealogy-tree li:only-child { padding-top: 0; } .genealogy-tree li:first-child::before, .genealogy-tree li:last-child::after { border: 0 none; } .genealogy-tree li:last-child::before { border-right: 2px solid #ccc; border-radius: 0 5px 0 0; -webkit-border-radius: 0 5px 0 0; -moz-border-radius: 0 5px 0 0; } .genealogy-tree li:first-child::after { border-radius: 5px 0 0 0; -webkit-border-radius: 5px 0 0 0; -moz-border-radius: 5px 0 0 0; } .genealogy-tree ul ul::before { content: ''; position: absolute; top: 0; left: 50%; border-left: 2px solid #ccc; width: 0; height: 20px; } .genealogy-tree li a { text-decoration: none; color: #666; font-family: arial, verdana, tahoma; font-size: 11px; display: inline-block; border-radius: 5px; -webkit-border-radius: 5px; -moz-border-radius: 5px; } .genealogy-tree li a:hover+ul li::after, .genealogy-tree li a:hover+ul li::before, .genealogy-tree li a:hover+ul::before, .genealogy-tree li a:hover+ul ul::before { border-color: #fbba00; } /*--------------memeber-card-design----------*/ .member-view-box { padding: 0px 20px; text-align: center; border-radius: 4px; position: relative; } .member-image { width: 60px; position: relative; } .member-image img { width: 60px; height: 60px; border-radius: 6px; background-color: #000; z-index: 1; }
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
                            <button type="button" class="close" data-dismiss="alert">
                            </button>
                            <strong>Invalid ID !</strong> {{ str_replace(['[',']','"',"'"],'',session('invalidId')) }}
                        </div>
                        @endif
                        @if (Session::has('validMapped'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert">
                            </button>
                            <strong>Valid ID !</strong> {{ str_replace(['[',']','"',"'"],'',session('validMapped')) }}
                        </div>
                        @endif
                        @if (Session::has('alreadyMapped'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert">
                            </button>
                            <strong>ALready Mapped !</strong> {{ str_replace(['[',']','"',"'"],'',session('alreadyMapped')) }}
                        </div>
                        @endif
                        <div class="col-sm-12 row">
                            <div class="col-sm-3 scrolldiv">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                        </tr>
                                    </thead>
                                    <tbody id="manaualAssignRadio">
                                        @foreach ($getOldUser as $key => $user)
                                        <tr>
                                            <td>
                                                <input type="radio" name="user_id" id="{{ $user->id }}_{{ $user->mobile_id }}">
                                                <label radiovalue="{{ $user->id }}_{{ $user->mobile_id }}" radioname="{{ $user->user_fname }}_{{ $user->user_lname }}" class="col-sm-4 control-label" id="manaual_radio_{{$key}}">
                                                    <center>{{ $user->mobile_id }} ({{ $user->user_fname }} {{ $user->user_lname }})</center>
                                                </label>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-5 scrolldiv" id="htmlassign">
                            </div>
                            <div class="col-sm-4 scrolldiv">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                        </tr>
                                    </thead>
                                    <tbody id="manaualAssignCheck">
                                        @php $r=0; @endphp
                                        @foreach ($getRecentlyJoinUser as $recentUser)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="user_id" value="{{ $recentUser->id }}">
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
</form>
@endsection

@section('page-script')
<script>
    var assignee_user_array = [];
    $(document).ready(function() {
        $('#auto-assign-checkbox').on('click', function() {
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
                    assignee_user[user] = [];
                    if (i == 1) {
                        i++;
                    } else {
                        fr = fr + 1;
                        to = fr + 2;
                    }
                    htmlassign += '<ul class="active"><li><div class="member-details"><h5>' + user_name + '</h5></div><ul>';
                    for (var k = fr; fr < to;) {
                        var assignee = $('#manuel_check_' + fr).attr('checkvalue');
                        if(assignee != undefined){
                            var assignee_name = $('#manuel_check_' + fr).attr('checkname');
                            htmlassign += '<li><div class="member-details"><h5>' + assignee_name + '</h5></div></li>';
                            assignee_user[user].push(assignee);
                            fr++;

                        }
                    }
                    htmlassign += '</ul></li></ul>';
                    // break;
                    j++;
                    assignee_user_array.push(assignee_user);
                    $('#manaualAssign').append('<input type="text" name="'+user+'[]" value="'+assignee_user[user]+'">');
                }
                htmlassign += '</div></div>';
                $('#htmlassign').append(htmlassign);
            } else {
                assignee_user_array = [];
                $('#autocheck').val(0);
                $('#htmlassign').html('');
                $('#auto-assign-checkbox').text('Auto Assign User');
            }
        }),
        $('#assign_user_submit').on('click', function() {
            if (assignee_user_array.length) {
                // Submit Form
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
        $('input[type="checkbox"]').change(function() {
            if ($('input[type="radio"]').is(':checked') != false) {
                alert($('input[type="radio"]').is(':checked'));
                if ($('input[type="radio"]').is(":checked")) {
                    var returnVal = confirm("Are you sure?");
                    $(this).attr("checked", returnVal);
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
                return false;
            }
        })
    });

    function showMessage(msg, classname) {
        $('#message_div').html('<button type="button" class="btn btn-outline-'+classname+' btn-fw" style="text-align: left;width:auto">' + msg + '</button>');
        setTimeout(function() {
            $('#message_div').html('');
        }, 3000);
    }
</script>
@endsection

@section('page-style')
@endsection