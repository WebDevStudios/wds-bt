/*
 * Functions to unregister and disable specific core Gutenberg blocks, styles, and variations.
 */

document.addEventListener('DOMContentLoaded', function () {
	if (typeof wp !== 'undefined' && typeof wp.blocks !== 'undefined') {
		// List of Gutenberg blocks to unregister.
		const unusedBlocks = [
			'core/latest-comments',
			'core/rss',
			'core/missing',
			'core/spacer',
		];

		// List of Gutenberg block variations to unregister.
		const unregisterBlockVariations = [
			// Example:
			// {
			//     blockName: 'core/group',
			//     blockVariationName: 'group-row',
			// },
			// {
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
		unusedBlocks.forEach((block) => {
			wp.blocks.unregisterBlockType(block);
		});

		// Unregister unused block variations.
		unregisterBlockVariations.forEach((variation) => {
			wp.blocks.unregisterBlockVariation(
				variation.blockName,
				variation.blockVariationName
			);
		});

		// Keep only necessary embed variations.
		embedBlockVariations.forEach((variation) => {
			if (!keepEmbeds.includes(variation.name)) {
				wp.blocks.unregisterBlockVariation(
					'core/embed',
					variation.name
				);
			}
		});
	} else {
		// eslint-disable-next-line no-console
		console.error('wp.blocks object is not available.');
	}
});
