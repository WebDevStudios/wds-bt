/**
 * Unregister the Gutenberg blocks, style and variations that are not going to be used on the website
 */
wp.domReady(() => {
	// List of the Gutenberg blocks that would be unregistered.
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

/* global alert */
if (window.localStorage) {
	window.addEventListener('storage', function (event) {
		if (event.key === 'wdsbt_patterns_flushed') {
			if (window.wp && window.wp.data) {
				window.wp.data
					.dispatch('core')
					.invalidateResolution('getBlockPatterns');
				window.wp.data
					.dispatch('core')
					.invalidateResolution('getBlockPatternCategories');
				// eslint-disable-next-line no-alert
				alert(
					'Block patterns and categories have been refreshed. Please re-open the inserter to see new items.'
				);
			}
		}
	});
}
