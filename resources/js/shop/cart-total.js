import $ from 'jquery';

/**
 * Formats ex-VAT line total the same as @money(*1.1) in Blade.
 * @param {number} totalNet
 * @returns {string}
 */
export function formatBasketTotalGross(totalNet) {
    const n = Number(totalNet);
    return (n * 1.1).toFixed(2) + '€';
}

/**
 * Updates header cart display and optional quantity badge.
 * @param {number} totalNet - basket sum before VAT (same as getSumTicketLines)
 * @param {number} [lineCount] - if omitted, badge is not updated
 */
export function updateBasketBadge(totalNet, lineCount) {
    const formatted = formatBasketTotalGross(totalNet);
    const $total = $('#ordertotal');
    if ($total.length) {
        $total.text(formatted);
    }
    const $badge = $('#basketItemCount');
    if ($badge.length && typeof lineCount === 'number') {
        if (lineCount > 0) {
            $badge.text(String(lineCount)).removeClass('hidden').attr('aria-hidden', 'false');
        } else {
            $badge.addClass('hidden').attr('aria-hidden', 'true').text('0');
        }
    }
}

if (typeof window !== 'undefined') {
    window.updateBasketBadge = updateBasketBadge;
    window.formatBasketTotalGross = formatBasketTotalGross;
}
