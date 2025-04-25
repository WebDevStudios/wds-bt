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
				name: 'chevron-right-circle--line',
				title: __('Chevron Right Circle', 'wdsbt'),
				icon: '<svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 3.16665C5.05448 3.16665 2.66667 5.55446 2.66667 8.49998C2.66667 11.4455 5.05448 13.8333 8 13.8333C10.9455 13.8333 13.3333 11.4455 13.3333 8.49998C13.3333 5.55446 10.9455 3.16665 8 3.16665ZM1.33333 8.49998C1.33333 4.81808 4.3181 1.83331 8 1.83331C11.6819 1.83331 14.6667 4.81808 14.6667 8.49998C14.6667 12.1819 11.6819 15.1666 8 15.1666C4.3181 15.1666 1.33333 12.1819 1.33333 8.49998Z"/><path fill-rule="evenodd" clip-rule="evenodd" d="M6.86193 6.02858C7.12228 5.76823 7.54439 5.76823 7.80474 6.02858L9.80474 8.02858C10.0651 8.28892 10.0651 8.71103 9.80474 8.97138L7.80474 10.9714C7.54439 11.2317 7.12228 11.2317 6.86193 10.9714C6.60158 10.711 6.60158 10.2889 6.86193 10.0286L8.39052 8.49998L6.86193 6.97138C6.60158 6.71103 6.60158 6.28892 6.86193 6.02858Z"/></svg>',
				categories: ['user-interface'],
			},
			{
				name: 'star--line',
				title: __('Star', 'wdsbt'),
				icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.69919 3.51857C10.5616 1.4886 13.4387 1.4886 14.3011 3.51857L15.8587 7.18501L19.7071 7.50733C21.8846 7.6897 22.7966 10.3813 21.1786 11.8498L18.2357 14.5208L19.199 18.957C19.6857 21.1983 17.1496 22.8572 15.2911 21.5134L12.0002 19.1339L8.70923 21.5134C6.85066 22.8573 4.31465 21.1983 4.80134 18.957L5.76465 14.5208L2.82167 11.8498C1.20359 10.3813 2.11566 7.6897 4.29317 7.50733L8.14158 7.18501L9.69919 3.51857ZM12.4604 4.30059C12.2879 3.8946 11.7124 3.8946 11.54 4.30059L9.86443 8.2446C9.64684 8.75678 9.16358 9.10641 8.60904 9.15286L4.4601 9.50035C4.02459 9.53682 3.84218 10.0751 4.1658 10.3688L7.32264 13.234C7.71965 13.5943 7.89416 14.1391 7.78039 14.663L6.75579 19.3814C6.65845 19.8297 7.16566 20.1614 7.53737 19.8927L11.1213 17.3013C11.6458 16.9221 12.3545 16.9221 12.8791 17.3013L16.4629 19.8927C16.8347 20.1614 17.3419 19.8297 17.2445 19.3814L16.2199 14.663C16.1062 14.1391 16.2807 13.5943 16.6777 13.234L19.8344 10.3688C20.158 10.0751 19.9756 9.53682 19.5401 9.50035L15.3913 9.15286C14.8367 9.10641 14.3535 8.75678 14.1359 8.2446L12.4604 4.30059Z"/></svg>',
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
