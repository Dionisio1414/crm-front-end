export default function () {
    const today = new Date();
    const date = today.getDate();
    const month = today.getMonth() + 1;
    const year = today.getFullYear();
    const hours = today.getHours()
    const minutes = today.getMinutes()

    return `${year}-${month < 10 ? '0' + month : month}-${date < 10 ? '0' + date : date } ${hours}:${minutes}`;
}