# Maison De Parfum

Maison De Parfum është një aplikacion web i ndërtuar me PHP dhe MySQL që simulon një dyqan online parfumesh luksoze. Përdoruesit mund të regjistrohen, shfletojnë produkte, t'i filtrojnë dhe rendisin ato, të shtojnë në shportë dhe të kryejnë porosi. Projekti përfshin gjithashtu një panel administrativ të plotë.

## Përshkrimi i Funksionaliteteve

Regjistrim dhe Login — Përdoruesi regjistrohet me username, email dhe fjalëkalim. Pas regjistrimit dërgohet një kod 6-shifror verifikimi në email përmes PHPMailer. Vetëm pas verifikimit të emailit, përdoruesi mund të kyçet. Fjalëkalimi ruhet i hashuar me password_hash(). Sistemi ka mbrojtje CSRF dhe rate limiting për të parandaluar sulmet brute-force.

Produktet dhe Shporta — Faqja e produkteve shfaq të gjitha parfumet me mundësi filtrimi sipas kategorisë (Men, Women, Unisex) dhe renditjeje sipas çmimit. Çdo produkt shfaq çmimin edhe në EUR përmes Exchange Rate API. Shtimi dhe heqja e produkteve nga shporta kryhet me AJAX pa refresh të faqes. Totali i shportës përditësohet në kohë reale.

Checkout dhe Porositë — Pas zgjedhjes së produkteve, përdoruesi plotëson formularin e blerjes me email, telefon dhe adresë. Sistemi validon të dhënat dhe ruan porosinë në databazë me try/catch për error handling. Historia e porosive shfaqet te profili i përdoruesit.

Profili — Çdo përdorues ka faqe personale ku mund të ngarkojë, ndryshojë ose fshijë foton e profilit. Gjithashtu mund të ndryshojë fjalëkalimin dhe të shohë historinë e porosive dhe feedback-et e dhëna.

Contact — Faqja e kontaktit lejon përdoruesit e kyçur të dërgojnë mesazhe direkt te emaili i dyqanit përmes PHPMailer. Formulari ka validim të plotë dhe error handling me try/catch.

Panel Admin — Administratori kyçet përmes një faqeje të veçantë me kod shtesë sigurie. Ka qasje në dashboard me statistika (total users, orders, revenue, feedbacks), mund të shtojë, ndryshojë dhe fshijë produkte, si dhe të menaxhojë statusin e porosive.

## Teknologjitë

PHP 8+, MySQL, HTML5, CSS3, JavaScript, AJAX, PHPMailer, Leaflet.js, Exchange Rate API, Git / GitHub

## Struktura e Projektit

index.php, login.php, register.php, verify-register.php, admin-login.php, logout.php

pages/
    products.php, add-product.php, edit-product.php, checkout.php,
    process_order.php, orders.php, profile.php, contact.php,
    admin-dashboard.php, about.php, visitus.php, save-feedback.php

includes/
    db.php, config.php, header.php, navbar.php, footer.php,
    security.php, send_verification_email.php

ajax/
    cart_action.php, search_products.php, filter_top_picks.php, delete_product.php

classes/
    Products.php, Perfume.php

assets/
    css/, js/, images/

## Instalimi

1. Klono projektin dhe vendose në C:/xampp/htdocs/PerfumeStore_20
2. Krijo databazën perfume_store në phpMyAdmin
3. Importo skedarin perfume_store.sql
4. Ekzekuto: ALTER TABLE users ADD COLUMN profile_image VARCHAR(255) DEFAULT NULL;
5. Ndrysho kredencialet në includes/db.php
6. Ndrysho SMTP në includes/send_verification_email.php me emailin tënd Gmail
7. Hap: localhost/PerfumeStore_20

## Kredencialet për Testim

Admin:
- URL: localhost/PerfumeStore_20/admin-login.php
