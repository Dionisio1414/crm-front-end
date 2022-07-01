export const MODE_TYPES = {
    ADD: "add",
    EDIT: "edit",
    COPY: "copy",
    MOVE: "move",
    REMOVE: "remove",
    VIEW: "view",
    CHANGE: 'change',
    CREATE: 'create'
}

export const DOCUMENT_TYPES = {
    RECEIPT_STOCKS: 'receipt_stocks',
    WRITE_OF_STOCKS: 'write_off_stocks',
    TRANSFER_STOCKS: 'transfer_stocks',
    PURCHASE: 'purchases'
}

export const  TABLE_ACTIONS = {
    COPY: 'copy',
    UPDATE: 'update',
    MOVE: 'move',
    CREATE: 'create',
    COPY_IN: 'copy_in',
    DELETE: 'delete',
    AUTATCUAL: 'aut_actual',
    TOACTUAL: 'to_actual',
    PRICES: 'prices',
    CHANGE_HISTORY: 'change_history',
    ANALOGS: "analogs",
    RELATED: 'related'
}

export const CHOOSE_MODAL_RESOURCES = {
    ANALOGS: {
        NAME: 'analog',
        FETCH_ALL_ITEMS: '/products/nomenclatures/get-products-in-related-or-analog-all',
        FETCH_CATEGORIES_ITEMS: '/products/nomenclatures/get-products-in-related-or-analog/',
        SELECT_ITEMS: '/products/nomenclatures/selected-related-or-analog-nomenclatures',
        ADD_ITEMS:'/products/nomenclatures/create-analog-products/',
        FETCH_URL: '/products/nomenclatures/get-table-analog-products/',

    },
    POSTING: {
        NAME: 'posting',
        FETCH_ALL_ITEMS: 'analogsFetchAllItems',
        FETCH_CATEGORIES_ITEMS: '/products/nomenclatures/get-select-products/',
        SELECT_ITEMS: '/products/nomenclatures/selected-nomenclatures',
        SELECT_SERVICES_ITEMS: '/products/nomenclatures/selected-services',
        SEARCH: 'products/nomenclatures/get-select-products-all'
    },
    ACCOMPANYING: {
        NAME: 'related',
        FETCH_ALL_ITEMS: '/products/nomenclatures/get-products-in-related-or-analog-all',
        FETCH_CATEGORIES_ITEMS: '/products/nomenclatures/get-products-in-related-or-analog/',
        SELECT_ITEMS: '/products/nomenclatures/selected-related-or-analog-nomenclatures',
        ADD_ITEMS:'/products/nomenclatures/create-related-products/',
        FETCH_URL: '/products/nomenclatures/get-table-related-products/',

    }
}

export const NOMENCLATURE_RESOURCES = {
    PRODUCTS: {
        MULTIPLE_VALUE: 'products',
        SINGLE_VALUE: 'product'
    },
    SERVICES: {
        MULTIPLE_VALUE: 'services',
        SINGLE_VALUE: 'service'
    },
    KITS: {
        MULTIPLE_VALUE: 'kits',
        SINGLE_VALUE: 'kit'
    },
    NOT_ACTUAL: {
        MULTIPLE_VALUE: 'not-actual-nomenclatures',
        SINGLE_VALUE: 'not-actual-nomenclatures'
    }
}

export const TYPE_OF_PACK = {
    PACK:"уп",
    THING:"шт"
}

export const TYPE_OF_STOCK_MODAL = {
    POST: "receipt_stocks",
    WRITE_OF: "write_off_stocks",
    MOVE_STOCK: "transfer_stocks",
    PURCHASES: "purchases",
    ORDERS: "orders"
}

export const TABS_GROUPS = {
    ALL: "all-groups",
    BY_GROUPS: "by-groups"
}

export const TABS_CATEGORY = {
    ALL: "all-category",
    BY_CATEGORY: "by-category"
}
