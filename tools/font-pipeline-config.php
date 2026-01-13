#!/usr/bin/env php
<?php
/**
 * WDSBT Font Pipeline Configuration.
 *
 * Central configuration for font scanning, processing, and theme.json generation.
 * All tools (CLI detection, generate-theme-json, font-processor) should use this as the
 * single source of truth for directories, output formats, and preload paths.
 *
 * @package WDSBT
 */

// Prefix: wdsbt_

/**
 * Font pipeline configuration.
 *
 * @var array $wdsbt_config
 *
 * Configuration keys:
 *   - input_dir      string  Relative directory to scan for source fonts.
 *   - output_dir     string  Relative directory to write processed/build fonts.
 *   - preload_output string  Path to PHP file for font preload declarations.
 *   - formats        array   List of font formats to process (e.g., ['woff2','woff']).
 */
$wdsbt_config = [
    // Directory to scan for source fonts (relative to theme root)
    'input_dir'      => 'assets/fonts',

    // Directory to write processed fonts (relative to theme root)
    'output_dir'     => 'build/fonts',

    // PHP file to generate for preloading fonts
    'preload_output' => 'inc/setup/font-preload.php',

    // List of font formats to process
    'formats'        => ['woff2','woff'],
];
