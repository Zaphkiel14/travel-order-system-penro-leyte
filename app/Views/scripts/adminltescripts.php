<script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/js/tempus-dominus.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.date-picker').forEach(function(el) {
      new tempusDominus.TempusDominus(el, {
        localization: {
          format: 'yyyy-MM-dd'
        },
        display: {
          components: {
            calendar: true,
            date: true,
            month: true,
            year: true,
            clock: false,
            hours: false,
            minutes: false,
            seconds: false
          }
        }
      });
    });
  });
</script>

<!-- Page specific script -->
<!-- load jQuery and DataTables core before any DataTable initialization -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?= base_url('assets/dist/plugins/datatables/jquery.dataTables.min.js') ?>"></script>



<script src="<?= base_url('assets/dist/js/adminlte.js') ?>"></script>

<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="<?= base_url('assets/dist/plugins/jszip/jszip.min.js') ?>"></script>
<script src="<?= base_url('assets/dist/plugins/pdfmake/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('assets/dist/plugins/pdfmake/vfs_fonts.js') ?>"></script>

<script src="<?= base_url('assets/dist/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>

<script src="<?= base_url('assets/dist/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>

<script src="<?= base_url('assets/dist/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>

<script src="<?= base_url('assets/dist/plugins/plugins/daterangepicker/daterangepicker.js') ?>"></script>

<script src="<?= base_url('assets/dist/plugins/moment/moment.min.js') ?>"></script>

<script src="<?= base_url('assets/dist/plugins/fullcalendar/main.js') ?>"></script>



<?php if ($toast = session()->getFlashdata('toast')):
  $iconMap = [
    'primary' => 'bi-info-circle',
    'success' => 'bi-check-circle',
    'info'    => 'bi-info-circle',
    'warning' => 'bi-exclamation-triangle',
    'danger'  => 'bi-x-circle'
  ];
  $icon = $iconMap[$toast['type']] ?? 'bi-bell-fill';
?>
  <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="autoToast" class="toast fade shadow border-0" role="alert">

      <div class="toast-header bg-<?= esc($toast['type']) ?> text-white border-0 d-flex align-items-center">

        <i class="bi <?= esc($icon) ?> me-2 fs-5"></i>

        <strong class="me-auto">TRAVEL ORDER SYSTEM <br> PENRO LEYTE</strong>

        <small class="text-white-50 toast-time" data-start="<?= time() ?>"></small>

        <button type="button"
          class="btn-close btn-close-white ms-2"
          data-bs-dismiss="toast"></button>
      </div>

      <div class="toast-body bg-<?= esc($toast['type']) ?>-subtle">
        <?= esc($toast['message']) ?>
      </div>

    </div>
  </div>
<?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
     // For all edit category modals
  $(document).on('shown.bs.modal', '.modal', function() {
    $(this).find('.select2').select2({
      dropdownParent: $(this)
    });
  });

  // Global initialize: all .select2 on the page (including non-modal)
  $(document).ready(function() {
    $('.select2').select2({
      theme: 'bootstrap-5'
    });
  });
</script>


<!--begin::Bootstrap Toasts-->
<!-- <script>
  const toastTriggerList = document.querySelectorAll('[data-bs-toggle="toast"]');
  toastTriggerList.forEach((btn) => {
    btn.addEventListener('click', (event) => {
      event.preventDefault();
      const toastEle = document.getElementById(btn.getAttribute('data-bs-target'));
      const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastEle);
      toastBootstrap.show();
    });
  });
</script> -->
<!--end::Bootstrap Toasts-->
<!-- <script>
  window.addEventListener('DOMContentLoaded', () => {
    var toastEl = document.getElementById('toastPrimary');
    if (toastEl) {
      var toast = new bootstrap.Toast(toastEl);
      toast.show();
    }
  });
</script> -->

<script>
  document.addEventListener("DOMContentLoaded", function() {

    const toastEl = document.getElementById("autoToast");
    const timeEl = document.querySelector(".toast-time");

    if (!toastEl) return;

    const toast = new bootstrap.Toast(toastEl, {
      autohide: false
    });

    toast.show();

    // ---- TIME COUNTER ----
    const startTime = parseInt(timeEl.dataset.start) * 1000;

    function updateTime() {
      const now = Date.now();
      const diff = Math.floor((now - startTime) / 1000);

      if (diff < 5) {
        timeEl.textContent = "Just now";
      } else if (diff < 10) {
        timeEl.textContent = "5 secs ago";
      } else if (diff < 30) {
        timeEl.textContent = "10 secs ago";
      } else if (diff < 60) {
        timeEl.textContent = "30 secs ago";
      } else {
        const mins = Math.floor(diff / 60);
        timeEl.textContent = mins + (mins === 1 ? " min ago" : " mins ago");
      }
    }

    updateTime();
    const timerInterval = setInterval(updateTime, 1000);

    // ---- AUTO CLOSE AFTER 10 MINUTES ----
    setTimeout(() => {
      toast.hide();
      clearInterval(timerInterval);
    }, 600000); // 10 minutes

  });
</script>

<script>
  // Example starter JavaScript for disabling form submissions if there are invalid fields
  (() => {
    'use strict';

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation');

    // Loop over them and prevent submission
    Array.from(forms).forEach((form) => {
      form.addEventListener(
        'submit',
        (event) => {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }

          form.classList.add('was-validated');
        },
        false,
      );
    });
  })();
</script>


<script>
  $(function() {

  
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', {
      'placeholder': 'dd/mm/yyyy'
    })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', {
      'placeholder': 'mm/dd/yyyy'
    })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date picker
    $('#reservationdate').datetimepicker({
      format: 'L'
    });

    //Date and time picker
    $('#reservationdatetime').datetimepicker({
      icons: {
        time: 'far fa-clock'
      }
    });

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
    //Date range as a button
    $('#daterange-btn').daterangepicker({
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment()
      },
      function(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })

    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    })

    $("input[data-bootstrap-switch]").each(function() {
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })

  })
  // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function() {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  })

  // DropzoneJS Demo Code Start
  Dropzone.autoDiscover = false

  // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
  var previewNode = document.querySelector("#template")
  previewNode.id = ""
  var previewTemplate = previewNode.parentNode.innerHTML
  previewNode.parentNode.removeChild(previewNode)

  var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
    url: "/target-url", // Set the url
    thumbnailWidth: 80,
    thumbnailHeight: 80,
    parallelUploads: 20,
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
  })

  myDropzone.on("addedfile", function(file) {
    // Hookup the start button
    file.previewElement.querySelector(".start").onclick = function() {
      myDropzone.enqueueFile(file)
    }
  })

  // Update the total progress bar
  myDropzone.on("totaluploadprogress", function(progress) {
    document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
  })

  myDropzone.on("sending", function(file) {
    // Show the total progress bar when upload starts
    document.querySelector("#total-progress").style.opacity = "1"
    // And disable the start button
    file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
  })

  // Hide the total progress bar when nothing's uploading anymore
  myDropzone.on("queuecomplete", function(progress) {
    document.querySelector("#total-progress").style.opacity = "0"
  })

  // Setup the buttons for all transfers
  // The "add files" button doesn't need to be setup because the config
  // `clickable` has already been specified.
  document.querySelector("#actions .start").onclick = function() {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
  }
  document.querySelector("#actions .cancel").onclick = function() {
    myDropzone.removeAllFiles(true)
  }
  // DropzoneJS Demo Code End
</script>

<!-- <script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script> -->
<!-- WORKING DATATABLE INITIALIZATION -->
<!-- <script>
  $(function() {
    $('.datatable-standard').each(function() {
      var $table = $(this);
      var lastColumnWidth = $table.data('last-column-width') || '80';
      var ajaxUrl = $table.data('url');
      var dt;
      try {
        var config = {
          responsive: true,
          lengthChange: false,
          autoWidth: false,
          info: true,
          paging: true,
          searching: true,
          dom: 'Bfrtip',
          buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
          columnDefs: [{
              orderable: false,
              targets: -1
            },
            {
              width: lastColumnWidth,
              targets: -1
            }
          ]
        };
        if (ajaxUrl) {
          config.processing = true;
          config.serverSide = true;
          config.ajax = {
            url: ajaxUrl,
            type: "POST"
          };
        }
        dt = $table.DataTable(config);
      } catch (err) {
        console.warn('DataTable init failed', err);
        dt = null;
      }
      // Move buttons beside search
      if (dt && dt.buttons) {
        var tableId = $table.attr('id');
        var $filter = tableId ? $('#' + tableId + '_filter') : $();
        if ($filter.length) {
          $filter.css({
            display: 'flex',
            'justify-content': 'space-between',
            'align-items': 'center',
            gap: '8px'
          });
          dt.buttons().container().prependTo($filter);
        }
      }
    });
  });
</script> -->
<!-- WORKING DATATABLE INITIALIZATION -->


<!-- EXPERIMENTAL DATATABLE INITIALIZATION -->
<script>
$(function () {

  /**
   * DataTable Global Initializer
   * ─────────────────────────────────────────────────────────────────────
   * Usage: add class="datatable-standard" to any <table> and configure
   * behaviour via data-* attributes or an inline JSON config block.
   *
   * BASIC ATTRIBUTES (on the <table> element)
   * ─────────────────────────────────────────
   * data-url="..."               → enables server-side processing (AJAX POST)
   * data-method="GET|POST"       → AJAX method (default: POST)
   * data-last-column-width="120" → px width for last column (default: 100)
   * data-page-length="25"        → rows per page (default: 25)
   * data-order='[[0,"asc"]]'     → initial sort (JSON array)
   * data-searching="false"       → disable search box
   * data-buttons="false"         → disable export buttons
   * data-responsive="true"       → enable responsive plugin (default: true)
   * data-language="..."          → custom language object (JSON)
   *
   * COLUMN DEFINITIONS (on <th> elements)
   * ──────────────────────────────────────
   * data-name="column_name"      → maps to server-side column name
   * data-orderable="false"       → disable sorting for this column
   * data-searchable="false"      → exclude column from search
   * data-visible="false"         → hide column (still sent server-side)
   * data-class="text-center"     → adds class to all <td> in this column
   * data-width="80"              → column width in px
   * data-type="date"             → column type (date, num, string, etc.)
   *
   * SERVER-SIDE RESPONSE FORMAT (your PHP controller must return):
   * ──────────────────────────────────────────────────────────────
   * {
   *   "draw":            <int>,
   *   "recordsTotal":    <int>,
   *   "recordsFiltered": <int>,
   *   "data":            [ [...], [...] ]   ← array of row arrays
   * }
   *
   * EXAMPLES
   * ────────
   * <table class="datatable-standard"
   *        data-url=" route_to('travel-orders.data') "
   *        data-page-length="10"
   *        data-order='[[3,"desc"]]'>
   *
   * <th data-name="travel_order_number">TO #</th>
   * <th data-name="destination">Destination</th>
   * <th data-name="status" data-orderable="false">Status</th>
   * <th data-orderable="false" data-searchable="false">Actions</th>
   */

  $('.datatable-standard').each(function () {
    var $table = $(this);

    // ── Read table-level config from data-* attributes ──────────────────
    var ajaxUrl          = $table.data('url')               || null;
    var ajaxMethod       = ($table.data('method')           || 'POST').toUpperCase();
    var lastColWidth     = $table.data('last-column-width') || 100;
    var pageLength       = parseInt($table.data('page-length')) || 25;
    var initialOrder     = $table.data('order')             || [[0, 'asc']];
    var enableSearching  = $table.data('searching')  !== false;
    var enableButtons    = $table.data('buttons')    !== false;
    var enableResponsive = $table.data('responsive') !== false;
    var extraParams      = $table.data('params')            || {};   // static extra POST params

    // ── Build columnDefs from <th data-*> attributes ─────────────────────
    var columns     = [];
    var columnDefs  = [];

    $table.find('thead th').each(function (i) {
      var $th          = $(this);
      var colName      = $th.data('name')       || null;
      var orderable    = $th.data('orderable')  !== false;
      var searchable   = $th.data('searchable') !== false;
      var visible      = $th.data('visible')    !== false;
      var colClass     = $th.data('class')      || null;
      var colWidth     = $th.data('width')      || null;
      var colType      = $th.data('type')       || null;

      // Column definition for the columns array (used with server-side)
      var colDef = {
        data:       colName,          // null = positional (client-side friendly)
        name:       colName,
        orderable:  orderable,
        searchable: searchable,
        visible:    visible
      };
      if (colType)  colDef.type  = colType;
      if (colWidth) colDef.width = colWidth + 'px';
      if (colClass) colDef.className = colClass;

      columns.push(colDef);

      // Build columnDefs array for non-orderable / non-searchable columns
      if (!orderable)  columnDefs.push({ orderable:  false, targets: i });
      if (!searchable) columnDefs.push({ searchable: false, targets: i });
      if (!visible)    columnDefs.push({ visible:    false, targets: i });
      if (colClass)    columnDefs.push({ className:  colClass, targets: i });
      if (colWidth)    columnDefs.push({ width:      colWidth + 'px', targets: i });
    });

    // Force last column width (actions column convenience)
    columnDefs.push({ width: lastColWidth + 'px', targets: -1 });

    // ── Base DataTable config ────────────────────────────────────────────
    var config = {
      responsive:   enableResponsive,
      processing:   true,
      lengthChange: false,
      autoWidth:    false,
      info:         true,
      paging:       true,
      searching:    enableSearching,
      pageLength:   pageLength,
      order:        (typeof initialOrder === 'string')
                      ? JSON.parse(initialOrder)
                      : initialOrder,
      columnDefs:   columnDefs,
      language: {
        processing: '<div class="d-flex align-items-center gap-2 py-2">'
                  + '<span class="spinner-border spinner-border-sm text-primary"></span>'
                  + '<span>Loading...</span></div>',
        emptyTable: 'No records found.',
        zeroRecords: 'No matching records found.'
      },
      dom: enableButtons
        ? '<"d-flex justify-content-between align-items-center mb-2"Bf>rtip'
        : '<"d-flex justify-content-between align-items-center mb-2"f>rtip',
      buttons: enableButtons ? [
        { extend: 'copy',   className: 'btn btn-sm btn-default' },
        { extend: 'csv',    className: 'btn btn-sm btn-default' },
        { extend: 'excel',  className: 'btn btn-sm btn-default' },
        { extend: 'pdf',    className: 'btn btn-sm btn-default' },
        { extend: 'print',  className: 'btn btn-sm btn-default' },
        { extend: 'colvis', className: 'btn btn-sm btn-default' }
      ] : []
    };

    // ── Server-side mode ─────────────────────────────────────────────────
    if (ajaxUrl) {
      config.serverSide = true;
      config.columns    = columns;   // required for server-side column mapping
      config.ajax = {
        url:  ajaxUrl,
        type: ajaxMethod,
        data: function (d) {
          // Merge any static extra params (e.g. data-params='{"status":"active"}')
          return $.extend({}, d, extraParams);
        }
      };
    }

    // ── Init ─────────────────────────────────────────────────────────────
    var dt;
    try {
      dt = $table.DataTable(config);
    } catch (err) {
      console.warn('[DataTable] Init failed on #' + ($table.attr('id') || '(no id)'), err);
      return;
    }

    // ── QR / URL search param auto-search ────────────────────────────────
    var searchParam = new URLSearchParams(window.location.search).get('search');
    if (searchParam && dt) {
      dt.search(searchParam).draw();
    }

    // ── Re-init any Select2 inside a modal that contains this table ───────
    $table.closest('.modal').on('shown.bs.modal', function () {
      var $m = $(this);
      $m.find('.select2').each(function () {
        var $s = $(this);
        if ($s.data('select2')) { try { $s.select2('destroy'); } catch (e) {} }
        try { $s.select2({ dropdownParent: $m }); } catch (e) {}
      });
    });


  });

});
</script>
<!-- EXPERIMENTAL DATATABLE INITIALIZATION -->


<!--begin::Third Party Plugin(OverlayScrollbars)-->
<script
  src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
  crossorigin="anonymous"></script>
<!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
<script
  src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
  crossorigin="anonymous"></script>
<!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
  crossorigin="anonymous"></script>
<!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->



<!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
<script>
  const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
  const Default = {
    scrollbarTheme: 'os-theme-light',
    scrollbarAutoHide: 'leave',
    scrollbarClickScroll: true,
  };
  document.addEventListener('DOMContentLoaded', function() {
    const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
    if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
      OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
        scrollbars: {
          theme: Default.scrollbarTheme,
          autoHide: Default.scrollbarAutoHide,
          clickScroll: Default.scrollbarClickScroll,
        },
      });
    }
  });
</script>
<!--end::OverlayScrollbars Configure-->
<!--end::Script-->


<!-- sortablejs -->
<script
  src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
  crossorigin="anonymous"></script>
<!-- sortablejs -->
<script>
  new Sortable(document.querySelector('.connectedSortable'), {
    group: 'shared',
    handle: '.card-header',
  });

  const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
  cardHeaders.forEach((cardHeader) => {
    cardHeader.style.cursor = 'move';
  });
</script>
<!-- apexcharts -->
<!-- <script
  src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
  integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
  crossorigin="anonymous"></script> -->
<!-- ChartJS -->
<!-- <script>
  // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
  // IT'S ALL JUST JUNK FOR DEMO
  // ++++++++++++++++++++++++++++++++++++++++++

  const sales_chart_options = {
    series: [{
        name: 'Digital Goods',
        data: [28, 48, 40, 19, 86, 27, 90],
      },
      {
        name: 'Electronics',
        data: [65, 59, 80, 81, 56, 55, 40],
      },
    ],
    chart: {
      height: 300,
      type: 'area',
      toolbar: {
        show: false,
      },
    },
    legend: {
      show: false,
    },
    colors: ['#0d6efd', '#20c997'],
    dataLabels: {
      enabled: false,
    },
    stroke: {
      curve: 'smooth',
    },
    xaxis: {
      type: 'datetime',
      categories: [
        '2023-01-01',
        '2023-02-01',
        '2023-03-01',
        '2023-04-01',
        '2023-05-01',
        '2023-06-01',
        '2023-07-01',
      ],
    },
    tooltip: {
      x: {
        format: 'MMMM yyyy',
      },
    },
  };

  const sales_chart = new ApexCharts(
    document.querySelector('#revenue-chart'),
    sales_chart_options,
  );
  sales_chart.render();
</script> -->
<!-- jsvectormap -->
<!-- <script
  src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
  integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y="
  crossorigin="anonymous"></script>
<script
  src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
  integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY="
  crossorigin="anonymous"></script> -->
<!-- jsvectormap -->
<!-- <script>
  // World map by jsVectorMap
  new jsVectorMap({
    selector: '#world-map',
    map: 'world',
  });

  // Sparkline charts
  const option_sparkline1 = {
    series: [{
      data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
    }, ],
    chart: {
      type: 'area',
      height: 50,
      sparkline: {
        enabled: true,
      },
    },
    stroke: {
      curve: 'straight',
    },
    fill: {
      opacity: 0.3,
    },
    yaxis: {
      min: 0,
    },
    colors: ['#DCE6EC'],
  };

  const sparkline1 = new ApexCharts(document.querySelector('#sparkline-1'), option_sparkline1);
  sparkline1.render();

  const option_sparkline2 = {
    series: [{
      data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
    }, ],
    chart: {
      type: 'area',
      height: 50,
      sparkline: {
        enabled: true,
      },
    },
    stroke: {
      curve: 'straight',
    },
    fill: {
      opacity: 0.3,
    },
    yaxis: {
      min: 0,
    },
    colors: ['#DCE6EC'],
  };

  const sparkline2 = new ApexCharts(document.querySelector('#sparkline-2'), option_sparkline2);
  sparkline2.render();

  const option_sparkline3 = {
    series: [{
      data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
    }, ],
    chart: {
      type: 'area',
      height: 50,
      sparkline: {
        enabled: true,
      },
    },
    stroke: {
      curve: 'straight',
    },
    fill: {
      opacity: 0.3,
    },
    yaxis: {
      min: 0,
    },
    colors: ['#DCE6EC'],
  };

  const sparkline3 = new ApexCharts(document.querySelector('#sparkline-3'), option_sparkline3);
  sparkline3.render();
</script> -->
<!--end::Script-->