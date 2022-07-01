export default function inverseDate(date) {
    if(!date) return null
    const [day, month, year] = date.split('.');
    return `${year}-${month}-${day}`;
}