/*=========================================================================================
    File Name: form-repeater.js
    Description: form repeater page specific js
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy HTML Admin Template
    Version: 1.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(function () {
  'use strict';

  // form repeater jquery
  var i = 0;
  $('.invoice-repeater, .repeater-default').repeater({
    show: function () {
      i++;
      var current = this;
      var company = current.getElementsByClassName('company_id');
      if(company.length > 0){
        company[0].setAttribute('data-id', i);
      }

      var carrier = current.getElementsByClassName('column_master');
      if(carrier.length > 0){
        carrier[0].classList.add("column_master"+i);
        carrier[0].setAttribute("id", "column_master"+i);
      }

      // var mandatory_column = current.getElementsByClassName('mandatory_column');
      // if(mandatory_column.length > 0){
      //   console.log(mandatory_column);
      //   mandatory_column[0].setAttribute("id", "carrier_ids"+i);
      // }
      $(this).slideDown();
      // Feather Icons
      if (feather) {
        feather.replace({ width: 14, height: 14 });
      }
    },
    hide: function (deleteElement) {
      if( $(".div-repeat").length == 1 ){
          bootbox.alert({
              title: "Delete",
              message: "One row should be present. You can not delete this row",
          })
      }
      else{
        bootbox.confirm("Are you sure you want to delete this element?", function(result){
          if(result){
            $(this).slideUp(deleteElement);
          }
        });
      }
    },
     isFirstItemUndeletable: false
  });
});
