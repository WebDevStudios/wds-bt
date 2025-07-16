#!/bin/bash

# Script to extract PHP version from composer.json and update Cursor rules files
# This script is designed to be run as a post-install hook
# Script: update-cursorrules.sh

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if jq is installed
if ! command -v jq &> /dev/null; then
    print_error "jq is not installed. Please install jq to use this script."
    print_status "You can install jq with: brew install jq (macOS) or apt-get install jq (Ubuntu/Debian)"
    exit 1
fi

# Check if composer.json exists
if [ ! -f "composer.json" ]; then
    print_error "composer.json not found in current directory"
    exit 1
fi

# Check if package.json exists
if [ ! -f "package.json" ]; then
    print_warning "package.json not found in current directory"
fi

# Extract PHP version from composer.json
print_status "Extracting PHP version from composer.json..."

# Try to get PHP version from platform config first
PHP_VERSION=$(jq -r '.["config"]["platform"]["php"] // empty' composer.json 2>/dev/null)

# If not found in platform config, try require-dev
if [ -z "$PHP_VERSION" ] || [ "$PHP_VERSION" = "null" ]; then
    PHP_VERSION=$(jq -r '.["require-dev"]["php"] // empty' composer.json 2>/dev/null)
fi

# If still not found, try require
if [ -z "$PHP_VERSION" ] || [ "$PHP_VERSION" = "null" ]; then
    PHP_VERSION=$(jq -r '.["require"]["php"] // empty' composer.json 2>/dev/null)
fi

# Clean up version string (remove >=, ^, ~, etc.)
PHP_VERSION=$(echo "$PHP_VERSION" | sed 's/[^0-9.]//g')

if [ -z "$PHP_VERSION" ] || [ "$PHP_VERSION" = "null" ]; then
    print_error "Could not extract PHP version from composer.json"
    print_status "Available PHP-related fields in composer.json:"
    jq -r 'paths | select(.[-1] == "php") | join(".")' composer.json 2>/dev/null || print_warning "No PHP version found in composer.json"
    exit 1
fi

print_status "Extracted PHP version: $PHP_VERSION"

# Extract additional information from composer.json
print_status "Extracting additional information from composer.json..."

COMPOSER_NAME=$(jq -r '.name // empty' composer.json 2>/dev/null)
COMPOSER_DESCRIPTION=$(jq -r '.description // empty' composer.json 2>/dev/null)
COMPOSER_VERSION=$(jq -r '.version // empty' composer.json 2>/dev/null)
COMPOSER_LICENSE=$(jq -r '.license // empty' composer.json 2>/dev/null)
COMPOSER_TYPE=$(jq -r '.type // empty' composer.json 2>/dev/null)

# Extract Node.js information from package.json if it exists
NODE_VERSION=""
NPM_VERSION=""
PACKAGE_NAME=""
PACKAGE_VERSION=""
PACKAGE_DESCRIPTION=""

if [ -f "package.json" ]; then
    print_status "Extracting information from package.json..."

    NODE_VERSION=$(jq -r '.engines.node // empty' package.json 2>/dev/null)
    NPM_VERSION=$(jq -r '.engines.npm // empty' package.json 2>/dev/null)
    PACKAGE_NAME=$(jq -r '.name // empty' package.json 2>/dev/null)
    PACKAGE_VERSION=$(jq -r '.version // empty' package.json 2>/dev/null)
    PACKAGE_DESCRIPTION=$(jq -r '.description // empty' package.json 2>/dev/null)
fi

# Extract Composer scripts
COMPOSER_SCRIPTS=$(jq -r '.scripts | to_entries[] | "  - " + .key + ": " + (if (.value|type)=="array" then (.value|join(" && ")) else .value end)' composer.json 2>/dev/null)

# Extract npm scripts
NPM_SCRIPTS=""
if [ -f "package.json" ]; then
    NPM_SCRIPTS=$(jq -r '.scripts | to_entries[] | "  - " + .key + ": " + .value' package.json 2>/dev/null)
fi

# Define Cursor rules directory
CURSOR_RULES_DIR=".cursor/rules"

# Create Cursor rules directory if it doesn't exist
if [ ! -d "$CURSOR_RULES_DIR" ]; then
    print_status "Creating Cursor rules directory: $CURSOR_RULES_DIR"
    mkdir -p "$CURSOR_RULES_DIR"
fi

# Update dependency-management.mdc with extracted information
print_status "Updating dependency-management.mdc..."
cat > "$CURSOR_RULES_DIR/dependency-management.mdc" << EOF
# Dependency Management

## PHP Version Configuration
The PHP version below is automatically extracted from composer.json
PHP_VERSION="$PHP_VERSION"

## Project Information
- Project Name: ${COMPOSER_NAME:-"WDS Block Theme (WDS-BT)"}
- Project Description: ${COMPOSER_DESCRIPTION:-"A FSE/Gutenberg block starter theme"}
- Project Type: ${COMPOSER_TYPE:-"wordpress-theme"}
- Project Version: ${COMPOSER_VERSION:-"1.3.0"}
- License: ${COMPOSER_LICENSE:-"GPL-2.0-or-later"}
- PHP Version: $PHP_VERSION (extracted from composer.json)
- Node.js Version: ${NODE_VERSION:-">=20.0.0"} (extracted from package.json)
- npm Version: ${NPM_VERSION:-">=10"} (extracted from package.json)

## Composer (PHP)
- Use Composer 2.x for PHP dependency management
- Lock file (composer.lock) must be committed to version control
- Define specific version constraints for all dependencies
- Use proper autoloading standards
- Follow PSR-4 autoloading for custom namespaces
- Document all custom Composer scripts

## npm (JavaScript)
- Use package.json for JavaScript dependency management
- Lock file (package-lock.json) must be committed
- Define specific version constraints for all dependencies
- Use proper peer dependency management
- Document all custom npm/yarn scripts

## WordPress Core and Plugins
- Use Composer for WordPress core installation
- Define WordPress version constraints in composer.json
- Use proper plugin version constraints
- Document all required and optional plugins
- Use proper plugin dependency management

## Security
- Regularly update dependencies
- Use security scanning tools
- Document known vulnerabilities
- Implement proper version constraints
- Use trusted sources for dependencies

## Build Tools
- Document all build tool requirements
- Use proper version constraints for build tools
- Implement proper build scripts
- Document build process
- Use proper environment variables

## Development Environment
- You are working on a WordPress block theme called "WDS Block Theme", or "WDS-BT"
- The project uses PHP version $PHP_VERSION as specified in composer.json
- Always use PHP $PHP_VERSION syntax and features when writing PHP code
- Use modern PHP features available in version $PHP_VERSION

## Build Process
- Use npm run build for production builds
- Use npm run start for development
- The project uses webpack for asset compilation
- Follow the established build pipeline

## Testing and Quality
- Run phpcs for PHP code quality checks
- Use ESLint for JavaScript linting
- Follow the project's lint-staged configuration
- Test blocks in the WordPress block editor
EOF

# Update php-code-standards.mdc with PHP version
print_status "Updating php-code-standards.mdc..."
cat > "$CURSOR_RULES_DIR/php-code-standards.mdc" << EOF
# PHP Code Standards

## General Standards
- Follow WordPress PHP Coding Standards (WPCS)
- Use PHP $PHP_VERSION features where supported
- Implement proper type hinting and return types
- Use strict typing where applicable
- Follow PSR-4 autoloading standards

## Code Style
- Use 4 spaces for indentation (no tabs)
- Use single quotes for strings unless double quotes are needed
- Use proper spacing around operators and control structures
- Follow WordPress naming conventions:
  - Classes: PascalCase
  - Functions: snake_case
  - Variables: snake_case
  - Constants: UPPER_CASE
  - Namespaces: PascalCase

## Documentation
- Use PHPDoc blocks for all classes, methods, and functions
- Document all parameters and return types
- Include @since tags for version tracking
- Document exceptions and error conditions
- Follow WordPress documentation standards

## Security
- Always sanitize and validate input
- Use nonces for form submissions
- Implement proper capability checks
- Use prepared statements for database queries
- Follow WordPress security best practices

## Performance
- Use proper caching strategies
- Optimize database queries
- Implement proper error handling
- Use proper logging
- Follow WordPress performance best practices

## Testing
- Write unit tests for all functions
- Use PHPUnit for testing
- Implement proper test coverage
- Test edge cases and error conditions
- Follow WordPress testing standards

## Error Handling
- Use proper error logging
- Implement proper exception handling
- Use WordPress debug mode in development
- Follow WordPress error handling standards
- Document all error conditions

## Database
- Use WordPress database functions
- Use proper table prefixes
- Follow database naming conventions
- Use proper data types
- Implement proper database versioning

## PHP Development Guidelines
- Use PHP $PHP_VERSION features like typed properties, match expressions, and named arguments
- Implement proper error handling and logging
- Use WordPress hooks and filters appropriately
- Follow WordPress security best practices
- Use proper namespacing for custom classes
EOF

# Create/update other .mdc files (these will be static content)
print_status "Creating development-workflow.mdc..."
cat > "$CURSOR_RULES_DIR/development-workflow.mdc" << EOF
# Development Workflow

## Project Setup
- All dependencies must be managed through Composer for PHP and npm for JavaScript
- Plugin dependencies should be defined in the root composer.json
- Theme and block dependencies should be managed in their respective package.json files
- PHP Version: $PHP_VERSION (extracted from composer.json)
- Node.js Version: ${NODE_VERSION:-">=20.0.0"} (extracted from package.json)
- npm Version: ${NPM_VERSION:-">=10"} (extracted from package.json)

## Version Control
- Use semantic versioning for all plugins and themes
- Commit messages should follow conventional commits format
- Feature branches should be created from develop branch
- Pull requests must be reviewed before merging
- Composer lock file (composer.lock) and npm lock file (package-lock.json) must be committed
- Project Version: ${COMPOSER_VERSION:-"1.3.0"} (extracted from composer.json)

## Development Process
- Local development will be done outside of the repository using Local by Flywheel
- All new features must include unit tests
- Code must pass PHPCS and ESLint checks before committing
- Documentation must be updated for all new features
- Custom Composer scripts:
$COMPOSER_SCRIPTS
- Custom npm scripts:
$NPM_SCRIPTS

## Deployment
- Production deployments should be automated
- Database migrations must be handled through WP-CLI
- Asset compilation must be part of the build process
- Environment-specific configurations must be managed through .env files
EOF

print_status "Creating javascript-standards.mdc..."
cat > "$CURSOR_RULES_DIR/javascript-standards.mdc" << EOF
# JavaScript and Block Development Standards

## General Standards
- Use ES6+ features where supported
- Follow WordPress JavaScript coding standards
- Use TypeScript for block development
- Implement proper error handling and logging
- Node.js Version: ${NODE_VERSION:-">=20.0.0"} (extracted from package.json)
- npm Version: ${NPM_VERSION:-">=10"} (extracted from package.json)

## Block Development
- Use @wordpress/create-block for new block scaffolding
- Follow block.json schema standards
- Implement proper block attributes and controls
- Use WordPress components from @wordpress/components
- Implement proper block registration and unregistration

## Code Organization
- Use modular architecture
- Implement proper dependency management
- Follow single responsibility principle
- Use proper file naming conventions:
  - block-name/
    - index.js
    - editor.js
    - style.css
    - editor.css
    - block.json

## Testing
- Write unit tests for all block functionality
- Use @wordpress/jest-preset-default for testing
- Implement proper test coverage
- Test both editor and frontend functionality
- Custom npm scripts for testing and linting:
  - lint:js: wp-scripts lint-js
  - test: (add your test script here if available)

## Build Process
- Use webpack for asset bundling
- Implement proper source maps
- Use proper minification and optimization
- Follow WordPress asset loading best practices
- Custom npm scripts for build:
  - build: rimraf build blocks && cross-env NODE_ENV=production wp-scripts build --config webpack.config.js --progress
  - start: rm -rf build blocks && npx cross-env NODE_ENV=development wp-scripts start
EOF

print_status "Creating theme-development.mdc..."
cat > "$CURSOR_RULES_DIR/theme-development.mdc" << EOF
# Theme Development Standards

## Theme Structure
- Follow WordPress theme development standards
- Use proper theme hierarchy
- Implement proper template parts
- Use proper template hierarchy
- Follow WordPress theme check requirements

## Asset Management
- Use proper asset enqueuing
- Implement proper asset versioning
- Use proper asset dependencies
- Follow WordPress asset loading best practices
- Implement proper asset minification

## Block Integration
- Support full site editing where applicable
- Implement proper block patterns
- Use proper block templates
- Support block styles
- Implement proper block variations

## Performance
- Implement proper caching strategies
- Optimize asset loading
- Use proper image optimization
- Implement proper lazy loading
- Follow WordPress performance best practices

## Security
- Follow WordPress security best practices
- Implement proper data sanitization
- Use proper nonce verification
- Implement proper capability checks
- Follow WordPress coding standards

## Documentation
- All documentation should follow proper DocBlock formatting standards
- Document all custom functions
- Document all template parts
- Document all custom post types
- Document all custom taxonomies
- Document all custom blocks
EOF

print_status "Successfully updated all Cursor rules files in $CURSOR_RULES_DIR with PHP version $PHP_VERSION"

# Make the script executable for future use
chmod +x "$0"

print_status "Script completed successfully!"
