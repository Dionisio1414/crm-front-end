export default function getAddressItem(data, id) {
    return data.find((item) => +item["directory_id"] === +id || item.id === +id)
}
