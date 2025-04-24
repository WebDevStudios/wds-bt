// Adds custom icons for use with the Icon Block.
wp.domReady(() => {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;

	/**
	 * Adds custom icons for use with the Icon Block.
	 *
	 * @param {Array} icons - The existing icons array.
	 * @return {Array} - The updated icons array with BDO icons.
	 */
	function addCustomIcons(icons) {
		const universalIcons = [
			{
				isDefault: true,
				name: 'chevron-right-circle',
				title: __('Chevron Right Circle', 'wdsbt'),
				icon: '<svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 3.16665C5.05448 3.16665 2.66667 5.55446 2.66667 8.49998C2.66667 11.4455 5.05448 13.8333 8 13.8333C10.9455 13.8333 13.3333 11.4455 13.3333 8.49998C13.3333 5.55446 10.9455 3.16665 8 3.16665ZM1.33333 8.49998C1.33333 4.81808 4.3181 1.83331 8 1.83331C11.6819 1.83331 14.6667 4.81808 14.6667 8.49998C14.6667 12.1819 11.6819 15.1666 8 15.1666C4.3181 15.1666 1.33333 12.1819 1.33333 8.49998Z"/><path fill-rule="evenodd" clip-rule="evenodd" d="M6.86193 6.02858C7.12228 5.76823 7.54439 5.76823 7.80474 6.02858L9.80474 8.02858C10.0651 8.28892 10.0651 8.71103 9.80474 8.97138L7.80474 10.9714C7.54439 11.2317 7.12228 11.2317 6.86193 10.9714C6.60158 10.711 6.60158 10.2889 6.86193 10.0286L8.39052 8.49998L6.86193 6.97138C6.60158 6.71103 6.60158 6.28892 6.86193 6.02858Z"/></svg>',
				categories: ['user-interface'],
			},
		];

		const universalIconCategories = [
			{
				name: 'user-interface',
				title: __('User Interface', 'wdsbt'),
			},
		];

		const wdsbtIconType = [
			{
				isDefault: true,
				type: 'universal',
				title: __('Universal', 'wdsbt'),
				icons: universalIcons,
				categories: universalIconCategories,
				hasNoIconFill: true,
			},
		];

		const allIcons = [].concat(icons, wdsbtIconType);

		return allIcons;
	}

	addFilter('iconBlock.icons', 'wdsbt/wdsbt-icons', addCustomIcons);
});
