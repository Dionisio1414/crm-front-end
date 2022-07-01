export default function getSupplierData(data) {
    const {
        formatDate: date_of_birth,
        foreignCompany: is_foreign_company,
        groupId: group_id,
        idCode,
        lawCode: partner_edrpou,
        nameOfCompany: title_company,
        nameOfSupplier: title_supplier_first_name,
        nameOfSupplierSecond: title_supplier_middle_name,
        nameOfSupplierThird: title_supplier_last_name,
        sexId: sex_id,
        legal_address_street,
        legal_address_number_housing,
        legal_address_country_id,
        legal_address_region_id,
        legal_city_id,
        email,
        phone,
        currencyValue: currency_id,
        managerValue: manager_id,
        statusSupplier: partner_type_id,
        is_legal_equal_actual_address,
        is_delivery_equal_actual_address,
        delivery_address,
        actual_address,
        contacts
    } = data;

    const partner_inn = +idCode;

    return {
        title_supplier: title_supplier_first_name,
        title_company,
        title_supplier_middle_name,
        title_supplier_last_name,
        title_supplier_first_name,
        group_id,
        is_foreign_company,
        date_of_birth,
        partner_inn,
        partner_edrpou,
        sex_id,
        currency_id,
        legal_address_street,
        legal_address_number_housing,
        phone,
        email,
        legal_address_country_id,
        legal_address_region_id,
        legal_city_id,
        manager_id,
        partner_type_id,
        is_legal_equal_actual_address,
        is_delivery_equal_actual_address,
        actual_address,
        delivery_address,
        contacts
    }
}