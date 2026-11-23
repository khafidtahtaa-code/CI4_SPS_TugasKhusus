<?= $this->extend('layout/template_mahasiswa') ?>
<?= $this->section('content') ?>

<div class="card">
    <!-- 
    <div class="card-header">
        <h3 class="card-title">
            Form Pengajuan Skripsi
        </h3>
    </div> -->

    <div class="card-body">

        <?php if (!empty($pesan)): ?>
            <div class="alert alert-warning">
                <?= $pesan ?>
            </div>
        <?php endif; ?>

        <form method="POST"
            action="<?= base_url('mahasiswa/proses_pengajuan') ?>">

            <div class="form-group">
                <label>
                    <i class="fas fa-user"></i>
                    Nama Mahasiswa
                </label>
                <input type="text"
                    name="nama"
                    placeholder="Masukkan nama lengkap"
                    class="form-control"
                    required>
            </div>

            <div class="form-group">
                <label>
                    <i class="fas fa-calendar"></i>
                    Angkatan
                </label>
                <input type="text"
                    name="angkatan"
                    class="form-control"
                    placeholder="Contoh : 2023"
                    required>
            </div>

            <div class="form-group">
                <label>
                    <i class="fas fa-book"></i>
                    Judul Skripsi
                </label>
                <input
                    type="text"
                    name="judul"
                    id="inputJudul"
                    class="form-control"
                    placeholder="Ketik judul skripsi..."
                    required>

                <div id="hasilDuplikasi" class="mt-2"></div>
            </div>
            <br>

            <div class="form-group">
                <label>
                    <i class="fas fa-align-left"></i>
                    Abstrak
                </label>
                <textarea
                    name="abstrak"
                    class="form-control"
                    rows="6"
                    required></textarea>
            </div> <br>

            <div class="form-group">
                <label>
                    <i class="fas fa-tags"></i>
                    Topik Skripsi
                </label>
                <select
                    name="id_topik"
                    id="topik"
                    class="form-control"
                    required>
                    <option value="">Pilih Topik</option>
                    <?php foreach ($topik as $t): ?>
                        <option value="<?= $t['id_topik'] ?>"
                            data-topik="<?= strtolower($t['nama_topik']) ?>">
                            <?= $t['nama_topik'] ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>
            </div>

            <div class="form-group">
                <label>
                    <i class="fas fa-user-tie"></i>
                    Calon Dosen Pembimbing
                </label>
                <select
                    name="calon_dosen"
                    id="dosen"
                    class="form-control"
                    required>
                    <option value="">Pilih Dosen</option>
                    <?php foreach ($dosen as $d): ?>
                        <option value="<?= $d['nidn'] ?>"
                            data-keahlian="<?= strtolower($d['bidang_keahlian']) ?>">
                            <?= $d['nama'] ?>
                            (Sisa Slot: <?= $d['kuota_bimbingan'] - $d['total_bimbingan'] ?>
                            / <?= $d['kuota_bimbingan'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button
                type="submit"
                id="btnSubmit"
                class="btn btn-success btn-block">

                <i class="fas fa-paper-plane"></i>
                Submit Pengajuan

            </button>

        </form>


    </div>

</div>


<script>
    const topikEl = document.getElementById('topik');
    const dosenEl = document.getElementById('dosen');
    const inputJudul = document.getElementById('inputJudul');
    const hasilDuplikasi = document.getElementById('hasilDuplikasi');
    const btnSubmit = document.getElementById('btnSubmit');
    let timer;

    // Filter dosen berdasarkan topik
    topikEl.addEventListener('change', function() {
        const selected = topikEl.options[topikEl.selectedIndex];
        const topikDipilih = selected.getAttribute('data-topik');
        const semuaDosen = dosenEl.querySelectorAll('option');

        semuaDosen.forEach(function(option) {
            if (option.value == '') {
                option.style.display = 'block';
                return;
            }
            const keahlian = option.getAttribute('data-keahlian');
            if (!topikDipilih) {
                option.style.display = 'block';
            } else if (keahlian && keahlian.includes(topikDipilih)) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    });

    // Cek duplikasi judul
    inputJudul.addEventListener('input', function() {
        clearTimeout(timer);
        const judul = this.value.trim();

        if (judul.length < 5) {
            hasilDuplikasi.innerHTML = '';
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('btn-secondary');
            btnSubmit.classList.add('btn-success');
            return;
        }

        hasilDuplikasi.innerHTML = `
            <div class="text-secondary">
            <i class="fas fa-spinner fa-spin"></i>
            Memeriksa Duplikasi...
            </div>`;

        timer = setTimeout(function() {
            fetch('<?= base_url('mahasiswa/cek_duplikasi') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'judul=' + encodeURIComponent(judul) + '&id_pengajuan='
                })
                .then(res => res.json())
                .then(result => {
                    if (result.ada) {
                        btnSubmit.disabled = true;
                        btnSubmit.classList.remove('btn-success');
                        btnSubmit.classList.add('btn-secondary');

                        let html = `<div style="background:#fff3cd; border:1px solid #ffc107;
                                padding:10px; border-radius:4px;">
                    <b style="color:orange;">⚠️ Ditemukan ${result.data.length} judul serupa:</b>
                    <ul style="margin:5px 0;">`;

                        result.data.forEach(item => {
                            html += `<li>${item.judul}
                        <small style="color:gray;">
                            (${item.nama_mahasiswa} - ${item.sumber})
                        </small>
                    </li>`;
                        });

                        html += `</ul>
                    <small style="color:red; font-weight:bold;">
                        ❌ Tidak bisa submit, ubah judul terlebih dahulu
                    </small>
                </div>`;

                        hasilDuplikasi.innerHTML = html;
                    } else {
                        btnSubmit.disabled = false;
                        btnSubmit.classList.remove('btn-secondary');
                        btnSubmit.classList.add('btn-success');
                        hasilDuplikasi.innerHTML = `
                            <div class="text-secondary">
                            <i class="fas fa-spinner fa-spin"></i>
                            Memeriksa judul...
                            </div>`;
                    }
                });
        }, 500);
    });
</script>

<?= $this->endSection() ?>