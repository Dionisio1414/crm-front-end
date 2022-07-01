export default function removeDuplicates(array) {
    return array.reduce((acc, item) => (acc.some(a => isDuplicates(a, item)) || acc.push(item), acc), []);
}

function isDuplicates(a, b) {
    if (!a || typeof a !== 'object') {
        return a === b;
    }

    let keys = Object.keys(a);
    return keys.length === Object.keys(b).length
        && keys.every(k => k in b && isDuplicates(a[k], b[k]));
}
