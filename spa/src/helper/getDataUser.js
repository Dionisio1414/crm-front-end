export default function getDataUser() {
    const user = localStorage.getItem('user');
    return JSON.parse(user);
}