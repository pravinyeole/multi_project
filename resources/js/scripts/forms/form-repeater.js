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
  $('.invoice-repeater, .repeater-default').repeater({
    show: function () {
      $(this).slideDown();
      // Feather Icons
      if (feather) {
        feather.replace({ width: 14, height: 14 });
      }
      
    },
    hide: function (deleteElement) {
      if (confirm('Are you sure you want to delete this element?')) {
        $(this).slideUp(deleteElement);
      }
    }
  });
});

// function enableSelectTwo() {
//   var select = $('.select2');
//   select.each(function () {
//     var $this = $(this);
//     $this.wrap('<div class="position-relative"></div>');
//     $this.select2({
//       // the following code is used to disable x-scrollbar when click in select input and
//       // take 100% width in responsive also
//       dropdownAutoWidth: true,
//       width: '100%',
//       dropdownParent: $this.parent()
//     });
//   });
// }
