# uniCenta / POS schema

The production database is primarily defined by the live MariaDB instance (see SQL dumps under `../dump/`). Laravel migrations in this folder cover only app-specific tables (users, product details, etc.).

To generate baseline migrations from an imported dump, use a schema exporter (e.g. `kitloong/laravel-migrations-generator`) against a **sanitized** copy of the database, then:

- Remove cross-schema FKs like `` REFERENCES `pos3`.`attributeset` `` (use same-database references).
- Prefer `ROW_FORMAT=DYNAMIC` for wide InnoDB tables to avoid error 1118.
