export const pick = (arrayObjects) => {
    const getTransformObject = (obj, ...keys) => keys.reduce((a, c) => ({ ...a, [c]: obj[c] }), {})
    return k => arrayObjects.map(item => getTransformObject(item, k))
}

