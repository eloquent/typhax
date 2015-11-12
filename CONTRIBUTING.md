# Contributing

**Typhax** is open source software; contributions from the community are
encouraged. Please take a moment to read these guidelines before submitting
changes.

## Code style

All PHP code must adhere to the [PSR-2] standards.

[psr-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md

## Branching and pull requests

As a guideline, please follow this process:

1. [Fork the repository].
2. Create a topic branch for the change, branching from **develop**
(`git checkout -b branch-name develop`).
3. Make the relevant changes.
4. [Squash] commits if necessary (`git rebase -i develop`).
5. Submit a pull request to the **develop** branch.

[fork the repository]: https://help.github.com/articles/fork-a-repo
[squash]: http://git-scm.com/book/en/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages

## Tests

- Run the tests with `vendor/bin/archer t path/to/tests`, or simply
  `vendor/bin/archer` to run the entire suite.
- Generate a coverage report with `vendor/bin/archer c path/to/tests`, or simply
  `vendor/bin/archer c` to generate coverage for the entire suite. The coverage
  report will be created at `artifacts/tests/coverage/index.html`.
