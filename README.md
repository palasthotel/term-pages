# WordPress Term Pages

WordPress Plugin for overwriting the first page of a term archive with a single page.

If you want to customize your taxonomy archive pages, create a page and connect
it with the term you want to overwrite. Visitors hitting the term archive get a
301 redirect to that page — paginated archive pages (`/page/2`) are untouched.

- **WordPress.org:** https://wordpress.org/plugins/term-pages/
- **User documentation:** [readme.txt](readme.txt) (the text shown on WordPress.org)
- **Changelog:** [CHANGELOG.md](CHANGELOG.md) — release-please owns that file, so do
  not add notes to it by hand. Entries before 2.0.0 are in the `== Changelog ==`
  section of [readme.txt](readme.txt).

## Installation

Install *Term Pages* from the WordPress plugin directory, or download
`term-pages.zip` from the [latest release](https://github.com/palasthotel/term-pages/releases/latest)
and extract it into `wp-content/plugins/`.

## Usage

1. Create and publish the page that should replace the archive.
2. Edit the term (category, tag or any custom taxonomy term).
3. Type the page title into the **overriding page** field and pick it from the
   autocomplete suggestions.
4. Save the term.

## Repository layout

| Path | Description |
|---|---|
| `term-pages.php` | the plugin |
| `admin.js` | page autocomplete on the term screens |
| `languages/` | translations (`de_DE` + `.pot`) |
| `readme.txt` | WordPress.org plugin page |
| `LICENSE` | GPL-3.0 text, shipped with the plugin |
| `bin/` | release helper scripts |
| `.github/workflows/` | CI/CD — see [.github/WORKFLOWS.md](.github/WORKFLOWS.md) |

Only the files listed in `PLUGIN_FILES` in [`bin/pack.sh`](bin/pack.sh) are
shipped to WordPress.org. Everything else stays GitHub-only.

## Releasing

Releases are automated with [release-please](https://github.com/googleapis/release-please)
and deployed to the WordPress.org SVN repository. There is nothing to bump by
hand — commit with [conventional commits](https://www.conventionalcommits.org/)
and merge the release PR:

```
fix: …   → patch    feat: …  → minor    feat!: … → major
```

```
merge PR to main → release-please opens "chore(main): release x.y.z"
                 → merge it → tag vx.y.z → deploy to WordPress.org
```

The full pipeline, including the required secrets, is documented in
[.github/WORKFLOWS.md](.github/WORKFLOWS.md). See [CONTRIBUTING.md](CONTRIBUTING.md)
for the commit conventions.

## Building locally

```sh
bash bin/pack.sh    # → term-pages.zip + build/term-pages/
```

## License

GNU General Public License v3.0 or later — see [LICENSE](LICENSE).
