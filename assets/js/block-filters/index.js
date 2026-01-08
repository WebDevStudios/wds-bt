/* eslint-disable no-console */
/**
 * Block Filters
 * Automatically imports all JS files in this directory (non-recursive).
 */

const filters = require.context('./', false, /\.js$/);

filters.keys().forEach((key) => {
	if (key === './index.js') {
		return;
	}
	try {
		filters(key);
	} catch (e) {
		// Filter failed to load.
	}
});
