Maison De Parfum – Aplikacion Web për Dyqan Parfumesh
Përshkrimi

Maison De Parfum është një aplikacion web i ndërtuar me PHP që simulon një dyqan online parfumesh. Përdoruesit mund të shfletojnë produkte, t’i filtrojnë dhe rendisin ato, si dhe të kryejnë porosi të thjeshta.

Ky projekt demonstron konceptet bazë të zhvillimit web:

PHP (logjika në server)
HTML/CSS (dizajni)
JavaScript (interaktiviteti)
Session & Cookies

Struktura e Projektit:
index.php
login.php
logout.php
pages/
    products.php
    about.php
    visitUs.php
    orders.php
includes/
    header.php
    navbar.php
    footer.php
    config.php
assets/
    css/
    js/
    images/

Funksionalitetet
Homepage (index.php) - Faqja kryesore e aplikacionit, përfshin navigimin dhe strukturën bazë, mbështet light mode dhe dark mode

Produktet (products.php) - Shfaq të gjitha produktet në mënyrë dinamike, filtrim sipas kategorisë: Meshkuj; Femra; Unisex. Renditje sipas çmimit: Në rritje; Në zbritje 
Karakteristika:
Kartela produktesh,
Badge “PREMIUM” për produkte të shtrenjta,
Kontroll i sasisë (+ / -),
Llogaritje e totalit në kohë reale (JavaScript)

Login (login.php) - Autentikim i thjeshtë me përdorues të paracaktuar, ruajtje e të dhënave në session: username; role (admin/user). Mesazh gabimi në rast të kredencialeve të pasakta.
User:
Mund të shfletojë produktet,
Mund të zgjedhë sasinë dhe të bëjë porosi,
Nuk ka qasje në faqen Orders
Admin:
Ka qasje në të gjitha faqet,
Mund të shohë faqen Orders,
Nuk kryen blerje (nuk shfaqet forma e porosisë)

About Us (about.php) - Informacion për biznesin. Përmban: Misionin, arsye pse të zgjidhet kompania, statistika me animacion, shfaq mesazh personal nëse ekziston cookie (email)

Visit Us (visitUs.php) - Shfaq lokacionin e dyqanit. Përfshin: Adresën, Orarin e punës, Numrin e telefonit, Buton për navigim në Google Maps, Hartë interaktive (Leaflet.js)

Logout (logout.php) - Mbyll session-in e përdoruesit, ridrejton në faqen kryesore

Dizajni
Përdoren disa skedarë CSS:
style.css – stili kryesor
about.css – faqja About
login.css – forma e login-it
visitUs.css – faqja e lokacionit

Mbështet:
Light Mode
Dark Mode

Teknologjitë:
PHP
HTML5
CSS3
JavaScript
Leaflet.js

Kredencialet për Testim
Admin:
username: admin
password: 123

Ky eshte linku i videos te projektit https://drive.google.com/file/d/1loIlWBN9lfYDyuU-07l3V5h-gTBlxLCq/view?usp=drive_link

User:
username: user
password: 123
