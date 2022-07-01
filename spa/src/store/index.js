import Vue from 'vue'
import Vuex from 'vuex'
import nav from './modules/nav'
import auth from './modules/auth'
import properties from "@/store/modules/products/properties"
import characteristics from "@/store/modules/products/characteristics"
import categories from "@/store/modules/products/categories"
import alert from "@/store/modules/ui/alert"
import dialog from "@/store/modules/ui/dialog"
import settings from "@/store/modules/settings/programSettings"
import classifiers from "@/store/modules/directories/classifiers"
import nomenclature from "@/store/modules/products/nomenclature"
import company from "@/store/modules/directories/company"
import lists from './modules/lists'
import suppliers from "@/store/modules/products/suppliers/suppliers"
import purchases from "@/store/modules/products/purchases/purchases"
import wareHouses from "@/store/modules/products/wareHouses/wareHouses"
import tabs from "@/store/modules/tabs"
import filters from "@/store/modules/filters"
import priceDiscounts from "@/store/modules/crm/priceDiscounts"
import filesManager from "@/store/modules/filesManager"
import reports from "@/store/modules/reports/reports"
import crm from "@/store/modules/crm/crm"
import profileSettings from "@/store/modules/profile-settings/profile-settings"
import commonAddress from "@/store/modules/commonAddress/commonAddress";
import orders from "@/store/modules/products/orders/orders";

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    nav,
    auth,
    properties,
    characteristics,
    categories,
    alert,
    dialog,
    settings,
    classifiers,
    company,
    purchases,
    nomenclature,
    lists,
    suppliers,
    wareHouses,
    tabs,
    priceDiscounts,
    reports,
    filters,
    filesManager,
    crm,
    profileSettings,
    commonAddress,
    orders
  },
})
