# Frontend Users

a versatile set of tools and objects for dealing with frontend-users

## Installation

```bash
composer require psbits/frontend-users
```

## Development

```bash
composer install
composer qa
```

`composer qa` fixes everything that can be fixed automatically and afterwards runs all linters and the static
analysis. The same checks run in the `CI` GitHub Actions workflow, which also runs the test matrix on the
supported core and PHP versions, and in the `pre-commit` hook that is installed by `composer install`.

The workflow publishes the extension to TER when a release tag is pushed. Publishing requires the
`TYPO3_API_TOKEN` repository secret.
