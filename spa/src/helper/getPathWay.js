export default function getPathWay(array, id, pathList = []) {
    const currentItem = array.find((item) => item.id === id);

    if (currentItem.parent_id) {
        getPathWay(array, currentItem.parent_id, pathList);
    }

    pathList.push(currentItem);
    return pathList;
}
