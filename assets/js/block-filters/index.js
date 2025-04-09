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
		console.log(`✅ Loaded filter: ${key}`);
	} catch (e) {
		console.error(`❌ Failed to load filter: ${key}`, e);
	}
});
