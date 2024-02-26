document.addEventListener('DOMContentLoaded', function () {
	// Select all tables on the page
	const tables = document.querySelectorAll('table');

	tables.forEach(function (table) {
		// Get the header row of each table
		const headerRow = table.rows[0];

		// Loop through each cell of the header row
		Array.from(headerRow.cells).forEach(function (cell, index) {
			const label = cell.textContent.trim();

			// Loop through each row (excluding the header row)
			for (let i = 1; i < table.rows.length; i++) {
				// Get the cell in the same column from the current row
				const cellInColumn = table.rows[i].cells[index];

				// Set the data-label attribute with the content of the header cell
				cellInColumn.setAttribute('data-label', label);
			}
		});
	});
});
