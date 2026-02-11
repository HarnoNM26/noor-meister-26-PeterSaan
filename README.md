# Peter Saan

## Peamised pakid
- Laravel
  - Iseenesest backendi jaoks, aga praegusel puhul on ta serveriks ka frontendi näitamiseks.
  - Sisseehitatud paljude lahenduste ja seadistustega (nagu ORM, mugavad starter-kitid jne), mis teeb selle algajatele heaks
- Inertia
  - Justkui köis, mis seob backendi ja frontendi omavahel kokku, kuigi nad ei ole mõeldud koos olema
- Vue.js
  - Frontend raamistik, mis võimaldab mugavat komponentide loomist ja reaktiivsust lehel
- TailwindCSS
  - Raamistik CSS'i kirjutamiseks HTML classidesse
- Shadcn
      - Lisand TailwindCSS'ile, et ei peaks ise komponente (nagu nupud, dropdown menüüd jne) ise tegema

## Projekti käivitamine
- Vaata, et PHP, Composer, Node ja Bun (või NPM) oleksid alla laetud
- Kopeeri `.env` fail `.env.example` näitest.
- Loo endale tühi `database.sqlite` fail `/database` kasusta
- Jooksuta migratsioonid:
```bash
php artisan migrate
```
- Lae alla kõik vajalik:
```bash
composer i && bun i # või NPMiga
```
- Ehita frontendi failid
```bash
bun run build # või NPMiga
```
- Jooksuta appi ja naudi:
```bash
composer run dev
```

## Märgatud puudused
- Moodul A andmebaasi tabeli tegemisel ei ole kindel, kas source column peab olema `not null` või `nullable`. Jätsin antud välja hetkel `not null`iks.
