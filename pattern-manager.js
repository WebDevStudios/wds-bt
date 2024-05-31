window.onload = function () {
	document.addEventListener('DOMContentLoaded', function () {
		document
			.getElementById('import-button')
			.addEventListener('click', function () {
				if (
					// eslint-disable-next-line no-alert
					confirm(
						'Are you sure you want to import patterns from patterns.json?'
					)
				) {
					// eslint-disable-next-line no-undef
					fetch(PatternManagerAjax.ajax_url, {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded',
						},
						body: new URLSearchParams({
							action: 'import_patterns',
							// eslint-disable-next-line no-undef
							nonce: PatternManagerAjax.nonce,
						}),
					})
						.then((response) => response.json())
						.then((response) => {
							if (response.success) {
								// eslint-disable-next-line no-alert
								alert('Patterns imported successfully');
							} else {
								// eslint-disable-next-line no-alert
								alert('Error: ' + response.data);
							}
						})
						// eslint-disable-next-line no-console
						.catch((error) => console.error('Error:', error));
				}
			});

		document
			.getElementById('export-button')
			.addEventListener('click', function () {
				if (
					// eslint-disable-next-line no-alert
					confirm(
						'Are you sure you want to export patterns to patterns.json?'
					)
				) {
					// eslint-disable-next-line no-undef
					fetch(PatternManagerAjax.ajax_url, {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded',
						},
						body: new URLSearchParams({
							action: 'export_patterns',
							// eslint-disable-next-line no-undef
							nonce: PatternManagerAjax.nonce,
						}),
					})
						.then((response) => response.json())
						.then((response) => {
							if (response.success) {
								// eslint-disable-next-line no-alert
								alert('Patterns exported successfully');
							} else {
								// eslint-disable-next-line no-alert
								alert('Error: ' + response.data);
							}
						})
						// eslint-disable-next-line no-console
						.catch((error) => console.error('Error:', error));
				}
			});
	});
};
