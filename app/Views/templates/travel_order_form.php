<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Travel Order - <?= esc($travel_order['travel_order_number']) ?></title>

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous" />

  <style>
    :root {
      --header-h: 48mm;
      --footer-h: 28mm;
      --page-top-margin: 10mm;
      --page-side-margin: 20mm;
    }

    .font-times {
      font-family: "Times New Roman", Times, serif;
    }

    .font-arial {
      font-family: Arial, Helvetica, sans-serif;
    }

    body {
      font-family: "Times New Roman", Times, serif;
    }

    .text-justify {
      text-align: justify;
    }

    .tab {
      display: block;
      text-indent: 40px;
    }

    .tab-2 {
      display: block;
      text-indent: 80px;
    }

    .no-space {
      line-height: 1 !important;
      margin: 0 !important;
    }

    .single-space {
      line-height: 1 !important;
      margin: 0 0 0.5em 0 !important;
    }

    .space-115 {
      line-height: 1.15 !important;
      margin: 0 0 0.5em 0 !important;
    }

    .space-150 {
      line-height: 1.5 !important;
      margin: 0 0 0.5em 0 !important;
    }

    .double-space {
      line-height: 2 !important;
      margin: 0 0 0.5em 0 !important;
    }

    .pt-9 {
      font-size: 9pt !important;
    }

    .pt-10 {
      font-size: 10pt !important;
    }

    .pt-11 {
      font-size: 11pt !important;
    }

    .pt-15 {
      font-size: 15pt !important;
    }

    .header,
    .footer {
      width: 100%;
      left: 0;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      align-items: stretch;
    }

    .header-row,
    .footer-row {
      display: flex;
      flex-direction: row;
      align-items: flex-end;
      justify-content: center;
      gap: 3mm;
      width: 100%;
      padding-bottom: 2mm;
    }

    .footer-row {
      align-items: center;
    }

    .header-bar {
      width: 100%;
      height: 15px;
      background-color: #9e0000 !important;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
      flex-shrink: 0;
    }

    .header-text,
    .footer-text {
      text-align: center;
    }

    .header-img-left img {
      width: 27mm;
      height: 27mm;
      object-fit: contain;
    }

    .header-img-right img {
      width: 30mm;
      height: 30mm;
      object-fit: contain;
    }

    .footer-img-left img,
    .footer-img-right img {
      width: 35mm;
      height: 35mm;
      object-fit: contain;
    }

    .header {
      padding-top: 0mm;
      padding-bottom: 0;
    }

    .footer {
      padding-top: 2mm;
      padding-bottom: 2mm;
    }

    @media screen {

      .header,
      .footer {
        position: fixed;
      }

      .header {
        top: 0;
      }

      .footer {
        bottom: 0;
        border-top: 1px solid #dee2e6;
      }

      .content {
        margin-top: var(--header-h);
        margin-bottom: var(--footer-h);
        padding-left: var(--page-side-margin);
        padding-right: var(--page-side-margin);
        padding-top: 6mm;
        padding-bottom: 6mm;
      }
    }

    @media print {
      @page {
        size: 8.5in 13in;
        margin: 0mm;
      }

      html,
      body {
        margin: 0 !important;
        padding: 0 !important;
      }

      .header,
      .footer {
        position: fixed;
        left: 0;
        width: 100%;
        box-sizing: border-box;
      }

      .header {
        top: 0;
        padding-top: var(--page-top-margin);
        padding-bottom: 0;
        height: auto;
      }

      .footer {
        bottom: 0;
        padding-top: 4mm;
        padding-bottom: var(--page-top-margin);
        height: auto;
      }

      .content {
        margin: 0;
        padding-top: var(--header-h);
        padding-bottom: var(--footer-h);
        padding-left: var(--page-side-margin);
        padding-right: var(--page-side-margin);
      }
    }
  </style>
</head>

<body onload="window.print()">

  <!-- HEADER -->
  <div class="header font-times">
    <div class="header-row">
      <div class="header-img-left">
        <img src="<?= base_url('denr_logo.png') ?>" alt="DENR Logo" />
      </div>
      <div class="header-text">
        <p class="no-space pt-11">Republic of The Philippines</p>
        <p class="single-space pt-11">
          <strong>
            <span style="font-size:15pt">D</span>EPARTMENT OF
            <span style="font-size:15pt">E</span>NVIRONMENT AND
            <span style="font-size:15pt">N</span>ATURAL
            <span style="font-size:15pt">R</span>ESOURCES
          </strong>
        </p>
        <p class="single-space pt-11">KAGAWARAN NG KAPALIGIRAN AT LIKAS NA YAMAN</p>
        <p class="single-space pt-11">Provincial Environment and Natural Resources Office Leyte</p>
      </div>
      <div class="header-img-right">
        <img src="<?= base_url('bagong_pilipinas_logo.png') ?>" alt="Bagong Pilipinas Logo" />
      </div>
    </div>
    <div class="header-bar"></div>
  </div>

  <!-- FOOTER -->
  <div class="footer font-times">
    <div class="footer-row">
      <div class="footer-img-left">
        <img src="<?= base_url('system_certification.jpg') ?>" alt="System Certification" />
      </div>
      <div class="footer-text">
        <p class="single-space pt-9">
          Government Center, Brgy. Baras, Palo, Leyte, Philippines <br />
          Tel. Nos. (053) 520-1287 / VolIP No. 3115 <br />
          penroleyte@denr.gov.ph / penroleyte@yahoo.com
        </p>
      </div>
      <div class="footer-img-right">
        <img src="<?= base_url('socotec.jpg') ?>" alt="Socotec" />
      </div>
    </div>
  </div>

  <!-- CONTENT -->
  <div class="content container-fluid font-arial">

    <p class="text-center space-115 pt-10"><strong>TRAVEL ORDER</strong></p>
    <p class="text-center space-115 pt-10">
      (No. <u><?= esc($travel_order['travel_order_number']) ?></u>)
    </p>
    <br />

    <!-- NAME & SALARY GRADE -->
    <div class="row">
      <div class="col-3 d-flex justify-content-between mb-0">
        <span class="space-115 pt-9">NAME</span>
        <span class="space-115 pt-9">:</span>
      </div>
      <div class="col-5">
        <?php foreach ($travel_order['persons'] as $person): ?>
          <p class="space-115 pt-9">
            <strong><?= esc(strtoupper($person['name'])) ?></strong>
          </p>
        <?php endforeach; ?>
      </div>
      <div class="col-2 d-flex justify-content-between mb-0">
        <span class="space-115 pt-9">Salary</span>
        <span class="space-115 pt-9">:</span>
      </div>
      <div class="col-2">
        <?php foreach ($travel_order['persons'] as $person): ?>
          <p class="space-115 pt-9"><?= esc($person['salary_grade']) ?></p>
        <?php endforeach; ?>
      </div>
    </div>
    <br />

    <!-- POSITION & OFFICIAL STATION -->
    <div class="row">
      <div class="col-3 d-flex justify-content-between mb-0">
        <span class="space-115 pt-9">POSITION</span>
        <span class="space-115 pt-9">:</span>
      </div>
      <div class="col-5">
        <?php foreach ($travel_order['persons'] as $person): ?>
          <p class="space-115 pt-9">
            <strong><?= esc($person['position']) ?></strong>
          </p>
        <?php endforeach; ?>
      </div>
      <div class="col-2 d-flex justify-content-between mb-0">
        <span class="space-115 pt-9">Official Station</span>
        <span class="space-115 pt-9">:</span>
      </div>
      <div class="col-2">
        <p class="space-115 pt-9">PENRO Leyte</p>
      </div>
    </div>
    <br />

    <!-- DEPARTURE DATE -->
    <div class="row">
      <div class="col-3 d-flex justify-content-between mb-0">
        <span class="space-115 pt-9">Departure Date</span>
        <span class="space-115 pt-9">:</span>
      </div>
      <div class="col-9">
        <p class="space-115 pt-9">
          <?= esc(date('F j, Y', strtotime($travel_order['departure_date']))) ?>
        </p>
      </div>
    </div>
    <br />

    <!-- ARRIVAL DATE -->
    <div class="row">
      <div class="col-3 d-flex justify-content-between mb-0">
        <span class="space-115 pt-9">Arrival Date</span>
        <span class="space-115 pt-9">:</span>
      </div>
      <div class="col-9">
        <p class="space-115 pt-9">
          <?= esc(date('F j, Y', strtotime($travel_order['arrival_date']))) ?>
        </p>
      </div>
    </div>
    <br />

    <!-- DESTINATION -->
    <div class="row">
      <div class="col-3 d-flex justify-content-between mb-0">
        <span class="space-115 pt-9">Destination</span>
        <span class="space-115 pt-9">:</span>
      </div>
      <div class="col-9">
        <p class="space-115 pt-9"><?= esc($travel_order['destination']) ?></p>
      </div>
    </div>
    <br />

    <!-- PURPOSE OF TRAVEL -->
    <div class="row">
      <div class="col-3 d-flex justify-content-between mb-0">
        <span class="space-115 pt-9">Purpose of Travel</span>
        <span class="space-115 pt-9">:</span>
      </div>
      <div class="col-9">
        <p class="space-115 pt-9"><?= esc($travel_order['purpose_of_travel']) ?></p>
      </div>
    </div>
    <br>

    <!-- ALLOWANCES & REMARKS -->
    <div class="row">
      <p class="space-115 pt-9">Per Diems/Expenses Allowed: ________________</p>
      <br>
      <p class="space-115 pt-9">Assistants or Laborers Allowed: ________________</p>
      <br>
      <p class="space-115 pt-9">Appropriation to which travel expenses should be charged: ________________</p>
      <br>
      <p class="space-115 pt-9">Remarks or special instructions: <u>Submit report upon completion of travel</u></p>
    </div>
    <br>

    <!-- CERTIFICATION -->
    <div class="row">
      <p class="space-115 pt-9">Certifications:</p>
      <p class="space-115 pt-9 tab-2">
        This is to certify that the travel is necessary and is connected with the functions
        of the official/ employee of this Div./Sec./Unit
      </p>
    </div>
    <br>

    <!-- RECOMMENDING & APPROVED SIGNATORIES -->
    <div class="row">
      <div class="col-6">
        <p class="space-115 pt-9"><strong>Recommending Approval:</strong></p>
        <br>
        <?php if (!empty($travel_order['division_head_name'])): ?>
          <p class="no-space pt-9 text-center">
            <strong><u><?= esc(strtoupper($travel_order['division_head_name'])) ?></u></strong>
          </p>
        <?php else: ?>
          <p class="no-space pt-9 text-center">__________________</p>
        <?php endif; ?>
        <p class="space-115 pt-9 text-center">
          <?= esc($travel_order['division_head_position'] ?? 'Division Chief') ?>
        </p>
      </div>
      <div class="col-6">
        <p class="space-115 pt-9"><strong>APPROVED:</strong></p>
        <br>
        <?php if (!empty($travel_order['organization_head_name'])): ?>
          <p class="no-space pt-9 text-center">
            <strong><u><?= esc(strtoupper($travel_order['organization_head_name'])) ?></u></strong>
          </p>
        <?php else: ?>
          <p class="no-space pt-9 text-center">__________________</p>
        <?php endif; ?>
        <p class="space-115 pt-9 text-center">
          <?= esc($travel_order['organization_head_position'] ?? 'PENR Officer') ?>
        </p>
      </div>
    </div>

    <br>
    <hr><br>

    <!-- AUTHORIZATION -->
    <div class="row">
      <p class="space-115 pt-9 text-center"><strong>AUTHORIZATION</strong></p>
      <br>
      <p class="space-115 pt-9 tab">
        I hereby authorize the accountant to deduct the corresponding amount of the unliquidated
        cash advance from my succeeding salary for my failure to liquidate this travel within the
        prescribed thirty-day period from upon return to my permanent official station pursuant to
        Item 5.1.3 COA 97-002 dated February 10, 1987 and Sec. 16 EO No. 248 dated May 29, 1995.
      </p>
    </div>

    <!-- PERSONNEL SIGNATURES -->
    <div class="row">
      <?php foreach ($travel_order['persons'] as $person): ?>
        <div class="col-6">
          <br>
          <p class="no-space pt-9 text-center">
            <strong><u><?= esc(strtoupper($person['name'])) ?></u></strong>
          </p>
          <p class="space-115 pt-9 text-center"><?= esc($person['position']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>

  </div>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
</body>

</html>