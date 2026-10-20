# Laravel – Autó nyilvántartó + Irányítószám REST API

A projekt két részből áll:

1. **Webes felület** (Blade + Bootstrap): autógyártók és autómodellek CRUD-ja – `/manufacturers`, `/car-models`.
2. **REST API** a korábbi irányítószámos feladathoz: **megyék** (szülő) – **irányítószámok/települések** (gyerek), keresés, megyék szerinti szűrés, tokenes (Laravel Sanctum) hitelesítés az adatmódosításokhoz.

## Telepítés

```
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

A `.env`-ben alapból MySQL (`laravel_autok` adatbázis, XAMPP) van beállítva, lásd `XAMPP-INDITAS.txt`.
Az API-hoz a `php artisan migrate --seed` kell (a `database.sql` import csak az autós táblákat hozza létre).

A seeder létrehozza:

- a teszt felhasználót: **admin@example.com / password**
- a 20 megyét (Budapesttel együtt) és ~150 irányítószámot a `database/data/iranyitoszamok.csv` fájlból
  (`irsz;telepules;megye` formátum – a teljes országos lista is betölthető, ha ugyanilyen formátumban lecseréled).

API alap URL: `http://localhost:8000/api` (`php artisan serve`), vagy XAMPP alatt `http://localhost/laravel-autok/public/api`.

## Hitelesítés

1. `POST /api/login` `{"email": "admin@example.com", "password": "password"}` → a válaszban `token`.
2. Az adatmódosító kérésekhez fejléc: `Authorization: Bearer <token>`.
3. Minden kéréshez ajánlott: `Accept: application/json`.

Token nélkül a védett végpontok `401 Unauthenticated` választ adnak; hibás adatnál `422`, nem létező rekordnál `404` jön (JSON-ban).

## Végpontok

| Metódus | Végpont | Leírás | Token |
|---|---|---|:---:|
| POST | `/api/register` | Regisztráció (`name`, `email`, `password`, `password_confirmation`) → token | – |
| POST | `/api/login` | Bejelentkezés (`email`, `password`) → token | – |
| GET | `/api/user` | A bejelentkezett felhasználó adatai | ✔ |
| POST | `/api/logout` | Kijelentkezés (a token visszavonása) | ✔ |
| GET | `/api/counties` | Megyék listája irányítószám-darabszámmal; `?search=` a névre | – |
| GET | `/api/counties/{id}` | Egy megye | – |
| GET | `/api/counties/{id}/postal-codes` | Egy megye összes irányítószáma (megye szerinti szűrés) | – |
| POST | `/api/counties` | Új megye (`name`) | ✔ |
| PUT | `/api/counties/{id}` | Megye átnevezése (`name`) | ✔ |
| DELETE | `/api/counties/{id}` | Megye törlése (az irányítószámaival együtt) | ✔ |
| GET | `/api/postal-codes` | Irányítószámok lapozott listája – keresés és szűrés, lásd lent | – |
| GET | `/api/postal-codes/{id}` | Egy irányítószám a megyéjével | – |
| POST | `/api/postal-codes` | Új irányítószám (`postal_code`, `city`, `county_id`) | ✔ |
| PUT | `/api/postal-codes/{id}` | Irányítószám módosítása (`postal_code`, `city`, `county_id`) | ✔ |
| DELETE | `/api/postal-codes/{id}` | Irányítószám törlése | ✔ |

### Keresés, szűrés – `GET /api/postal-codes` paraméterei

| Paraméter | Példa | Jelentés |
|---|---|---|
| `search` | `?search=debr`, `?search=60` | Település nevének részlete **vagy** az irányítószám eleje |
| `county_id` | `?county_id=7` | Szűrés megye azonosítóra |
| `county` | `?county=Pest` | Szűrés megyenévre (pontos név) |
| `sort` | `?sort=city` | Rendezés: `postal_code` (alap) vagy `city` |
| `direction` | `?direction=desc` | `asc` (alap) vagy `desc` |
| `per_page`, `page` | `?per_page=50&page=2` | Lapozás (1–100, alap 20) |

A paraméterek kombinálhatók, pl. `/api/postal-codes?county=Pest&search=gö`.

Példa válasz:

```json
{
  "data": [
    { "id": 65, "postal_code": "4024", "city": "Debrecen", "county": { "id": 9, "name": "Hajdú-Bihar" } }
  ],
  "links": { "first": "...", "last": "...", "prev": null, "next": null },
  "meta": { "current_page": 1, "per_page": 20, "total": 2, "last_page": 1 }
}
```

## Tesztelés Bruno-val

A `bruno/` mappa egy kész Bruno kollekció (23 kérés, mindegyikhez teszt):

1. Bruno → *Open Collection* → válaszd ki a `bruno` mappát.
2. Jobb felül válaszd a **Local** (`php artisan serve`) vagy **XAMPP** környezetet.
3. Futtasd először a *01 Bejelentkezes* kérést – ez automatikusan elmenti a tokent a `token` változóba, a többi kérés ezt használja.
4. Vagy a teljes kollekció futtatása (*Run*) sorban végigmegy: listázás, keresés, szűrés, 401 token nélkül, létrehozás, 422 hibás adat, módosítás, törlés, 404, kijelentkezés.

Parancssorból (friss `migrate:fresh --seed` után):

```
cd bruno
npx @usebruno/cli run --env Local
```

## APIDOC dokumentáció

A dokumentáció a controllerekben lévő [apiDoc](https://apidocjs.com/) kommentekből készül (`app/Http/Controllers/Api`), a generált HTML a `public/apidoc` mappában van:

- `php artisan serve` mellett: <http://localhost:8000/apidoc/index.html>
- XAMPP alatt: <http://localhost/laravel-autok/public/apidoc/index.html>

Újragenerálás a kommentek módosítása után:

```
npm install
npm run apidoc
```

## Fájlok

| Fájl | Tartalom |
|---|---|
| `routes/api.php` | API útvonalak (`auth:sanctum` csoport a módosításokhoz) |
| `app/Http/Controllers/Api/*` | Auth, County, PostalCode controllerek + apiDoc kommentek |
| `app/Http/Resources/*` | JSON válaszok formája |
| `app/Models/County.php`, `PostalCode.php`, `User.php` | Modellek (County 1 – N PostalCode) |
| `database/migrations/*` | `users`, `personal_access_tokens`, `counties`, `postal_codes` táblák |
| `database/seeders/PostalCodeSeeder.php` | CSV betöltés |
| `bruno/` | Bruno kollekció |
| `public/apidoc/` | Generált APIDOC |
