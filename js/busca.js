<section class="cards-container">
  <?php while($prof = mysqli_fetch_assoc($resultado)) { ?>
    <div class="card">
      <div class="card-img">
        <img src="<?= $prof['imagem'] ?>" alt="<?= $prof['nome'] ?>">
        <span class="heart">🤍</span>
      </div>
      <div class="card-info">
        <h3><?= $prof['nome'] ?></h3>
        <p><?= $prof['cidade'] ?>, <?= $prof['estado'] ?></p>
        <div class="stars"><?= $prof['avaliacao'] ?></div>
      </div>
    </div>
  <?php } ?>
</section>