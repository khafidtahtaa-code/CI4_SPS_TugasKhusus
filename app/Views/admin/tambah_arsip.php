<!DOCTYPE html>
<html>
<head><title>Tambah Arsip Skripsi</title></head>
<body>

<h1>Tambah Arsip Skripsi Manual</h1>

<form method="POST" action="<?= base_url('admin/simpan_arsip') ?>" enctype="multipart/form-data">

    <label>NPM</label><br>
    <input type="text" name="npm" required><br><br>

    <label>Nama Mahasiswa</label><br>
    <input type="text" name="nama_mahasiswa" required><br><br>

    <label>Angkatan</label><br>
    <input type="text" name="angkatan" placeholder="contoh: 2021"><br><br>

    <label>Judul Skripsi</label><br>
    <input type="text" name="judul" required style="width:400px;"><br><br>

    <label>Abstrak</label><br>
    <textarea name="abstrak" rows="5" style="width:400px;"></textarea><br><br>

    <label>Kata Kunci</label><br>
    <input type="text" name="kata_kunci" placeholder="contoh: AI, Machine Learning, CNN" style="width:400px;"><br><br>

    <label>Topik</label><br>
    <select name="id_topik">
        <option value="">-- Pilih Topik --</option>
        <?php foreach ($topik_list as $t): ?>
            <option value="<?= $t['id_topik'] ?>"><?= $t['nama_topik'] ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Pembimbing 1</label><br>
    <select name="pembimbing_1">
        <option value="">-- Pilih Dosen --</option>
        <?php foreach ($dosen_list as $d): ?>
            <option value="<?= $d['nidn'] ?>"><?= $d['nama'] ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Pembimbing 2</label><br>
    <select name="pembimbing_2">
        <option value="">-- Pilih Dosen --</option>
        <?php foreach ($dosen_list as $d): ?>
            <option value="<?= $d['nidn'] ?>"><?= $d['nama'] ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Tahun Lulus</label><br>
    <input type="number" name="tahun_lulus" value="<?= date('Y') ?>" required><br><br>

    <label>Upload File PDF Skripsi</label><br>
    <input type="file" name="file_pdf" accept=".pdf"><br><br>

    <button type="submit" style="background-color:lightgreen;">Simpan Arsip</button>
    <a href="<?= base_url('admin/arsip_skripsi') ?>"><button type="button">Batal</button></a>

</form>

</body>
</html>