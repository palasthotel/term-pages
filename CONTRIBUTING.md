# Contributing

## Branching

`main` is the default branch and always reflects what is released (or about to
be released). Work on a feature branch and open a pull request against `main`.

## Commit messages

Releases and the changelog are generated from the commit history, so commit
messages follow [Conventional Commits](https://www.conventionalcommits.org/):

```
<type>[optional scope][!]: <description>

[optional body]

[optional footer]
```

| Type | Effect on the version | Appears in changelog |
|---|---|---|
| `fix:` | patch (1.0.3 → 1.0.4) | yes, "Bug Fixes" |
| `feat:` | minor (1.0.3 → 1.1.0) | yes, "Features" |
| `feat!:` or `BREAKING CHANGE:` footer | major (1.0.3 → 2.0.0) | yes, highlighted |
| `docs:`, `refactor:`, `chore:`, `deps:`, `style:`, `test:`, `ci:` | none | no |

Examples:

```
fix: keep pagination working for custom taxonomies
feat: allow overriding archives per language
feat!: drop support for PHP 7.4

BREAKING CHANGE: requires PHP 8.0 or newer
```

A pull request that should trigger a release needs at least one `fix:` or
`feat:` commit. When squash-merging, make sure the squash commit message itself
is a conventional commit — that is the message release-please reads.

## Versions

Never edit version numbers by hand. `version.txt`, `CHANGELOG.md`,
`term-pages.php` and the `Stable tag:` in `readme.txt` are all maintained by the
release pipeline — see [.github/WORKFLOWS.md](.github/WORKFLOWS.md).

Content changes to `readme.txt` (description, FAQ, screenshots, tested-up-to)
are of course done by hand; just leave `Stable tag:` and the `== Changelog ==`
entries alone.

## Checks

Every PR runs `php -l` against PHP 7.4, 8.2, 8.3 and 8.4. The plugin declares
`Requires PHP: 7.4` and `Requires at least: 6.2` (WordPress), so avoid syntax
and APIs newer than that unless you raise the requirement in `term-pages.php`
and `readme.txt` in the same PR.
