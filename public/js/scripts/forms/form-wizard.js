/*=========================================================================================
    File Name: wizard-steps.js
    Description: wizard steps page specific js
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(function () {
  'use strict';

  var bsStepper = document.querySelectorAll('.bs-stepper'),
    select = $('.select2'),
    horizontalWizard = document.querySelector('.horizontal-wizard-example'),
    verticalWizard = document.querySelector('.vertical-wizard-example'),
    modernWizard = document.querySelector('.modern-wizard-example'),
    modernVerticalWizard = document.querySelector('.modern-vertical-wizard-example');

  // Adds crossed class
  if (typeof bsStepper !== undefined && bsStepper !== null) {
    for (var el = 0; el < bsStepper.length; ++el) {
      bsStepper[el].addEventListener('show.bs-stepper', function (event) {
        var index = event.detail.indexStep;
        var numberOfSteps = $(event.target).find('.step').length - 1;
        var line = $(event.target).find('.step');

        // The first for loop is for increasing the steps,
        // the second is for turning them off when going back
        // and the third with the if statement because the last line
        // can't seem to turn off when I press the first item. ¯\_(ツ)_/¯

        for (var i = 0; i < index; i++) {
          line[i].classList.add('crossed');

          for (var j = index; j < numberOfSteps; j++) {
            line[j].classList.remove('crossed');
          }
        }
        if (event.detail.to == 0) {
          for (var k = index; k < numberOfSteps; k++) {
            line[k].classList.remove('crossed');
          }
          line[0].classList.remove('crossed');
        }
      });
    }
  }

  // select2
  select.each(function () {
    var $this = $(this);
    $this.wrap('<div class="position-relative"></div>');
    $this.select2({
      placeholder: 'Select value',
      dropdownParent: $this.parent()
    });
  });

  // Horizontal Wizard
  // --------------------------------------------------------------------
  if (typeof horizontalWizard !== undefined && horizontalWizard !== null) {
    var numberedStepper = new Stepper(horizontalWizard),
      $form = $(horizontalWizard).find('form');
    // $form.each(function () {
    //   var $this = $(this);
    //   $this.validate({
    //     rules: {
    //       username: {
    //         required: true
    //       },
    //       email: {
    //         required: true
    //       },
    //       password: {
    //         required: true
    //       },
    //       'confirm-password': {
    //         required: true,
    //         equalTo: '#password'
    //       },
    //       'first-name': {
    //         required: true
    //       },
    //       'last-name': {
    //         required: true
    //       },
    //       address: {
    //         required: true
    //       },
    //       landmark: {
    //         required: true
    //       },
    //       country: {
    //         required: true
    //       },
    //       language: {
    //         required: true
    //       },
    //       twitter: {
    //         required: true,
    //         url: true
    //       },
    //       facebook: {
    //         required: true,
    //         url: true
    //       },
    //       google: {
    //         required: true,
    //         url: true
    //       },
    //       linkedin: {
    //         required: true,
    //         url: true
    //       }
    //     }
    //   });
    // });

    $(horizontalWizard)
      .find('.btn-next')
      .each(function () {
        $(this).on('click', function (e) {
          var isValid = $(this).parent().siblings('form').valid();
          if (isValid) {
            var formID = $(this).attr('id');
            if(formID == 'step1Form'){
                var form1 = $(this).parent().siblings('form');
                var dataString = new FormData(document.getElementById(formID));
                var form_data   = $("#"+formID).serialize();
                dataString.append("form_data", form_data);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#loader').show();
                $.ajax({
                    url: base_url+'/teacher_paper/storeStep1',
                    dataType: 'json',  
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: dataString,                         
                    type: 'POST',
                    success: function(result){
                      
                        $('#loader').hide();
                        if(result.title == 'Success'){
                          numberedStepper.next();
                          console.log(result);
                          var barcodeUrl = base_url+'/teacher_paper/print_barcode/'+result.id;
                          var invoiceUrl = base_url+'/teacher_paper/invoice/'+result.id;
                          $('#teacher_paper_id').val(result.id);
                          $('#no_of_barcode1').attr('value', result.no_of_barcodes);
                          $('#PrintBarcodeBtn1').attr('href', barcodeUrl);
                          $('#step2Submit').attr('href', invoiceUrl);
                          $('#step2_screen').removeClass('d-none');
                          $('h3').text(result.chairmanData.faculty_name);
                          $('#department').text(result.department);
                          $('#internal_paper_setter').html(result.internalPaperSetterData.faculty_name);
                          $('#external_paper_setter').html(result.externalPaperSetterData.faculty_name);
                          $('#subject').html(result.subject);
                          $('#date').html(result.date);
                        }else{
                          toastr.error(result.msg, 'Error');
                        }
                    }
                });
            }else if(formID == 'step2Form'){
              if($(this).data('purchase') == 'Y'){
                window.location.href=base_url+'/buy-credits';
              }else{
                var token = $("input[name='_token']").val();
                var carrier_id = $(this).data('carrierid');
                $('#loader').show();
                $.ajax({
                    url: base_url+'/audit/getPlans',
                    dataType: 'json',  
                    data: {
                        '_token': token,
                        'audit_exceution_id': $(this).data('id'),
                        'carrier_id': carrier_id,
                    },                 
                    type: 'POST',
                    success: function(result){
                        $('#loader').hide();
                        if(result.title == 'Success'){
                          var easePlan = '<div class="row"><div class="col-md-6 mb-1"><b>Ease Plans</b></div><div class="col-md-6 mb-1" style="padding-left:0px"><b>Carrier Plans</b></div>';
                            for (var easeData in result.easePlans) {
                              easePlan += '<div class="form-row form-group col-md-12">';
                              easePlan += '<label class="col-md-6 col-form-label">'+result.easePlans[easeData]["plan_type"]+'<span class="text-danger">*</span></label>';
                              easePlan += '<input type="hidden" class="custom-input" name="easePlans[]" value="'+result.easePlans[easeData]["plan_type"]+'" required>';
                              easePlan += '<div class="col"><select class="form-control name_input" id=""  name="carrierPlans[]" required>';
                              easePlan += '<option value="" selected="" disabled="">Select Plan</option><option value="no_match">No Match Found</option>';
                              if(carrier_id == 1){ //guardian
                                  var dentalFlag = 0;
                                  var vtFlag = 0;
                                  for (var carrierData in result.carrierPlans){
                                    var plan_type1 = result.carrierPlans[carrierData]['plan_type'];
                                    var plan_type = plan_type1.charAt(0).toUpperCase() + plan_type1.slice(1);
                                    if(plan_type.toLowerCase() == 'dental premium'  || plan_type.toLowerCase() =='managed dental care   mdc premium' || plan_type.toLowerCase()=='managed dental care mdc premium'){
                                      if(dentalFlag == 0){
                                        easePlan += '<option value="Dental|Managed Dental Care   Mdc">Dental|Managed Dental Care Mdc</option>';
                                        dentalFlag = 1;
                                      }
                                    }
                                    else if(plan_type.toLowerCase() == 'voluntary ad&d premium' || plan_type.toLowerCase() == 'voluntary term life premium'){
                                      if(vtFlag == 0){
                                        easePlan += '<option value="Voluntary Ad&d|Voluntary Term Life">Voluntary Ad&d|Voluntary Term Life</option>';
                                        vtFlag = 1;
                                      }
                                    }else{
                                      if(plan_type.toLowerCase()!='managed dental care   mdc premium' || plan_type.toLowerCase()!='managed dental care mdc premium' || plan_type.toLowerCase()!='voluntary term life premium'){
                                        easePlan += '<option value="'+plan_type.replace('premium','')+'">'+plan_type.replace('premium','')+'</option>';
                                      }
                                    }
                                  }
                              }
                              else if(carrier_id == 2){ // Blueshield
                                var l = 0;
                                for (var carrierData in result.carrierPlans){
                                    var plan_type1 = result.carrierPlans[carrierData]['plan_type'];
                                    var plan_type = plan_type1.charAt(0).toUpperCase() + plan_type1.slice(1);
                                    // console.log(plan_type);
                                    if(plan_type == 'Silver Full PPO Savings 2100/25% OffEx IND' || plan_type == "Silver Full PPO Savings 2100/25% OffEx FAM"){
                                      if(l == 0){
                                        easePlan += '<option value="Silver Full PPO Savings 2100/25% OffEx IND|Silver Full PPO Savings 2100/25% OffEx FAM">Silver Full PPO Savings 2100/25% OffEx IND|Silver Full PPO Savings 2100/25% OffEx FAM</option>';
                                        l = 1;
                                      }
                                    }
                                    else if(plan_type == 'Silver Full PPO Savings 2000/25% OffEx IND' || plan_type == "Silver Full PPO Savings 2000/25% OffEx FAM"){
                                      if(l == 0){
                                        easePlan += '<option value="Silver Full PPO Savings 2000/25% OffEx IND|Silver Full PPO Savings 2000/25% OffEx FAM">Silver Full PPO Savings 2000/25% OffEx IND|Silver Full PPO Savings 2000/25% OffEx FAM</option>';
                                        l = 1;
                                      }
                                    }else{
                                      easePlan += '<option value="'+plan_type.replace('premium','')+'">'+plan_type.replace('premium','')+'</option>';
                                    }
                                }
                              }
                              else if(carrier_id == 4){ // anthem
                                var l = 0;
                                var vl = 0;
                                for (var carrierData in result.carrierPlans){
                                    var plan_type1 = result.carrierPlans[carrierData]['plan_type'];
                                    var plan_type = plan_type1.charAt(0).toUpperCase() + plan_type1.slice(1);
                                    if(plan_type == 'LIFE' || plan_type == "AD&D"){
                                      if(l == 0){
                                        easePlan += '<option value="LIFE|AD&D">LIFE|AD&D</option>';
                                        l = 1;
                                      }
                                    }else if(plan_type == 'SUPLIFE' || plan_type == "SUPAD&D"){
                                      if(vl == 0){
                                        easePlan += '<option value="SUPLIFE|SUPAD&D">SUPLIFE|SUPAD&D</option>';
                                        vl = 1;
                                      }
                                    }else{
                                      easePlan += '<option value="'+plan_type.replace('premium','')+'">'+plan_type.replace('premium','')+'</option>';
                                    }
                                }
                              }
                              else if(carrier_id == 5){ // UHC
                                var l = 0;
                                for (var carrierData in result.carrierPlans){
                                    var plan_type1 = result.carrierPlans[carrierData]['plan_type'];
                                    var plan_type = plan_type1.charAt(0).toUpperCase() + plan_type1.slice(1);
                                    if(plan_type == 'ADD by Flat Amount' || plan_type == "Life by Flat Amount"){
                                      if(l == 0){
                                        easePlan += '<option value="ADD by Flat Amount|Life by Flat Amount">ADD by Flat Amount|Life by Flat Amount</option>';
                                        l = 1;
                                      }
                                    }else{
                                      easePlan += '<option value="'+plan_type.replace('premium','')+'">'+plan_type.replace('premium','')+'</option>';
                                    }
                                }
                              }
                              else{
                                  for (var carrierData in result.carrierPlans){
                                    var plan_type1 = result.carrierPlans[carrierData]['plan_type'];
                                    var plan_type = plan_type1.charAt(0).toUpperCase() + plan_type1.slice(1);
                                    
                                    easePlan += '<option value="'+plan_type.replace('premium','')+'">'+plan_type.replace('premium','')+'</option>';
                                  }
                              }
                              easePlan += '</select></div></div>';
                            }
                            easePlan += '</div>';
                            $("#plan_mapping").append(easePlan);  
                            // toastr.success(result.msg, 'Success');
                            numberedStepper.next();
                        }else{
                          toastr.error(result.msg, 'Error');
                        }
                    }
                });
              }
            }else if(formID == 'step3Form'){
              var audit_exceution_id = $(this).data('id');
              var carrier_id = $(this).data('carrierid');
              var token = $("input[name='_token']").val();
              $('#loader').show();
              var dataString = new FormData(document.getElementById(formID));
              var form_data   = $("#"+formID).serializeArray();
              form_data.push({name: "audit_exceution_id", value: audit_exceution_id});
              form_data.push({name: "carrier_id", value: carrier_id});
              dataString.append("form_data", form_data);
              $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
              $.ajax({
                  url: base_url+'/audit/getNames',
                  dataType: 'json',  
                  data: $.param(form_data),              
                  type: 'POST', 
                  success: function(result){
                      $('#loader').hide();
                      // console.log(carrier_id); 
                      if(result.title == 'Success'){
                          var easeName = '<div class="row"><div class="col-md-6 mb-1"><b>Ease Names</b></div><div class="col-md-6 mb-1" style="padding-left:0px"><b>Carrier Names</b></div>';
                          var easeCount = (result.easeNames).length;
                          var selectedCount = 0;
                          if(carrier_id == 3){
                            for (var easeData in result.easeNames) {
                              var easeMemberName1 = (result.easeNames[easeData]["member_name"]).toLowerCase();
                              var easeMemberName = easeMemberName1.split(',').join(' ');
                              var easeMemberNameArray = easeMemberName.split(" ");
                              var newEM = easeMemberNameArray[0]+" "+easeMemberNameArray[1];
                              var newEM1 = easeMemberNameArray[0]+"  "+easeMemberNameArray[1];

                              easeName += '<div class="form-row form-group col-md-12">';
                              easeName += '<label class="col-md-6 col-form-label">'+result.easeNames[easeData]["member_name"]+'<span class="text-danger">*</span></label>';
                              easeName += '<input type="hidden" class="custom-input" name="ease_name[]" value="'+result.easeNames[easeData]["member_name"]+'" required></label>';
                              easeName += '<div class="col"><select class="form-control name_input1" id=""  name="carrier_name[]" required>';
                              easeName += '<option value="" selected="" disabled="">Select Name</option><option value="no_match">No Match Found</option>';
                              for (var carrierData in result.carrierNames){
                                var member_name = result.carrierNames[carrierData]['member_name'];
                                var carrierMemberName1 = (result.carrierNames[carrierData]['member_name']).toLowerCase();
                                var carrierMemberName = carrierMemberName1.split(',').join(' ');
                                var carrierMemberNameArray = carrierMemberName.split(" ");
                                var clength = carrierMemberNameArray.length;
                                var newCM = "";
                                for (let i = 0; i < clength; i++) {
                                  newCM += carrierMemberNameArray[i].trim() + " ";
                                }

                                var newCM1 = "";
                                for (let i = 0; i < clength-1; i++) {
                                  newCM1 += carrierMemberNameArray[i].trim() + " ";
                                }

                                var newCM2 = "";
                                var reverseCArray = carrierMemberNameArray.reverse();
                                for (let i = 0; i < clength; i++) {
                                  newCM2 += reverseCArray[i].trim() + " ";
                                }

                                var newCM3 = "";
                                for (let i = 1; i < clength; i++) {
                                  newCM3 += reverseCArray[i].trim() + " ";
                                }
                                if(result.easeNames[easeData]["ssn"] == result.carrierNames[carrierData]['ssn']){
                                  easeName += '<option value="'+member_name+'" selected>'+member_name+'</option>';
                                }
                                else if((easeMemberName.trim() == carrierMemberName.trim()) || (newEM.trim() == newCM.trim()) || (newEM.trim() == newCM1.trim()) || (newEM.trim() == newCM2.trim()) || (newEM.trim() == newCM3.trim()) || (newEM1.trim() == newCM3.trim()) || (newEM1.trim() == newCM2.trim())){
                                  easeName += '<option value="'+member_name+'" selected>'+member_name+'</option>';
                                }else{
                                  easeName += '<option value="'+member_name+'">'+member_name+'</option>';
                                }
                              }
                              easeName += '</select></div></div>';
                            }
                          }else if(carrier_id == 1){
                            for (var easeData in result.easeNames) {
                              var easeMemberName1 = (result.easeNames[easeData]["member_name"]).toLowerCase();
                              var easeMemberName = easeMemberName1.split(',').join(' ');
                              var easeMemberNameArray = easeMemberName.split(" ");
                              var newEM = easeMemberNameArray[0]+" "+easeMemberNameArray[1];
                              easeName += '<div class="form-row form-group col-md-12">';
                              easeName += '<label class="col-md-6 col-form-label">'+result.easeNames[easeData]["member_name"]+'<span class="text-danger">*</span></label>';
                              easeName += '<input type="hidden" class="custom-input" name="ease_name[]" value="'+result.easeNames[easeData]["member_name"]+'" required></label>';
                              easeName += '<div class="col"><select class="form-control name_input1" id=""  name="carrier_name[]" required>';
                              easeName += '<option value="" selected="" disabled="">Select Name</option><option value="no_match">No Match Found</option>';

                              for (var carrierData in result.carrierNames){
                                var member_name = result.carrierNames[carrierData]['member_name'];
                                var carrierMemberName1 = (result.carrierNames[carrierData]['member_name']).toLowerCase();
                                var carrierMemberName = carrierMemberName1.split(', ').join(' ');
                                var carrierMemberNameArray = carrierMemberName.split(" ");
                                var newCM = carrierMemberNameArray[0]+" "+carrierMemberNameArray[1];

                                if(result.easeNames[easeData]["ssn"] == result.carrierNames[carrierData]['ssn']){
                                  easeName += '<option value="'+member_name+'" selected>'+member_name+'</option>';
                                }
                                else if((easeMemberName.trim() == carrierMemberName.trim())){
                                  easeName += '<option value="'+member_name+'" selected>'+member_name+'</option>';
                                }else{
                                  easeName += '<option value="'+member_name+'">'+member_name+'</option>';
                                }
                              }
                              easeName += '</select></div></div>';
                            }
                          }
                          else if(carrier_id == 2){
                            for (var easeData in result.easeNames) {
                              var easeMemberName1 = (result.easeNames[easeData]["member_name"]).toLowerCase();
                              var easeMemberName = easeMemberName1.split(',').join(' ');
                              var easeMemberNameArray = easeMemberName.split(" ");
                              var newEM = easeMemberNameArray[0]+" "+easeMemberNameArray[1];
                              // console.log("newEM "+newEM);
                              // console.log("easeMemberName1 "+easeMemberName1);

                              easeName += '<div class="form-row form-group col-md-12">';
                              easeName += '<label class="col-md-6 col-form-label">'+result.easeNames[easeData]["member_name"]+'<span class="text-danger">*</span></label>';
                              easeName += '<input type="hidden" class="custom-input" name="ease_name[]" value="'+result.easeNames[easeData]["member_name"]+'" required></label>';
                              easeName += '<div class="col"><select class="form-control name_input1" id=""  name="carrier_name[]" required>';
                              easeName += '<option value="" selected="" disabled="">Select Name</option><option value="no_match">No Match Found</option>';

                              for (var carrierData in result.carrierNames){
                                var member_name = result.carrierNames[carrierData]['member_name'];
                                var carrierMemberName1 = (result.carrierNames[carrierData]['member_name']).toLowerCase();
                                var carrierMemberName = carrierMemberName1.split(', ').join(' ');
                                var carrierMemberNameArray = carrierMemberName.split(" ");
                                var newCM = carrierMemberNameArray[0]+" "+carrierMemberNameArray[1];
                                // console.log("newCM "+newCM);
                                // console.log("carrierMemberNameArray "+carrierMemberNameArray);
                                if(result.easeNames[easeData]["ssn"] == result.carrierNames[carrierData]['ssn']){
                                  easeName += '<option value="'+member_name+'" selected>'+member_name+'</option>';
                                }
                                else if((easeMemberName.trim() == carrierMemberName.trim())){
                                  easeName += '<option value="'+member_name+'" selected>'+member_name+'</option>';
                                }else{
                                  easeName += '<option value="'+member_name+'">'+member_name+'</option>';
                                }
                              }
                              easeName += '</select></div></div>';
                            }
                          }
                          else{
                            for (var easeData in result.easeNames) {
                              var easeMemberName1 = (result.easeNames[easeData]["member_name"]).toLowerCase();
                              var easeMemberName = easeMemberName1.split(',').join(' ');
                              var easeMemberNameArray = easeMemberName.split(" ");
                              var newEM = easeMemberNameArray[0]+" "+easeMemberNameArray[1];
                              easeName += '<div class="form-row form-group col-md-12">';
                              easeName += '<label class="col-md-6 col-form-label">'+result.easeNames[easeData]["member_name"]+'<span class="text-danger">*</span></label>';
                              easeName += '<input type="hidden" class="custom-input" name="ease_name[]" value="'+result.easeNames[easeData]["member_name"]+'" required></label>';
                              easeName += '<div class="col"><select class="form-control name_input1" id=""  name="carrier_name[]" required>';
                              easeName += '<option value="" selected="" disabled="">Select Name</option><option value="no_match">No Match Found</option>';
                              // console.log("ssn "+result.easeNames[easeData]["ssn"]);
                              // console.log("easeMemberName "+easeMemberName);
                              // console.log("newEM "+newEM);

                              for (var carrierData in result.carrierNames){
                                var member_name = result.carrierNames[carrierData]['member_name'];
                                var carrierMemberName1 = (result.carrierNames[carrierData]['member_name']).toLowerCase();
                                var carrierMemberName = carrierMemberName1.split(', ').join(' ');
                                var carrierMemberNameArray = carrierMemberName.split(" ");
                                var newCM = carrierMemberNameArray[0]+" "+carrierMemberNameArray[1];
                                // console.log("ssn "+result.carrierNames[carrierData]["ssn"]);
                                // console.log("carrierMemberName "+carrierMemberName);
                                // console.log("newCM "+newCM);
                                if(result.easeNames[easeData]["ssn"] == result.carrierNames[carrierData]['ssn']){
                                  easeName += '<option value="'+member_name+'" selected>'+member_name+'</option>';
                                  // selectedCount = selectedCount+1;
                                }
                                else if((easeMemberName.trim() == carrierMemberName.trim()) || (newEM.trim() == newCM.trim())){
                                  easeName += '<option value="'+member_name+'" selected>'+member_name+'</option>';
                                  // console.log("in else");
                                  // selectedCount = selectedCount+1;
                                }else{
                                  easeName += '<option value="'+member_name+'">'+member_name+'</option>';
                                }
                              }
                              easeName += '</select></div></div>';
                            }
                          }
                          easeName += '</div>';
                          $("#name_mapping").append(easeName);  
                          numberedStepper.next();

                          if($("#step4Form").valid()){
                          }else{ // if form is not valid then auto submit form for highlihting first required value
                            $("#step4Form").submit();
                          }
                          
                      }else{
                        toastr.error(result.msg, 'Error');
                      }
                  }
              });
            }
          } else {
            e.preventDefault();
          }
        });
      });

    $(horizontalWizard)
      .find('.btn-prev')
      .on('click', function () {
        numberedStepper.previous();
      });

    $(horizontalWizard)
      .find('.btn-submit')
      .on('click', function () {
        var isValid = $(this).parent().siblings('form').valid();
        if (isValid) {
          var formID = $(this).attr('id');
          var audit_exceution_id = $(this).data('id');
          var carrier_id = $(this).data('carrierid');
          var team_id = $(this).data('teamid');
          var credits = $(this).data('credits');
          var token = $("input[name='_token']").val();
          $('#loader').show();
          var dataString = new FormData(document.getElementById(formID));
          var form_data   = $("#"+formID).serializeArray();
          form_data.push({name: "audit_exceution_id", value: audit_exceution_id});
          form_data.push({name: "carrier_id", value: carrier_id});
          form_data.push({name: "team_id", value: team_id});
          form_data.push({name: "credits", value: credits});
          dataString.append("form_data", form_data);
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $.ajax({
              url: base_url+'/audit/save1',
              dataType: 'json',  
              data: $.param(form_data),              
              type: 'POST',
              success: function(result){
                  $('#loader').hide();
                  // console.log(result); 
                  if(result.title == 'Success'){                          
                      toastr.success(result.msg, 'Success');
                      window.location.href=base_url+'/audit';
                  }else{
                    toastr.error(result.msg, 'Error');
                  }
              }
          });
        }
      });
  }

  // Vertical Wizard
  // --------------------------------------------------------------------
  if (typeof verticalWizard !== undefined && verticalWizard !== null) {
    var verticalStepper = new Stepper(verticalWizard, {
      linear: false
    });
    $(verticalWizard)
      .find('.btn-next')
      .on('click', function () {
        verticalStepper.next();
      });
    $(verticalWizard)
      .find('.btn-prev')
      .on('click', function () {
        verticalStepper.previous();
      });

    $(verticalWizard)
      .find('.btn-submit')
      .on('click', function () {
        alert('Submitted..!!');
      });
  }

  // Modern Wizard
  // --------------------------------------------------------------------
  if (typeof modernWizard !== undefined && modernWizard !== null) {
    var modernStepper = new Stepper(modernWizard, {
      linear: false
    });
    $(modernWizard)
      .find('.btn-next')
      .on('click', function () {
        modernStepper.next();
      });
    $(modernWizard)
      .find('.btn-prev')
      .on('click', function () {
        modernStepper.previous();
      });

    $(modernWizard)
      .find('.btn-submit')
      .on('click', function () {
        alert('Submitted..!!');
      });
  }

  // Modern Vertical Wizard
  // --------------------------------------------------------------------
  if (typeof modernVerticalWizard !== undefined && modernVerticalWizard !== null) {
    var modernVerticalStepper = new Stepper(modernVerticalWizard, {
      linear: false
    });
    $(modernVerticalWizard)
      .find('.btn-next')
      .on('click', function () {
        modernVerticalStepper.next();
      });
    $(modernVerticalWizard)
      .find('.btn-prev')
      .on('click', function () {
        modernVerticalStepper.previous();
      });

    $(modernVerticalWizard)
      .find('.btn-submit')
      .on('click', function () {
        alert('Submitted..!!');
      });
  }
});
