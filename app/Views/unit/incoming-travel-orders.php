<?= $this->extend('layouts/supervisor-base') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">

                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title flex-grow-1">Incoming Travel Orders</h3>

                </div>
                <div class="card-body table-responsive">
                    <table id="incoming_travel_orders_table"
                        class="table table-bordered table-striped datatable-standard"
                        data-last-column-width="100"
                        data-page-length="10"
                        data-order='[[0,"desc"]]'
                        data-url="<?= route_to('data.incomingTravelOrders') ?>">
                        <thead>
                            <tr>
                                <th data-name="created_at">Date Submitted</th>
                                <th data-name="travel_order_number">Travel Order<br>Number</th>
                                <th data-name="destination">Destination</th>
                                <th data-name="travel_dates">Travel Dates</th>
                                <th data-name="status" data-orderable="false">Status</th>
                                <th data-name="actions" data-orderable="false" data-searchable="false">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th data-name="created_at">Date Submitted</th>
                                <th data-name="travel_order_number">Travel Order<br>Number</th>
                                <th data-name="destination">Destination</th>
                                <th data-name="travel_dates">Travel Dates</th>
                                <th data-name="status" data-orderable="false">Status</th>
                                <th data-orderable="false" data-searchable="false">Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>