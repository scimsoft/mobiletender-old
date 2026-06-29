/**
 * Offer create/edit page: add/remove product rows in the offer's
 * "virtual table". Pure DOM, no jQuery, safe to import on every page
 * served from layouts.admin (it no-ops if the table is not present).
 */

function initOfferEdit() {
    const table = document.getElementById('offer-products-table');
    const addBtn = document.getElementById('add-offer-row');
    if (!table || !addBtn) {
        return;
    }
    const tbody = table.querySelector('tbody');
    if (!tbody) {
        return;
    }

    function resetRow(row) {
        const select = row.querySelector('select');
        if (select) {
            select.value = '';
        }
        const qty = row.querySelector('input[type="number"]');
        if (qty) {
            qty.value = 1;
        }
    }

    addBtn.addEventListener('click', function () {
        const firstRow = tbody.querySelector('tr');
        if (!firstRow) {
            return;
        }
        const clone = firstRow.cloneNode(true);
        resetRow(clone);
        tbody.appendChild(clone);
    });

    tbody.addEventListener('click', function (event) {
        const btn = event.target.closest('.remove-offer-row');
        if (!btn) {
            return;
        }
        const row = btn.closest('tr');
        if (!row) {
            return;
        }
        const rows = tbody.querySelectorAll('tr');
        if (rows.length > 1) {
            row.remove();
        } else {
            resetRow(row);
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initOfferEdit);
} else {
    initOfferEdit();
}
