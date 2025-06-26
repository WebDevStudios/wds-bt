# Font Generation Tools

This directory contains tools for managing fonts in the WDSBT theme.

## Font Organization

Fonts are organized by their purpose/role for easy swapping:

```
assets/fonts/
├── headline/
│   ├── Inter.woff2
│   └── Poppins-Bold.woff2
├── body/
│   ├── Oxygen-Regular.woff2
│   └── OpenSans-Regular.woff2
└── mono/
    └── RobotoMono-Regular.woff2
```

**Benefits:**
- **Easy font swapping**: Just replace files in each folder
- **Clear purpose**: Each folder has a specific role
- **Standardized slugs**: Automatically generates `headline`, `body`, `mono` slugs in theme.json
- **Consistent paths**: CSS custom properties always use the same slugs

**How it works:**
1. Place fonts in the appropriate purpose folder (`headline/`, `body/`, `mono/`)
2. The processor detects the purpose from the folder name
3. Maps to standardized slugs in theme.json
4. Generates correct CSS custom properties: `var(--wp--preset--font-family--headline)`, `var(--wp--preset--font-family--body)`, `var(--wp--preset--font-family--mono)`

**To change fonts:**
- **Headlines**: Replace files in `headline/` folder
- **Body text**: Replace files in `body/` folder
- **Code/mono**: Replace files in `mono/` folder

## Tools Overview

### 1. Font Detection (`font-detection.php`)
Scans the theme for existing font files and displays information about them.

**Usage:**
```bash
npm run fonts:detect
# or
php tools/font-detection.php
```

**Output:**
- Lists all fonts found in `assets/fonts/` and `build/fonts/`
- Shows font family, weight, and style information
- Helps identify what fonts are available

### 2. Font Processor (`font-processor.php`)
Processes existing font files to generate optimized CSS, preload links, and update theme.json.

**Usage:**
```bash
npm run fonts
# or
php tools/font-processor.php [options]
```

**Options:**
- `--input-dir DIR` - Input directory (default: assets/fonts)
- `--output-dir DIR` - Output directory (default: build/fonts)
- `--css-output FILE` - CSS output file (default: assets/scss/base/_fonts.scss)
- `--preload-output FILE` - Preload output file (default: inc/setup/font-preload.php)
- `--help` - Show help information

**What it does:**
1. Scans for existing font files (WOFF2, WOFF, TTF, OTF)
2. Copies fonts to the build directory
3. Generates CSS `@font-face` declarations
4. Creates font preload links for performance
5. Generates font fallback variables
6. Updates theme.json with font information

### 3. Theme JSON Generator (`generate-theme-json.php`)
Updates theme.json with detected font information for WordPress block editor.

**Usage:**
```bash
npm run fonts:generate
# or
php tools/generate-theme-json.php
```

**What it does:**
- Scans for font files in assets and build directories
- Groups fonts by family
- Updates theme.json with font family definitions
- Enables fonts in the WordPress block editor

## Font File Naming Convention

The tools automatically detect font information from filenames using these patterns:

### Font Families
- `inter` → Inter
- `oxygen` → Oxygen
- `roboto-mono` → Roboto Mono
- `roboto` → Roboto
- `open-sans` → Open Sans
- `lato` → Lato
- `poppins` → Poppins
- `montserrat` → Montserrat
- `raleway` → Raleway
- `playfair` → Playfair Display

### Font Weights
- `thin` or `-100` → 100
- `extralight` or `-200` → 200
- `light` or `-300` → 300
- `regular`, `normal`, or `-400` → 400
- `medium` or `-500` → 500
- `semibold` or `-600` → 600
- `bold` or `-700` → 700
- `extrabold` or `-800` → 800
- `black` or `-900` → 900

### Font Styles
- `italic` → italic
- `oblique` → oblique

### Examples
- `inter-regular.woff2` → Inter, weight 400, normal style
- `oxygen-bold.woff2` → Oxygen, weight 700, normal style
- `roboto-mono-italic.woff2` → Roboto Mono, weight 400, italic style

## Generated Files

When you run the font processor, it creates:

1. **Build Fonts** (`build/fonts/`)
   - Optimized font files ready for production

2. **Font CSS** (`assets/scss/base/_fonts.scss`)
   - `@font-face` declarations for all fonts
   - Includes `font-display: swap` for performance

3. **Font Preload** (`inc/setup/font-preload.php`)
   - PHP function to output preload links
   - Improves font loading performance

4. **Font Fallbacks** (`assets/scss/abstracts/_font-fallbacks.scss`)
   - SCSS variables with system font fallbacks
   - Ensures good typography even if custom fonts fail to load

5. **Theme JSON** (`theme.json`)
   - Updated with font family definitions
   - Enables fonts in WordPress block editor

## Integration

### Including Font CSS
Add this to your main SCSS file:
```scss
@import 'base/fonts';
```

### Including Font Fallbacks
Add this to your abstracts:
```scss
@import 'abstracts/font-fallbacks';
```

### Using Font Preload
Add this to your theme's header:
```php
<?php wdsbt_font_preload_links(); ?>
```

## Performance Tips

1. **Use WOFF2 format** - Best compression and browser support
2. **Preload critical fonts** - Only preload fonts used above the fold
3. **Use font-display: swap** - Prevents invisible text during font loading
4. **Subset fonts** - Only include characters you actually use
5. **Optimize font files** - Use tools like fonttools for compression

## Troubleshooting

### Fonts not detected
- Check that font files are in the correct directory
- Verify filename follows the naming convention
- Ensure file extensions are supported (woff2, woff, ttf, otf)

### CSS not generated
- Check file permissions on output directories
- Verify PHP has write access to the theme directory

### Theme JSON not updated
- Ensure the generate-theme-json.php file is included correctly
- Check that theme.json is writable

## Advanced Usage

### Custom Font Families
To add support for new font families, edit the `$family_patterns` array in the PHP files:

```php
$family_patterns = [
    'inter' => 'Inter',
    'oxygen' => 'Oxygen',
    'your-font' => 'Your Font Name',
];
```

### Custom Output Formats
Modify the `$formats` array to generate different font formats:

```php
$formats = ['woff2', 'woff', 'ttf'];
```

### Custom Subsets
For the font generator tool, you can specify custom character subsets:

```bash
php tools/font-generator.php --subset "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"
```
