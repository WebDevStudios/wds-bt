# Scripts Directory

This directory contains utility scripts for the WDS Block Theme project.

## update-cursorrules.sh

This script automatically extracts the PHP version from `composer.json` and updates the Cursor rules files in the `.cursor/rules/` directory to ensure Cursor uses the correct PHP version for code analysis and suggestions.

### Features

- **Automatic PHP Version Detection**: Uses `jq` to parse `composer.json` and extract the PHP version
- **Multiple Version Sources**: Checks multiple locations in composer.json:
  - `config.platform.php` (preferred)
  - `require-dev.php`
  - `require.php`
- **Version String Cleaning**: Removes version constraints (>=, ^, ~, etc.) to get the base version
- **Modern Cursor Rules Structure**: Creates separate `.mdc` files in `.cursor/rules/` directory:
  - `dependency-management.mdc` - PHP version and package management
  - `development-workflow.mdc` - Project structure and workflow
  - `javascript-standards.mdc` - Frontend development standards
  - `php-code-standards.mdc` - Comprehensive PHP coding standards
  - `theme-development.mdc` - WordPress theme development guidelines

### Prerequisites

- `jq` must be installed on your system
  - macOS: `brew install jq`
  - Ubuntu/Debian: `sudo apt-get install jq`
  - Windows: Download from https://stedolan.github.io/jq/download/

### Usage

#### Manual Execution
```bash
./scripts/update-cursorrules.sh
```

#### Via NPM Script
```bash
npm run update-cursorrules
```

#### Automatic Execution
The script runs automatically after:
- `npm install` (via postinstall script)
- `composer install` (via post-install-cmd script)

### Output

The script creates/updates files in `.cursor/rules/` directory:

```
.cursor/rules/
├── dependency-management.mdc    # PHP version and package management
├── development-workflow.mdc     # Project structure and workflow
├── javascript-standards.mdc     # Frontend development standards
├── php-code-standards.mdc       # Comprehensive PHP coding standards
└── theme-development.mdc        # WordPress theme development guidelines
```

Each file contains specific guidelines for different aspects of development:

#### dependency-management.mdc
- PHP version configuration (automatically updated)
- Package management guidelines
- Development environment setup
- Build process instructions

#### development-workflow.mdc
- Project structure overview
- Code standards and quality assurance
- Testing and error handling
- Performance optimization

#### javascript-standards.mdc
- Modern JavaScript (ES6+) standards
- Block development guidelines
- Code style and documentation
- Testing and build processes

#### php-code-standards.mdc
- WordPress PHP Coding Standards (WPCS)
- Code style and naming conventions
- Security and performance guidelines
- Testing and error handling
- Database best practices

#### theme-development.mdc
- WordPress block theme standards
- Theme structure and setup
- Block patterns and templates
- Internationalization and accessibility
- Performance optimization

### Error Handling

The script includes comprehensive error handling:
- Checks for `jq` installation
- Validates `composer.json` exists
- Provides helpful error messages if PHP version cannot be extracted
- Uses colored output for better readability

### Integration

This script is integrated into the project's build process:

1. **NPM Integration**: Added to `package.json` postinstall script
2. **Composer Integration**: Added to `composer.json` post-install-cmd script
3. **Manual Script**: Available as `npm run update-cursorrules`

### Benefits

- **Consistent PHP Version**: Ensures Cursor always uses the correct PHP version
- **Organized Rules**: Separates concerns into focused rule files
- **Automatic Updates**: No manual intervention required
- **Project-Specific Rules**: Tailored to WordPress block theme development
- **Modern PHP Features**: Leverages PHP 8.2+ features in suggestions
- **WordPress Standards**: Includes WordPress-specific coding guidelines
- **Comprehensive Coverage**: Covers all aspects of theme development

## test-update-cursorrules.sh

This script is used to test the extraction and update logic for the Cursor rules automation.
