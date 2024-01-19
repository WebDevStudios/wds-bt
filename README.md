# WDS BT

[![WebDevStudios. Your Success is Our Mission.](https://webdevstudios.com/wp-content/uploads/2018/04/wds-github-banner.png)](https://webdevstudios.com/contact/)

Meet WDS BT, a stylish block theme drawing inspiration from the Powder Theme. With a modern design, user-friendly interface, and smooth integration with the WordPress block editor, WDS BT delivers a clean and responsive layout. It's important to note that this theme is intentionally designed as a base theme, not a parent theme. This distinction offers users a customizable foundation to build upon. Elevate your website with WDS BT, where design effortlessly meets functionality, providing the ideal canvas for your creative expression.

## Requirements

- WordPress 6.4+
- PHP 8.2+
- NPM 10.1.0+
- Node 20
- License: [GPLv3](https://www.gnu.org/licenses/gpl-3.0.html)

## Getting Started

1. Set up a local WordPress development environment, we recommend using [Local](https://localwp.com/).
2. Ensure you are using WordPress 6.4+.
3. Clone / download this repository into the `/wp-content/themes/` directory of your new WordPress instance.
4. In the WordPress admin, use the Appearance > Themes screen to activate the theme.

## Syncing with upstream repo

### Configure a remote repository for the fork

1. Open Terminal.
2. Specify a new remote upstream repository that will be synced with the fork.

``` CODE
git remote add upstream https://github.com/bgardner/powder
```

### Getting Upstream changes

1. Change the current working directory to your local project.
2. Fetch the branches and their respective commits from the upstream repository. Commits to `main` will be stored in the local branch `upstream/main`.
3. Check out the fork's local default branch `main`.
4. Merge the changes from the upstream default branch - in this case, `upstream/main` - into your local default branch.

``` CODE
git fetch upstream
git checkout main
git merge upstream/main
```

### Theme Unit Test

1. Download the theme test data from <https://github.com/WebDevStudios/wds-bt/blob/main/wdsunittestdata.wordpress.xml>
2. Import test data into your WordPress install by going to Tools => Import => WordPress
3. Select the XML file from your computer
4. Click on “Upload file and import”.
5. Under “Import Attachments,” check the “Download and import file attachments” box and click submit.

*Note: You may have to repeat the Import step until you see “All Done” to obtain the full list of Posts and Media.*
