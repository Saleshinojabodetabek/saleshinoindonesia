<?php
$id = $_GET['id'];
$data = json_decode(file_get_contents("https://saleshinoindonesia.com/admin/api/get_artikel.php"), true);
$artikel = null;

foreach ($data as $item) {
  if ($item['id'] == $id) {
    $artikel = $item;
    break;
  }
}
?>

<section class="content-section">
  <div class="container">
    <?php if($artikel): ?>
      <h1><?= $artikel['judul'] ?></h1>
      <img src="<?= $artikel['gambar'] ?>" alt="<?= $artikel['judul'] ?>" style="width:100%; max-width:800px; border-radius:16px;">
      <p style="margin-top:20px;"><?= nl2br($artikel['isi']) ?></p>
    <?php else: ?>
      <p>Artikel tidak ditemukan.</p>
    <?php endif; ?>
  </div>
</section>
