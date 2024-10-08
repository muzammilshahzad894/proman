export function truncateText(text, limit) {
    return text.length > limit ? text.substring(0, limit) + '...' : text;
}

export function formatDate(date) {
    return new Date(date).toLocaleDateString();
}

export function totalDays(date1, date2) {
    return Math.floor((new Date(date2) - new Date(date1)) / (1000 * 60 * 60 * 24));
}