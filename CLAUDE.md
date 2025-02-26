# Cardano Mercury Invoice - Guide for Agents

## Build & Dev Commands
- Build project: `make build`
- Start services: `make up`
- Frontend dev mode: `make frontend-watch`
- Access container shell: `make shell`

## Lint & Test Commands
- Run all tests: `make artisan COMMAND="test"`
- Run single test: `make artisan COMMAND="test tests/Path/To/TestFile.php"`
- Run tests by suite: `make artisan COMMAND="test --testsuite=Feature"`
- Lint PHP code: `make artisan COMMAND="pint"`
- Lint specific file: `make artisan COMMAND="pint path/to/file.php"`

## Code Style Guidelines
- Backend: PHP 8.2+ with Laravel 11 & PSR-4 autoloading
- Frontend: Vue 3 + Inertia.js + Vuetify (Composition API with script setup)
- Database: Eloquent ORM with migrations in `application/database/migrations` & models in `application/app/Models`
- Naming: PascalCase for classes, camelCase for methods/properties
- Error handling: Use try/catch with logging & use Laravel's built-in validation
- Imports: Group by type (PHP core, framework, local) and sort alphabetically
