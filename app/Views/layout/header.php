<?php 
$notifModel = new \App\Models\NotifikasiModel();
$unread = $notifModel->getUnread(session()->get('id_user')); 
$count = count($unread);
?>

<div class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        🔔 Notifikasi (<?= $count ?>)
    </a>
    <ul class="dropdown-menu">
        <?php foreach ($unread as $n): ?>
            <li><a href="<?= $n['link'] ?>"><?= $n['pesan'] ?></a></li>
        <?php endforeach; ?>
        <?php if($count == 0): ?>
            <li><a href="#">Tidak ada pesan baru</a></li>
        <?php endif; ?>
    </ul>
</div>