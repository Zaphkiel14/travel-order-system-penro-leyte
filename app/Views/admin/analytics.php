<?= $this->extend('layouts/admin-base') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-primary shadow-sm">
                    <i class="bi bi-bar-chart-fill"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text mb-0 text-muted">Total</span>
                    <span class="info-box-number fs-4 fw-bold">
                        <?= esc($stats['total']) ?: 0 ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-warning shadow-sm">
                    <i class="bi bi-hourglass-split"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text mb-0 text-muted">Pending</span>
                    <span class="info-box-number fs-4 fw-bold">
                        <?= esc($stats['pending']) ?: 0 ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-success shadow-sm">
                    <i class="bi bi-hand-thumbs-up-fill"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text mb-0 text-muted">Approved</span>
                    <span class="info-box-number fs-4 fw-bold">
                        <?= esc($stats['approved']) ?: 0 ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon text-bg-danger shadow-sm">
                    <i class="bi bi-hand-thumbs-down-fill"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text mb-0 text-muted">Rejected</span>
                    <span class="info-box-number fs-4 fw-bold">
                        <?= esc($stats['rejected']) ?: 0 ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <form method="GET" class="d-flex gap-2">

                <select name="month" class="form-control w-auto">
                    <option value="">All Months</option>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= ($month == $m) ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>

                <input type="number"
                       name="year"
                       class="form-control w-auto"
                       placeholder="Year"
                       value="<?= esc($year) ?>">

                <button class="btn btn-primary">
                    Filter
                </button>

            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Travel Order Trends</h3>
                </div>

                <div class="card-body">
                    <canvas id="travelChart" height="100"></canvas>
                </div>
            </div>

        </div>
    </div>

</div>
                        
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('travelChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,

        datasets: [
            {
                label: 'Pending',
                data: <?= json_encode($pending) ?>,
                borderColor: 'orange',
                tension: 0.3
            },
            {
                label: 'Approved',
                data: <?= json_encode($approved) ?>,
                borderColor: 'green',
                tension: 0.3
            },
            {
                label: 'Rejected',
                data: <?= json_encode($rejected) ?>,
                borderColor: 'red',
                tension: 0.3
            },
            {
                label: 'Total',
                data: <?= json_encode($total) ?>,
                borderColor: 'blue',
                tension: 0.3
            }
        ]
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false
        },
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<?= $this->endSection() ?>