# Security Policy

## Supported Versions

Currently, the following versions of the GitHub Maintainer Hub are supported with security updates:

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |

## Reporting a Vulnerability

If you discover a security vulnerability within the GitHub Maintainer Hub, please send an e-mail to Otávio Belchior via [your-email@example.com](mailto:your-email@example.com). All security vulnerabilities will be promptly addressed.

We kindly ask you not to disclose the vulnerability publicly until it has been resolved.

## Branch Protection Rules (For Contributors)

To maintain a secure and stable codebase, this repository should enforce Branch Protection Rules on the `main` or `master` branch. 

If you are setting up your own instance or managing this repository on GitHub:
1. Go to your repository settings -> **Branches**.
2. Add a branch protection rule for `main`.
3. Check **Require a pull request before merging**.
4. Check **Require approvals** (set to at least 1, but for personal projects, you can restrict who can push).
5. Check **Do not allow bypassing the above settings**.
6. Check **Restrict who can push to matching branches** -> Select only yourself or trusted maintainers.

This ensures no one can directly push to `main` without review or without being authorized.
