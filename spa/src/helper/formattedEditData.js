export default function formattedEditData(array, obj) {
    const { document_id, cells: { convert_id }, type, delivery } = obj;
    const item = array.find(item => item.id === document_id || item.document_id === document_id);

    return {...item, type, delivery, ...{ document_id, convert_id }}
}
