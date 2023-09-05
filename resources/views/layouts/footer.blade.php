  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <!-- <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div> -->
    <div class="credits">
    </div>
  </footer><!-- End Footer -->
</div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  @include('panels/scripts')
  <script src="{{ asset('js/template.js')}}"></script>
  <script src="{{ asset('js/dashboard.js')}}"></script>
  <script src="{{ asset('js/off-canvas.js')}}"></script>
  
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  
  <script src="{{ asset('vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{ asset('js/jquery.cookie.js')}}"></script>
  <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{ asset('assets/vendor/chart.js/chart.umd.js')}}"></script>
  <script src="{{ asset('assets/vendor/echarts/echarts.min.js')}}"></script>
  <script src="{{ asset('assets/vendor/quill/quill.min.js')}}"></script>
  <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js')}}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js')}}"></script>
  
  <!-- Template Main JS File -->
  <script src="{{ asset('assets/js/main.js')}}"></script>

<script type="text/javascript">
  $(window).on('load', function() {
    if (feather) {
      feather.replace({
        width: 14,
        height: 14
      })
    }
  })
  $(document).ready(function() {
    $('.common-table').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            // 'copyHtml5',
            'excelHtml5',
            // 'csvHtml5',
            'pdfHtml5'
        ]
    } );
} );
function copyText(copyText) {
        navigator.clipboard.writeText(copyText);
        console.log(copyText);
        setTooltip('Copied!');
        hideTooltip();
    }
    $('#copyBtn').tooltip({
        trigger: 'click',
        placement: 'bottom'
    });
    var clipboard = new Clipboard('#copyBtn');
    clipboard.on('success', function(e) {
        setTooltip('Copied!');
        hideTooltip();
    });

    clipboard.on('error', function(e) {
        setTooltip('Failed!');
        hideTooltip();
    });

    function setTooltip(message) {
        $('#copyBtn').tooltip('hide')
            .attr('data-original-title', message)
            .tooltip('show');
    }

    function hideTooltip() {
        setTimeout(function() {
            $('#copyBtn').tooltip('hide');
        }, 1000);
    }
</script>
</body>

</html>