<!DOCTYPE html>
<html>
<head>
    <title>Edit Pengajuan Skripsi</title>
</head>
<body>

<h1>Edit Pengajuan Skripsi</h1>

<?php if (isset($review) && $review['status'] == 'Direvisi'): ?>
    <p style="color:orange; font-weight:bold; background:#fff3cd; padding:10px; border-left:3px solid orange;">
        ⚠️ Pengajuan ini diminta revisi oleh dosen.<br>
        <b>Catatan Dosen:</b> <?= $review['feedback'] ?: '-' ?><br>
        Silakan perbaiki judul dan abstrak lalu submit ulang.
    </p>
<?php else: ?>
    <p style="color:orange;">
        ⚠️ Edit hanya bisa dilakukan selama pengajuan masih <b>Pending</b>
    </p>
<?php endif; ?>

<form method="POST" action="<?= base_url('mahasiswa/proses_edit') ?>">
    <input type="hidden" name="id_pengajuan" value="<?= $data['id_pengajuan'] ?>">

    <label>Judul Skripsi</label><br>
    <input type="text" name="judul" id="inputJudul"
           value="<?= $data['judul'] ?>" required style="width:400px;">
    <br>
    <div id="hasilDuplikasi" style="margin-top:8px;"></div>
    <br>

    <label>Abstrak</label><br>
    <textarea name="abstrak" rows="5" required style="width:400px;"><?= $data['abstrak'] ?></textarea><br><br>

    <?php if (isset($review) && $review['status'] == 'Direvisi'): ?>
        <input type="hidden" name="id_topik" value="<?= $data['id_topik'] ?>">
        <input type="hidden" name="calon_dosen" value="<?= $data['calon_dosen'] ?>">
    <?php else: ?>
        <label>Topik Skripsi</label><br>
        <select name="id_topik" id="topik" required>
            <option value="">Pilih Topik</option>
            <?php foreach ($topik as $t): ?>
                <option value="<?= $t['id_topik'] ?>"
                    data-topik="<?= strtolower($t['nama_topik']) ?>"
                    <?= ($data['id_topik'] == $t['id_topik']) ? 'selected' : '' ?>>
                    <?= $t['nama_topik'] ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Calon Dosen Pembimbing</label><br>
        <select name="calon_dosen" id="dosen" required>
            <option value="">Pilih Dosen</option>
            <?php foreach ($dosen as $d): ?>
                <option value="<?= $d['nidn'] ?>"
                    data-keahlian="<?= strtolower($d['bidang_keahlian']) ?>"
                    <?= ($data['calon_dosen'] == $d['nidn']) ? 'selected' : '' ?>>
                    <?= $d['nama'] ?>
                    (Sisa Slot: <?= $d['kuota_bimbingan'] - $d['total_bimbingan'] ?> / <?= $d['kuota_bimbingan'] ?>)
                </option>
            <?php endforeach; ?>
        </select><br><br>
    <?php endif; ?>

    <button type="submit" id="btnSubmit" style="background-color:lightblue; cursor:pointer;">
        Simpan Perubahan
    </button>
    <a href="<?= base_url('mahasiswa/status') ?>">
        <button type="button">Batal</button>
    </a>
</form>

<script>
const inputJudul     = document.getElementById('inputJudul');
const hasilDuplikasi = document.getElementById('hasilDuplikasi');
const btnSubmit      = document.getElementById('btnSubmit');
const topik          = document.getElementById('topik');
const dosen          = document.getElementById('dosen');
let timer;

// Filter dosen berdasarkan topik (hanya jika elemen tersedia di halaman)
if (topik && dosen) {
    topik.addEventListener('change', function () {
        const selectedOption = topik.options[topik.selectedIndex];
        const topikDipilih   = selectedOption.getAttribute('data-topik');
        const semuaDosen     = dosen.querySelectorAll('option');

        semuaDosen.forEach(function (option) {
            if (option.value == '') { option.style.display = 'block'; return; }
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
}

// Cek duplikasi judul
inputJudul.addEventListener('input', function () {
    clearTimeout(timer);
    const judul = this.value.trim();

    if (judul.length < 5) {
        hasilDuplikasi.innerHTML = '';
        btnSubmit.disabled = false;
        btnSubmit.style.backgroundColor = 'lightblue';
        return;
    }

    hasilDuplikasi.innerHTML = '<span style="color:gray;">Memeriksa duplikasi...</span>';

    timer = setTimeout(function () {
        fetch('<?= base_url('mahasiswa/cek_duplikasi') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'judul=' + encodeURIComponent(judul) + '&id_pengajuan=<?= $data["id_pengajuan"] ?>'
        })
        .then(res => res.json())
        .then(result => {
            if (result.ada) {
                btnSubmit.disabled = true;
                btnSubmit.style.backgroundColor = '#ccc';

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
                btnSubmit.style.backgroundColor = 'lightblue';
                hasilDuplikasi.innerHTML = `
                    <span style="color:green; font-weight:bold;">
                        ✅ Judul belum ada di arsip maupun pengajuan aktif
                    </span>`;
            }
        });
    }, 500);
});
</script>

</body>
</html>