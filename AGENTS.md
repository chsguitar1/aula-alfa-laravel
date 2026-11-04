# Project Agent Guide

This is a Laravel 13 application using PHP `^8.3` (the current workspace uses PHP 8.4). Laravel Boost is already installed; do not reinstall it or change dependencies unless the user asks.

## Before editing

- Read the nearest sibling implementation and the relevant test before introducing a new pattern.
- Check `composer.json` and `package.json` when relying on framework or package APIs; do not assume versions.
- Keep changes within the existing `app/`, `database/`, `resources/`, `routes/`, and `tests/` structure.
- No `.ai/rules` directory currently exists. Do not require or create one unless the project adopts shared rules.

## Current structure

- Controllers: `app/Http/Controllers`; models: `app/Models`; providers: `app/Providers`.
- Web routes are defined directly in `routes/web.php`.
- Blade views live under `resources/views`, including `layouts/` and `user/`.
- Vite entrypoints are `resources/scss/app.scss` and `resources/js/app.js`; frontend dependencies include Bootstrap, AdminLTE, OverlayScrollbars, Sass, and Instrument Sans.
- Existing controllers use direct Eloquent queries. Form Requests, services, repositories, route naming, and resource controllers are not established conventions yet; follow nearby code before introducing them.
- Existing domain models and migrations need verification before changes. In particular, `Profile` currently follows Eloquent's `profiles` table convention while its migration creates `profile`.

## Verification

- Setup: `composer run setup`
- Development: `composer run dev` or `npm run dev` as appropriate. Laravel Herd serves the app; do not start another PHP server.
- Frontend build: `npm run build`
- Tests: `php artisan test --compact` or the narrowest relevant test file. Tests use Pest.
- PHP formatting after PHP edits: `vendor/bin/pint --dirty --format agent`

Prefer feature tests for HTTP behavior and existing factories for test data. Do not delete tests or alter dependencies without approval. Keep replies concise and update documentation only when requested or required by the change.

## Project skills

Use the relevant existing skill under `.claude/skills/` for Laravel best practices, testing, or convention discovery. These skills are available on demand; there are no project-specific Copilot instruction, agent, prompt, or hook files beyond this guide and `CLAUDE.md`.
