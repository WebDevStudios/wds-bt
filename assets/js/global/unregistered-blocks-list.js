wp.domReady(function () {
	const coreVariations = ['core/loginout', 'youtube'];

	const embedVariations = [
		'amazon-kindle',
		'animoto',
		'cloudup',
		'collegehumor',
		'crowdsignal',
		'dailymotion',
		'flickr',
		'imgur',
		'issuu',
		'kickstarter',
		'mixcloud',
		'pocketcasts',
		'reddit',
		'reverbnation',
		'screencast',
		'scribd',
		'slideshare',
		'smugmug',
		'soundcloud',
		'speaker-deck',
		'spotify',
		'ted',
		'tiktok',
		'tumblr',
		'youtube',
	];

	for (let i = coreVariations.length - 1; i >= 0; i--) {
		wp.blocks.unregisterBlockType(coreVariations[i]);
	}
	for (let i = embedVariations.length - 1; i >= 0; i--) {
		wp.blocks.unregisterBlockVariation('core/embed', embedVariations[i]);
	}
});
