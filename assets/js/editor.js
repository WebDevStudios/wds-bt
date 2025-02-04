/**
 * Unregister the Gutenberg blocks, style and variations that are not going to be used on the website
 */
wp.domReady(() => {
	// List of the Gutenberg blocks that would be unregistered
	const unusedBlocks = ['core/latest-comments', 'core/missing'];

	const embedBlockVariations = wp.blocks.getBlockVariations('core/embed');
	const keepEmbeds = [
		'twitter',
		'instragram',
		'wordpress',
		'spotify',
		'soundcloud',
		'youtube',
		'flickr',
		'tiktok',
		'videopress',
		'wordpress-tv',
		'bluesky',
	];

	for (let i = 0; i < unusedBlocks.length; i++) {
		wp.blocks.unregisterBlockType(unusedBlocks[i]);
	}

	for (let i = 0; i < embedBlockVariations.length; i++) {
		if (!keepEmbeds.includes(embedBlockVariations[i].name)) {
			wp.blocks.unregisterBlockVariation(
				'core/embed',
				embedBlockVariations[i].name
			);
		}
	}
});
