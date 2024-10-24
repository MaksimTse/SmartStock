function sortTable(columnIndex) {
	const table = document.getElementById("inventoryTable");
	let rows = Array.from(table.rows).slice(1);
	const isAscending = table.getAttribute("data-order") === "asc";

	rows.sort((a, b) => {
		const aText = a.cells[columnIndex].innerText;
		const bText = b.cells[columnIndex].innerText;
		return isAscending
			? aText.localeCompare(bText)
			: bText.localeCompare(aText);
	});

	rows.forEach(row => table.appendChild(row));
	table.setAttribute("data-order", isAscending ? "desc" : "asc");
}