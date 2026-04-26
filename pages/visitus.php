<?php
include '../includes/header.php';
include '../includes/navbar.php';
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="/PERFUMESTORE_20/assets/css/visitUs.css">

<div class="visit-showroom">
  <main class="visit-grid">
    <section class="visit-card">
      <h2>Visit Our Showroom</h2>

      <p>
        <strong>Address:</strong> Tirana East Gate (TEG)<br>
        Tirana-Elbasan Highway
      </p>

      <div class="visit-details">
        <div class="visit-detail">
          <strong>Working Hours</strong>
          Everyday 10:00 – 20:00
        </div>

        <div class="visit-detail">
          <strong>Phone</strong>
          +355 69 253 6666
        </div>
      </div>

      <div class="visit-cta">
        <a class="visit-btn"
           href="https://www.google.com/maps/dir/?api=1&destination=Tirana+East+Gate+TEG"
           target="_blank"
           rel="noopener">
          Navigate
        </a>
      </div>

      <div id="map" class="visit-map"></div>
    </section>
  </main>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="../assets/js/visitUs.js"></script>

<?php
include '../includes/footer.php';
?>