export default function formatDate(date) {
    if (!date) return null;

    const getDate = date.split(' ')[0];
    const [year, month, day] = getDate.split('-')
    return `${day}.${month}.${year}`
}