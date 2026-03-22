# Dodatno korisno

## Elementor CSS Regenerator

**Repozitorijum:** https://github.com/robwent/elementor-css-regenerator

### Šta radi

Kad Elementor obriše svoje generisane CSS fajlove (npr. nakon update-a ili klika na "Regenerate CSS & Data"), ti fajlovi se ne regenerišu odmah — nego tek kad neko posjeti stranicu. Ako u međuvremenu keš plugin (LiteSpeed Cache, W3 Total Cache, WP Rocket) kešira stranicu, HTML će sadržavati `<link>` tagove koji pokazuju na CSS fajlove koji više ne postoje. Rezultat: 404 greška za CSS i stranica bez stilova.

Ovaj plugin presreće 404 odgovore za putanje koje sadrže `/elementor/css/`, pronalazi odgovarajući post ID, regeneriše CSS fajl u realnom vremenu i vraća ga browseru sa 200 statusom. Sljedeći posjetilac već dobija keširanu verziju sa ispravnim CSS-om.

### Kako se instalira

1. Upload-uj `elementor-css-regenerator.zip` kroz **WordPress Admin → Plugins → Add New → Upload Plugin**
2. Aktiviraj plugin

Plugin radi u pozadini bez ikakvih podešavanja.

### Kako se koristi zajedno sa Fix Elementor CSS Race Condition

Ova dva plugina rješavaju različite probleme i potpuno su kompatibilna:

| Plugin | Problem koji rješava |
|--------|---------------------|
| **Fix Elementor CSS Race Condition** (mu-plugin) | `<link>` tag nedostaje iz HTML-a jer object cache vraća zastarjele podatke |
| **Elementor CSS Regenerator** | CSS fajl ne postoji na disku (404) jer ga Elementor nije regenerisao na vrijeme |

Preporuka je koristiti oba zajedno za potpunu zaštitu od Elementor CSS problema.
