# Irányítószám REST API

| Metódus | Végpont | Leírás | Token |
|---|---|---|:---:|
| POST | `/api/login` | Bejelentkezés, token kérése | – |
| GET | `/api/counties` | Megyék listája | – |
| GET | `/api/postal-codes` | Irányítószámok listája (`?search=`, `?county_id=`) | – |
| GET | `/api/postal-codes/{id}` | Egy irányítószám | – |
| POST | `/api/postal-codes` | Új irányítószám | ✔ |
| PUT | `/api/postal-codes/{id}` | Irányítószám módosítása | ✔ |
| DELETE | `/api/postal-codes/{id}` | Irányítószám törlése | ✔ |
