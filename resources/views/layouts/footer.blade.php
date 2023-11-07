<a href="javascript:void()" class="floating-btn" data-toggle="modal" data-target="#modals-slide-in">Create Id<span>+</span></a>
<div class="modal fade" id="modals-slide-in" tabindex="-1" role="dialog" aria-labelledby="exampleModalSlideLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalSlideLabel">Please Wait</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="d-flex align-items-start">
          <div>
            <p class="msg-date text-black mb-0">
              Are you sure want to create Id ?
            <div id="timer">1:00</div>
            </p>
          </div>
        </div>
        <br>
        <div class="d-flex flex-column-reverse flex-md-row gap-20 justify-content-end">
          <form id="createIdForm" action="{{ route('normal_user.create_id') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
            <button type="submit" id="createButton" class="btn btn-primary mb-2">Create</button>
            <button type="button" class="btn btn-danger mb-2 " data-dismiss="modal">Cancel</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
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
<script src="{{asset('js/custom/feather.min.js')}}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('assets/js/main.js')}}"></script>

<script type="text/javascript">
  $(window).on('load', function() {
    if (feather) {
      feather.replace({
        width: 18,
        height: 18
      })
    }
  })
  $(document).ready(function() {
    //refresh the page at:59:55 AM daily
    // Calculate the time until 9:59:55 AM (in milliseconds)
    var now = new Date();
    var targetTime = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 9, 59, 55);
    if (targetTime <= now) {
      targetTime.setDate(targetTime.getDate() + 1); // Move to the next day
    }
    var timeUntilRefresh = targetTime - now;

    // Schedule the page refresh
    setTimeout(function() {
      location.reload();
    }, timeUntilRefresh);

    $('#createButton').on('click', function() {
      $('#createIdForm').submit();
    });
    $('.floating-btn').on('click', function() {
      startTimer()
    });
   
    $('.common-table').DataTable({
      dom: 'Bfrtip',
      buttons: [
        // 'copyHtml5',
        'excelHtml5',
        // 'csvHtml5',
        'pdfHtml5'
      ]
    });
  });

  function startTimer() {
    var timerElement = $('#timer');
    var createButton = $('#createButton');
    var modal = $('#modals-slide-in');

    timerElement.text('1:00'); // Initial time
    createButton.hide();
    // modal.modal('show');

    var timeLeft = 05; // Time in seconds
    var timerInterval = setInterval(function() {
      timeLeft--;
      var minutes = Math.floor(timeLeft / 60);
      var seconds = timeLeft % 60;
      var timeString = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
      timerElement.text(timeString);

      if (timeLeft <= 0) {
        clearInterval(timerInterval);
        // timerElement.text('Time\'s up!');
        createButton.show();
      }
    }, 1000);
  }

  startTimer(); // Call the timer function to start the countdown
  function copyText(copyText) {
    navigator.clipboard.writeText(copyText);
    console.log(copyText);
    setTooltip('Copied!');
    hideTooltip();
  }
  $('.copyBtn').tooltip({
    trigger: 'click',
    placement: 'bottom'
  });
  var clipboard = new Clipboard('.copyBtn');
  clipboard.on('success', function(e) {
    setTooltip('Copied!');
    hideTooltip();
  });

  clipboard.on('error', function(e) {
    setTooltip('Failed!');
    hideTooltip();
  });

  function setTooltip(message) {
    $('.copyBtn').tooltip('hide')
      .attr('data-original-title', message)
      .tooltip('show');
  }

  function hideTooltip() {
    setTimeout(function() {
      $('.copyBtn').tooltip('hide');
    }, 1000);
  }
</script>
</body>

</html>