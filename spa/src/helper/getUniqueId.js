export default function getUniqueId() {
    return `f${(~~(Math.random()*1e8)).toString(16)}`;
}