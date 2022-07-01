export const nomenclatureGetProductDecorator = (data) => {
    if (data.related && data.related.body) {
        data.related = data.related.body.map(item => ({
            id: item.id
        }))
    }

    data.base_characteristic_value = data.base_characteristic_value || null;
    let color = data.characteristic_color_value;
    // if (color && color.is_base) {
    //     data.base_characteristic_value = {
    //         ...color,
    //         is_color: true,
    //         values: [{...color.values}]
    //     }
    // } else {
    //     const base = data.characteristic_value.find(char => char.is_base)
    //     if (base) {
    //         data.base_characteristic_value = {
    //             ...base
    //         }
    //     }
    // }
    if (color && data.characteristic_value) {
        data.characteristic_value.push({
            id: color.id,
            title: color.title,
            values: data['is_groups'] ? color.values : [color.values]
        })
        color = null
        data.characteristic_value = data.characteristic_value.filter(char => !char.is_base)
    }

    data.prices = data.prices.map(price => ({
        ...price,
        id: price['type_price_id']
    }))

    return data
}

export const nomenclaturePostProductDecorator = (itemData, isGroup, resource) => {
    const data = itemData;
    // if (!data.related) {
    //     data.related = []
    // } else {
    //     data.related =[...data.related.body].map(item => ({id: item.id}))
    // }
    if (resource === 'product') {
        if (!isGroup) {
            const statsValuesArr = []
            // if (data.base_characteristic_value) {
            //     data.base_characteristic_value = {
            //         is_color: data.base_characteristic_value.is_color,
            //         ids: data.base_characteristic_value.values
            //     }
            // }
            const color = data.characteristic_value.find(char => char.id === 1) || null
            data.characteristic_color_value = color ? [...color.values] : null
            data.characteristic_value = data.characteristic_value.filter((item) => item.id !== 1)
            data.characteristic_value.forEach((item) => {
                item.values.forEach(val => statsValuesArr.push({...val}))
            })
            data.characteristic_value = statsValuesArr
            data.property_value = data.property_value.map(item => {
                return {id: item.id}
            })
        } else {
            // data.base_characteristic = {
            //     ids: [{
            //         id: data.base_characteristic_value.id
            //     }]
            // }
            data.characteristic = [...data.characteristic_value].map(char => {
                return {id: char.id}
            })

            if (data.characteristic_color_value) {
                data.characteristic.push({id: 1})
            }

            delete data.characteristic_value
            delete data.base_characteristic_value
            delete data.characteristic_color_value
        }
    }
    return data
}
