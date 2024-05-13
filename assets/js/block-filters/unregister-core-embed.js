/*
 * Functions to unregister and disable specific core Gutenberg blocks, styles, and variations.
 */

wp.domReady(() => {
	// List of Gutenberg blocks to unregister.
	const unusedBlocks = [
		'core/file',
		'core/latest-comments',
		'core/rss',
		'core/tag-cloud',
		'core/missing',
		'core/site-tagline',
		'core/loginout',
		'core/term-description',
		'core/query-title',
	];

	// List of Gutenberg block variations to unregister.
	const unregisterBlockVariations = [
		// Example:
		// {
		//     blockName: 'core/group',
		//     blockVariationName: 'group-row',
		//
		//     blockName: 'core/group',
		//     blockVariationName: 'group-stack',
		// },
	];

	// Keep only the necessary embed variations.
	const embedBlockVariations = wp.blocks.getBlockVariations('core/embed');
	const keepEmbeds = [
		'twitter',
		'wordpress',
		'spotify',
		'soundcloud',
		'flickr',
	];

	// Unregister unused blocks.
	for (let i = 0; i < unusedBlocks.length; i++) {
		wp.blocks.unregisterBlockType(unusedBlocks[i]);
	}

	// Unregister unused block variations.
	for (let i = 0; i < unregisterBlockVariations.length; i++) {
		wp.blocks.unregisterBlockVariation(
			unregisterBlockVariations[i].blockName,
			unregisterBlockVariations[i].blockVariationName
		);
	}

	// Keep only necessary embed variations.
	for (let i = 0; i < embedBlockVariations.length; i++) {
		if (!keepEmbeds.includes(embedBlockVariations[i].name)) {
			wp.blocks.unregisterBlockVariation(
				'core/embed',
				embedBlockVariations[i].name
			);
		}
	}
});
