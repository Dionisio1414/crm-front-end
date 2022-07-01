export default function getFlatMap(array = []) {
    return array.reduce((r, { children, ...rest }) =>
        r.concat({ ...rest }, getFlatMap(children)), []);
}
