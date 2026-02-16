# Version Management

The theme uses `updateVersion.js` to keep version consistent across `style.css`, `package.json`, `composer.json`, and `README.md`.

## How to Update

**Recommended:** Set version in `.env`, then run:

```bash
echo "VERSION=1.4.0" > .env
npm run version-update
```

Other options: `VERSION=1.4.0 npm run version-update` or `npx dotenv -e .env -- npm run version-update`.

## What Gets Updated

- `style.css` (theme header)
- `package.json`
- `composer.json`
- `README.md` (version line)

## Release Workflows

- **Patch (e.g. 1.4.0 → 1.4.1):** Create `patch/1.4.1` (or `hotfix/1.4.1`), make fixes, set `VERSION=1.4.1` in `.env`, run `npm run version-update`, commit, push, open PR, then tag after merge.
- **Minor (e.g. 1.4.0 → 1.5.0):** Create `feature/1.5.0`, add features, update version, update CHANGELOG if used, commit, push, PR, tag.
- **Major (e.g. 1.4.0 → 2.0.0):** Create `release/2.0.0`, implement breaking changes, update docs, run `npm run build`, `npm run lint`, `npm run a11y`, then version bump and PR/tag.

## Pre-release

```bash
echo "VERSION=1.4.0-beta.1" > .env
npm run version-update
```

(Same for `-alpha.1`, `-rc.1`, etc.)

## Troubleshooting

- **Version not updating:** Ensure `.env` exists with `VERSION` (e.g. `1.4.0`, not `v1.4.0`). Run `npm run version-update` and check for errors.
- **Permission errors:** Check write permissions and that files aren’t locked.
- **Script not found:** Ensure `updateVersion.js` exists and Node is installed; run `npm install` if needed.
