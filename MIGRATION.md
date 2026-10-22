# Migration de SSTemp vers Laravel 12 et PHP 8.5

Référence initiale : `e9bc40a`. Branche locale : `migration/laravel-12`. Contraintes : Laravel `^12.0` et PHP `~8.5.12`.

## Changements

- Cast de `last_send` et calcul du délai de 30 minutes compatibles Carbon 3.
- Remplacement de Beautymail par le mail natif Laravel, testé avec un transport en mémoire.
- Création des paramètres à la première ouverture ou modification, et champs facultatifs correctement validés.
- Température et statistiques filtrées sur le type MySensors V_TEMP ; les mesures d’humidité restent diffusées sans déclencher une alerte de température.
- Agrégation horaire compatible SQLite et MariaDB et ordre déterministe des dernières mesures.

Les routes utilisent des références de classes explicites. Les migrations historiques restent inchangées. Les permissions au-delà de l’authentification ne font pas l’objet d’un audit complet.

## Tests

**6 tests, 25 assertions**, sous Laravel 12.70.1 et PHP 8.5.12, sur SQLite en mémoire et MariaDB 10.11 isolée. Aucun compte externe, équipement ou serveur de production n’est contacté. Les sources de ce dépôt sont chargées avant les copies de vendor.

Le plugin dépend des classes App de LaraHome : les tests utilisent l’application hôte migrée et son verrou Composer, sans embarquer une seconde installation Laravel. Les anciens paliers du framework ont été testés précédemment dans LaraHome ; cette suite spécifique cible la version finale.

Avec LaraHome migré dans le dossier voisin et ses dépendances installées :

```bash
docker build -f ../LaraHome/tools/migration/Dockerfile.php85 -t larahome-migration:php85 ../LaraHome
bash tools/test --display-deprecations --display-warnings
```

`LARAHOME_PATH` permet de choisir un autre dossier hôte. Le lanceur utilise par défaut SQLite et un conteneur sans réseau. Pour MariaDB, définir `MIGRATION_NETWORK` et les variables `PLUGIN_TEST_DB_CONNECTION`, `PLUGIN_TEST_DB_HOST`, `PLUGIN_TEST_DB_DATABASE`, `PLUGIN_TEST_DB_USERNAME`, `PLUGIN_TEST_DB_PASSWORD` avec une base jetable : RefreshDatabase peut en effacer les tables.

Les tests sur équipements réels et les parcours visuels restent à vérifier en préproduction. Aucun push, tag ni déploiement n’a été effectué. La licence non déclarée du dépôt initial n’a pas été remplacée par une licence supposée.
