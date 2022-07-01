export default function daysInMonth(last) {
    const date = new Date();
    const lastMonth = last ? date.getMonth() -1 : date.getMonth();

    return 33 - new Date(date.getFullYear(), lastMonth, 33).getDate();
}