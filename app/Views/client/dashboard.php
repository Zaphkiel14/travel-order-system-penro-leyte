<?= $this->extend('layouts/user-base') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
   <div class="row">
      <div class="col-12 col-sm-6 col-md-3">
         <div class="info-box shadow-sm border-0 rounded-3">
            <span class="info-box-icon bg-info text-white elevation-1">
               <i class="fas fa-file-alt"></i>
            </span>

            <div class="info-box-content">
               <span class="info-box-text text-muted">Total Requests</span>
               <span class="info-box-number fs-4 fw-bold">
                  Placeholder
               </span>
            </div>
         </div>
      </div>

      <!-- Pending -->
      <div class="col-12 col-sm-6 col-md-3">
         <div class="info-box shadow-sm border-0 rounded-3">
            <span class="info-box-icon bg-warning text-white elevation-1">
               <i class="fas fa-clock"></i>
            </span>

            <div class="info-box-content">
               <span class="info-box-text text-muted">Pending Requests</span>
               <span class="info-box-number fs-4 fw-bold">
                  Placeholder
               </span>
            </div>
         </div>
      </div>

      <!-- Approved -->
      <div class="col-12 col-sm-6 col-md-3">
         <div class="info-box shadow-sm border-0 rounded-3">
            <span class="info-box-icon bg-success text-white elevation-1">
               <i class="fas fa-check-circle"></i>
            </span>

            <div class="info-box-content">
               <span class="info-box-text text-muted">Approved Requests</span>
               <span class="info-box-number fs-4 fw-bold">
                  Placeholder
               </span>
            </div>
         </div>
      </div>

      <!-- Rejected -->
      <div class="col-12 col-sm-6 col-md-3">
         <div class="info-box shadow-sm border-0 rounded-3">
            <span class="info-box-icon bg-danger text-white elevation-1">
               <i class="fas fa-trash"></i>
            </span>

            <div class="info-box-content">
               <span class="info-box-text text-muted">Rejected Requests</span>
               <span class="info-box-number fs-4 fw-bold">
                  Placeholder
               </span>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="container-fluid">
   <div class="row">
      <div class="col-12">
         <div class="card">
            <div class="card-header dataTables_wrapper dt-bootstrap5">
               <div class="row">
                  <div class="col-12 col-md-9">
                     <h4 class="card-title mt-2">My Travel Orders</h4>
                  </div>
                  <div class="col-12 col-md-3 text-md-end">
                     <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-new-travel-order">
                        <i class="bi bi-plus-lg"></i> New Travel Order
                     </button>
                  </div>
               </div>
            </div>

            <div class="card-body">
               <table id="travelOrderTbl" class="table table-bordered table-striped datatable-standard" data-last-column-width="100">
                  <thead>
                     <tr>
                        <th>Date Submitted</th>
                        <th>Destination</th>
                        <th>Travel Dates</th>
                        <th>Status</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if (!empty($travelOrder)): ?>
                        <?php foreach ($travelOrder as $travel): ?>
                           <tr>
                              <td><?= $travel['created_at'] ?></td>
                              <td><?= $travel['destination'] ?></td>
                              <td><?= date('M d, Y', strtotime($travel['departure_date'])) . ' - ' . date('M d, Y', strtotime($travel['arrival_date'])) ?></td>
                              <td><?= $travel['status'] ?></td>
                              <td></td>

                           </tr>
                        <?php endforeach; ?>
                     <?php else: ?>
                        <tr>
                           <td colspan="9" class="text-center">No travel order requests..</td>
                        </tr>
                     <?php endif; ?>
                  </tbody>
                  <tfoot>

                  </tfoot>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- Modal – New Travel Order -->
<div class="modal fade" id="modal-new-travel-order" tabindex="-1">
   <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg border-0 rounded-3">

         <form method="POST" action="<?= route_to('user.maintenance.schedule') ?>" class="needs-validation" novalidate>

            <!-- HEADER -->
            <div class="modal-header border-0 px-4 pt-4">
               <h5 class="modal-title fw-semibold">
                  <i class="fas fa-plane-departure me-2 text-primary"></i>
                  New Travel Order
               </h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body px-4">

               <!-- PERSONAL INFO -->
               <div class="mb-3">
                  <h6 class="fw-bold border-start ps-2">
                     Personal Information
                  </h6>
               </div>

               <div class="row g-3 mb-4">
                  <div class="col-md-12">
                     <label class="form-label">Name</label>
                     <input type="text" class="form-control form-control-sm" name="name" placeholder="Enter full name">
                  </div>

                  <div class="col-md-6">
                     <label class="form-label">Salary Grade</label>
                     <input type="text" class="form-control form-control-sm" name="salary" placeholder="e.g. SG-15">
                  </div>

                  <div class="col-md-6">
                     <label class="form-label">Position</label>
                     <input type="text" class="form-control form-control-sm" name="position">
                  </div>

                  <div class="col-md-12">
                     <label class="form-label">Division / Section / Unit</label>
                     <select class="form-select form-select-sm" name="division" disabled>
                        <option selected disabled>(prefilled according to user office)</option>
                        <option>Finance</option>
                        <option>HR</option>
                     </select>
                  </div>
               </div>

               <!-- TRAVEL DETAILS -->
               <div class="mb-3">
                  <h6 class="fw-bold border-start ps-2">
                     Travel Details
                  </h6>
               </div>

               <div class="row g-3 mb-4">
                  <div class="col-md-6">
                     <label class="form-label">Departure Date</label>
                     <input type="date" class="form-control form-control-sm" name="departure_date">
                  </div>

                  <div class="col-md-6">
                     <label class="form-label">Arrival Date</label>
                     <input type="date" class="form-control form-control-sm" name="arrival_date">
                  </div>

                  <div class="col-md-12">
                     <label class="form-label">Destination</label>
                     <input type="text" class="form-control form-control-sm" name="destination"
                        placeholder="e.g. Regional Office, Manila">
                  </div>

                  <div class="col-md-12">
                     <label class="form-label">Purpose of Travel</label>
                     <textarea class="form-control form-control-sm" name="purpose" rows="3"
                        placeholder="Describe the purpose..."></textarea>
                  </div>
               </div>

               <!-- DOCUMENTS -->
               <div class="mb-3">
                  <h6 class="fw-bold border-start ps-2">
                     Supporting Documents
                  </h6>
               </div>

               <div class="row g-3">
                  <div class="col-md-12">
                     <label class="form-label">Upload Document</label>
                     <input type="file" class="form-control form-control-sm" name="document">
                  </div>
               </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer border-0 px-4 pb-4">
               <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">
                  Cancel
               </button>

               <button type="submit" class="btn btn-success btn-sm px-4">
                  <i class="fas fa-paper-plane me-1"></i>
                  Submit Travel Order
               </button>
            </div>

         </form>

      </div>
   </div>
</div>
<?= $this->endSection() ?>
