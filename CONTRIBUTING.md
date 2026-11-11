# Contributing to Vertex

Thank you for considering a contribution to Vertex. Contributions should improve the framework while keeping its API, WordPress compatibility and maintenance requirements clear.

## Before You Start

- Read the [README](README.md) to understand the framework's purpose, requirements and structure.
- Search existing issues and pull requests before opening a new one.
- For substantial changes, open an issue first to discuss the problem and proposed approach. This helps avoid work that conflicts with the project's direction.
- Keep changes focused. Avoid unrelated refactoring in the same pull request.

## Development Setup

Vertex requires PHP 8.0 or later and WordPress 6.3 or later for runtime use.

Clone the repository and run the test suite with a compatible PHPUnit PHAR:

```bash
php phpunit.phar --configuration phpunit.xml
```

The PHPUnit version must be compatible with the PHP version being used. The GitHub Actions workflow documents the supported PHP and PHPUnit matrix.

## Coding Guidelines

- Follow the existing code style and naming conventions.
- Use British English in documentation, comments and user-facing technical text.
- Keep framework code independent of product-specific behaviour.
- Preserve the `Vertex\\` namespace and the existing directory-to-namespace mapping.
- Maintain compatibility with the documented PHP and WordPress requirements.
- Validate and sanitise data at the appropriate boundary, and escape output when it is rendered.
- Prefer small, focused changes over introducing unnecessary abstractions or dependencies.
- Document public API changes and update relevant tests.

## Tests

- Add or update tests for behavioural changes and bug fixes.
- Run the relevant tests before submitting a pull request.
- Do not remove or weaken existing tests merely to make a change pass.
- If a change cannot be tested automatically, explain why and describe the manual verification performed.

## Pull Requests

A pull request should:

1. Explain the problem and the reason for the change.
2. Describe the implementation and any relevant design decisions.
3. List the tests and checks that were run, including any that could not be run.
4. Include documentation updates for changes to public behaviour or APIs.
5. Reference related issues where applicable.

Keep pull requests focused and ready for review. Maintainers may request changes, ask for additional tests or close proposals that do not fit the project's scope.

## Reporting Bugs and Suggesting Features

Use the repository's issue templates where possible. Include a minimal reproduction for bugs, along with the PHP and WordPress versions and any relevant error messages. Do not include credentials, personal data or production secrets in issues.

For security vulnerabilities, follow [SECURITY.md](SECURITY.md) instead of opening a public issue.
