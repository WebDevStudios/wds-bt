# How To Contribute

WebDevStudios welcomes contributions and bug fixes from third-parties. Here are the steps to get started:

- Create an [Issue](https://github.com/WebDevStudios/wds-bt/issues) so we can all discuss your idea
- Fork wds-bt
- Create a feature/hotfix branch off [main](https://github.com/WebDevStudios/wds-bt/tree/main)
- Commit code changes to your feature/hotifx branch
- Continue to merge master into your feature/hotifx branch so it stays current
- Test across all major browsers
- Accessibility testing (both WCAG 2.2AA and Section 508)
- Must pass PHPCS, ESLint, and Stylelint assertions (strict mode: latest WordPress standards, warnings count as failures)
- Do not use `git commit --no-verify` or `git push --no-verify`; CI will run the same checks and reject PRs that do not pass
- Submit a [Pull Request](https://github.com/WebDevStudios/wds-bt/pulls) and reference your Issue #
- If everything tests well on our end, we may merge in your Pull Request
