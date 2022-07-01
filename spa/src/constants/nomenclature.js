export const TABLE_RESOURCES = {
    ANALOGS: {
        NAME: 'analogs',
        FETCH_URL: '/products/nomenclatures/get-table-analog-products/',
        FETCH_CATEGORIES_ITEMS: '/products/nomenclatures/get-table-analog-products/',
        ADD_URL: '/products/nomenclatures/create-analog-products/',
        DEL_URL: '/products/nomenclatures/delete-analog-products/'
    },
    RELATED: {
        NAME: 'related',
        FETCH_URL: '/products/nomenclatures/get-table-related-products/',
        ADD_URL: '/products/nomenclatures/create-related-products/',
        DEL_URL: '/products/nomenclatures/delete-related-products/'

    },
    PRICES: {
        NAME: 'prices',
        FETCH_URL: ''
    },
    GROUPS_PRODUCTS: {
        NAME: 'groups_products',
        FETCH_URL: 'products/nomenclatures/get-groups-nomenclatures/',
        DEL_URL: 'products/nomenclatures/toArchive'
    },
}
