/*=========================================================================================
    File Name: credit-repeater.js
    Description: form repeater page specific js
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy HTML Admin Template
    Version: 1.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

// $(function () {
//     'use strict';
  
//     // form repeater jquery
//     var i = 0;
//     $('.invoice-repeater, .repeater-default').repeater({
//         show: function () {
//             i++;
//             var current = this;
//             var company = current.getElementsByClassName('company_id');
//             if(company.length > 0){
//                 company[0].setAttribute('data-id', i);
//             }
    
//             var carrier = current.getElementsByClassName('column_master');
//             if(carrier.length > 0){
//                 carrier[0].classList.add("column_master"+i);
//                 carrier[0].setAttribute("id", "column_master"+i);
//             }
    
//             $(this).slideDown();
//             // Feather Icons
//             if (feather) {
//                 feather.replace({ width: 14, height: 14 });
//             }
//         },
//         hide: function (deleteElement) {
//             if( $(".div-repeat").length == 1 ){
//                 bootbox.alert({
//                     title: "Delete",
//                     message: "One row should be present. You can not delete this row",
//                 })
//             }else{
//                 bootbox.confirm("Are you sure you want to delete this element?", function(result){
//                     if(result){
//                         $(this).slideUp(deleteElement);
//                     }
//                 });
//             }
//         },
//         isFirstItemUndeletable: true
//     });
// });

jQuery(document).ready(function(){
    var ArrTxt  = parseInt(jQuery('#creditCount').val());
    ArrTxt      = ArrTxt == 0 ? 1 : ArrTxt;
    jQuery('.addRepeat').click(function(){
        // addRepeat
        let x = Math.floor((Math.random() * 1000) + 1);
        jQuery('#dataRepeat #row_').attr('id', 'row_'+x);

        jQuery('#dataRepeat .minClass').attr('name', 'min['+ArrTxt+']');
        jQuery('#dataRepeat .maxClass').attr('name', 'max['+ArrTxt+']');
        jQuery('#dataRepeat .priceClass').attr('name', 'price['+ArrTxt+']');
        jQuery('#dataRepeat .discountClass').attr('name', 'discount['+ArrTxt+']');
        jQuery('#dataRepeat .pointIDClass').attr('name', 'pointID['+ArrTxt+']');        

        var html = jQuery('#dataRepeat').html();
        jQuery('#repeater').append(html).fadeIn('slow');

        jQuery('#dataRepeat #row_'+x).attr('id', 'row_');
        jQuery('#dataRepeat .minClass').attr('name', 'min[]');
        jQuery('#dataRepeat .maxClass').attr('name', 'max[]');
        jQuery('#dataRepeat .priceClass').attr('name', 'price[]');
        jQuery('#dataRepeat .discountClass').attr('name', 'discount[]');
        jQuery('#dataRepeat .pointIDClass').attr('name', 'pointID[]');        
        setMinimumValue();
        ArrTxt++;
    });

    jQuery(document).on('change', '.minClass, .maxClass', setMinimumValue);
    
    jQuery(document).on('click', '.deleteRepeat', function(element){
        const parentID = jQuery(this).parent().parent().parent().attr('id');

        bootbox.confirm("Are you sure you want to delete this element?", function(result){
            if(result){
                jQuery('#'+parentID).remove();
                setMinimumValue();
            }
        });        
    });

    jQuery.validator.addMethod("require_field", function(value, element) {
        if(value.trim() == ''){
            return false;
        }
        return true;
    }, "This field is required.");

    jQuery.validator.addMethod("specialChars", function( value, element ) {
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var key = value;

        if (!regex.test(key)) {
            return false;
        }
        return true;
    }, "Please use only alphanumeric and alphabetic characters.");

    jQuery('#logo').change(function(){
        const file = this.files[0];
        if (file){
            let reader = new FileReader();
            reader.onload = function(event){
                // console.log(event.target.result);
                // $('#imgPreview').attr('src', event.target.result);
                jQuery('#check_upload_orig').val(event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    jQuery('#jquery-val-form').validate({
        rules: {
            name                : {
                required        : true,
                require_field   : true,
            },
            description         : {
                required        : true,
                require_field   : true,
                maxlength       : 500
            },
            logo                : {
                required        : {
                    depends     : function(element) {
                        return $("#check_upload_orig").is(":blank");
                    }
                },
            },
        },
        submitHandler: function(form){
            form.submit();
        }
    });

});

function setMinimumValue(){
    var present = document.getElementById('jquery-val-form');
    var select  = present.querySelectorAll('.minClass');
    // console.log(select);
    var minValue = 0;
    var maxValue = 0;
    for (let index = 0; index < select.length; index++) {
        const element   = select[index];      
        const parentID  = element.parentElement.parentElement.parentElement.getAttribute('id');
        
        const present   = document.getElementById(parentID);
        if(index == 0){
            let min         = present.getElementsByClassName('minClass')[0].value;
            let max         = present.getElementsByClassName('maxClass')[0].value;
            // console.log('Min: '+min+ ' Max: '+max );
        
            minValue = min;
            maxValue = max;
        }else{
            let check = present.getElementsByClassName('maxClass')[0].value;
            present.getElementsByClassName('minClass')[0].value = parseInt(maxValue) + 1;

            if(parseInt(check) != '' && parseInt(check) > parseInt(maxValue) + 2){
                if(check == '∞'){
                    present.getElementsByClassName('maxClass')[0].value = '∞';
                }else{
                    present.getElementsByClassName('maxClass')[0].value = parseInt(check);
                }
            }else{
                if(check == '∞'){
                    present.getElementsByClassName('maxClass')[0].value = '∞';
                }else{
                    present.getElementsByClassName('maxClass')[0].value = parseInt(maxValue) + 2;
                }
            }

            let min         = present.getElementsByClassName('minClass')[0].value;
            let max         = present.getElementsByClassName('maxClass')[0].value;
            // console.log('Min: '+min+ ' Max: '+max );

            minValue = min;
            maxValue = max;
        }
        
    }
}