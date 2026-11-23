<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= array_sum(array_column($statistik_status ?? [], 'total')) ?></h3>
                    <p>Total Pengajuan</p>
                </div>
                <div class="icon"><i class="fas fa-file-alt"></i></div>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= is_array($statistik_topik) ? count($statistik_topik) : 0 ?></h3>
                    <p>Total Topik</p>
                </div>
                <div class="icon"><i class="fas fa-book"></i></div>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= array_sum(array_column($statistik_arsip ?? [], 'total')) ?></h3>
                    <p>Total Arsip</p>
                </div>
                <div class="icon"><i class="fas fa-archive"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title"><strong>Tren Topik Pengajuan Skripsi</strong></h3>
                </div>
                <div class="card-body">
                    <canvas id="chartTopik" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title"><strong>Status Pengajuan</strong></h3>
                </div>
                <div class="card-body">
                    <canvas id="chartStatus" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title"><strong>Pengajuan Per Bulan (Tahun Ini)</strong></h3>
                </div>
                <div class="card-body">
                    <canvas id="chartBulan" style="min-height: 280px; height: 280px; max-height: 280px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title"><strong>Tingkat Kepopuleran Topik (Pengajuan vs Arsip)</strong></h3>
                </div>
                <div class="card-body">
                    <canvas id="chartHorizontalPopuler" style="min-height: 280px; height: 280px; max-height: 280px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">Statistik Topik</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-hover m-0">
                        <thead>
                            <tr>
                                <th width="10%">No</th>
                                <th>Topik</th>
                                <th width="30%">Jumlah Pengajuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($statistik_topik ?? [] as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['nama_topik'] ?></td>
                                    <td><?= $row['total'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title">Statistik Arsip Skripsi</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-hover m-0">
                        <thead>
                            <tr>
                                <th width="10%">No</th>
                                <th>Topik</th>
                                <th width="30%">Total Arsip</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($statistik_arsip ?? [] as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['nama_topik'] ?></td>
                                    <td><?= $row['total'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- Ekstraksi Data dari Backend PHP ---
        const dataTopik = <?= json_encode($statistik_topik ?? []) ?>;
        const labelTopik = dataTopik.map(item => item.nama_topik);
        const nilaiTopik = dataTopik.map(item => item.total);

        const dataStatus = <?= json_encode($statistik_status ?? []) ?>;
        const labelStatus = dataStatus.map(item => item.status || 'Pending');
        const nilaiStatus = dataStatus.map(item => item.total);

        const dataArsip = <?= json_encode($statistik_arsip ?? []) ?>;
        // Membuat peta pencarian cepat untuk membandingkan data pengajuan dan arsip secara berurutan
        const mapPengajuan = {};
        dataTopik.forEach(item => mapPengajuan[item.nama_topik] = item.total);
        
        // Label disamakan urutannya berdasarkan ranking kepopuleran arsip teratas
        const labelPopuler = dataArsip.map(item => item.nama_topik);
        const nilaiArsipPopuler = dataArsip.map(item => item.total);
        const nilaiPengajuanPopuler = dataArsip.map(item => mapPengajuan[item.nama_topik] || 0);

        const dataBulanan = <?= json_encode($statistik_bulanan ?? []) ?>;

        // 1. Grafik Tren Topik (Vertical Bar Chart)
        new Chart(document.getElementById('chartTopik'), {
            type: 'bar',
            data: {
                labels: labelTopik,
                datasets: [{ label: 'Jumlah Pengajuan', data: nilaiTopik, backgroundColor: '#17a2b8' }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // 2. Grafik Status Pengajuan (Doughnut Chart)
        new Chart(document.getElementById('chartStatus'), {
            type: 'doughnut',
            data: {
                labels: labelStatus,
                datasets: [{ data: nilaiStatus, backgroundColor: ['#28a745', '#dc3545', '#ffc107', '#17a2b8'] }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // 3. Grafik Pengajuan Per Bulan (Line Chart / Area)
        new Chart(document.getElementById('chartBulan'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pengajuan Masuk',
                    data: dataBulanan,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // 4. Grafik Spesifik Menurun Berdasarkan Request (Horizontal Bar Chart)
        new Chart(document.getElementById('chartHorizontalPopuler'), {
            type: 'bar',
            data: {
                labels: labelPopuler,
                datasets: [
                    {
                        label: 'Total di Pengajuan',
                        data: nilaiPengajuanPopuler,
                        backgroundColor: '#007bff'
                    },
                    {
                        label: 'Total di Arsip Kelulusan',
                        data: nilaiArsipPopuler,
                        backgroundColor: '#ffc107'
                    }
                ]
            },
            options: {
                indexAxis: 'y', // MEMBUAT GRAFIK JADI MENURUN / HORIZONTAL
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    });
</script>

<?= $this->endSection() ?>