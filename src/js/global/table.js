document.addEventListener('DOMContentLoaded', function () {
	// Select all tables on the page
	const tables = document.querySelectorAll('table');

	tables.forEach(function (table) {
		// Get the header row of each table
		const headerRow = table.rows[0];

		if (headerRow) {
			// Check if headerRow is defined
			// Loop through each cell of the header row
			Array.from(headerRow.cells).forEach(function (cell, index) {
				const label = cell.textContent.trim();

				// Loop through each row (excluding the header row)
				for (let i = 1; i < table.rows.length; i++) {
					const currentRow = table.rows[i];
					if (currentRow) {
						// Check if currentRow is defined
						// Get the cell in the same column from the current row
						const cellInColumn = currentRow.cells[index];
						if (cellInColumn) {
							// Check if cellInColumn is defined
							// Set the data-label attribute with the content of the header cell
							cellInColumn.setAttribute('data-label', label);
						}
					}
				}
			});
		}
	});
});
