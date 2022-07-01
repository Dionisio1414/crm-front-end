import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store/index.js'
import Category from "@/components/dashboard/categories/Category"
// Directories pages
import Locality from '@/components/dashboard/directoties/address/Locality'
import Branch from '@/components/dashboard/directoties/address/Branch'
// import CreateOrganizations from '@/components/dashboard/directoties/company/CreateOrganizations'
// import CreateDepartments from '@/components/dashboard/directoties/company/CreateDepartments'
import Company from '@/views/directories/Company.vue'
import Contractor from '@/components/dashboard/directoties/contractors/Contractor'
import ContractsСontractors from '@/components/dashboard/directoties/contractors/ContractsСontractors'
import IndividualDocuments from '@/components/dashboard/directoties/contractors/IndividualDocuments'
import ReasonCanceledPurchase from '@/components/dashboard/directoties/contractors/ReasonCanceledPurchase'
import ReasonReturningGoods from '@/components/dashboard/directoties/contractors/ReasonReturningGoods'
import Employee from '@/components/dashboard/directoties/employees/Employee'
import Users from '@/components/dashboard/directoties/employees/Users'
import EmployeesDocuments from '@/components/dashboard/directoties/employees/EmployeesDocuments'
import Positions from '@/components/dashboard/directoties/employees/Positions'
import TabTypes from "@/components/dashboard/directoties/prices/tabs/TabTypes"
import Countries from "@/components/dashboard/directoties/address/Countries"
import Currencies from "@/components/dashboard/directoties/classifiers/Currencies"
import Regions from "@/components/dashboard/directoties/address/Regions"
import MeasurementPoints from "@/components/dashboard/directoties/classifiers/MeasurementPoints"
import PriceDiscounts from "@/views/crm/PriceDiscounts/PriceDiscounts"
import Orders from "@/views/crm/Orders/Orders"
import Buyers from "@/views/crm/Buyers/Buyers"
import PageCrm from "@/components/dashboard/crm/leads/PageCrm"
import { MAIN_DOMAIN } from '@/domain'
import { PROTOCOL } from '@/domain'

import axios from 'axios'

Vue.use(VueRouter)

const routes = [
  {
    path: '*',
    name: 'PageNotFound',
    component: () => import('../components/app/page-not-found/PageNotFound.vue')
  },
  {
    path: '/',
    name: 'home',
    meta: {layout: 'home', auth: true},
    component: () => import('../views/Home.vue')
  },
  {
    path: '/cabinet',
    name: 'cabinet',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/Cabinet.vue')
  },
  {
    path: '/directories',
    name: 'directories',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/Directories.vue')
  },
  {
    path: '/system_management',
    name: 'SystemManagement',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/SystemManagement')
  },
  {
    path: '/system_management/settings',
    name: 'Settings',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/systemManagement/Settings')
  },{
    path: '/report',
    name: 'Report',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/report/Report')
  }, {
    path: '/crm',
    name: 'Crm',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/crm/Crm'),
    children: [
      {
        path: '/crm',
        name: 'crm',
        component: PageCrm,
        meta: { layout: 'main', auth: true},
      },
      {
        path: '/crm/orders',
        component: Orders,
        meta: { layout: 'main', auth: true },
      },
      {
        path: '/crm/price&discounts',
        component: PriceDiscounts,
        meta: { layout: 'main', auth: true },
      },
      {
        path: '/crm/buyers',
        component: Buyers,
        meta: { layout: 'main', auth: true },
      }
    ]
  }, {
    path: '/system_management/archive',
    name: 'Archive',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/systemManagement/Archive')
  },
  {
    path: '/system_management/translate',
    name: 'Translate',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/systemManagement/Translate')
  },
  {
    path: '/system_management/templates',
    name: 'Templates',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/systemManagement/Templates')
  },
  {
    path: '/directories/classifiers',
    name: 'classifiers',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/directories/Classifiers'),
    children: [
      {
        path: 'currencies',
        component: Currencies,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'currencies/:params',
        component: Currencies,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'measurement-points',
        component: MeasurementPoints,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'measurement-points/:params',
        component: MeasurementPoints,
        meta: {layout: 'main', auth: true},
      },
    ]
  },
  {
    path: '/directories/prices',
    name: 'price',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/directories/Price/Prices'),
    children: [
      {
        path: 'type-price',
        component: TabTypes,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'type-price/:params',
        component: TabTypes,
        meta: {layout: 'main', auth: true},
      },
    ]
  },
  {
    path: '/products',
    name: 'products',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/Products.vue')
  },
  {
    path: '/products/nomenclature',
    name: 'nomenclature',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/products/Nomenclature/Nomenclature.vue')
  },
  {
    path: '/products/nomenclature/:params',
    name: 'nomenclature',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/products/Nomenclature/Nomenclature.vue')
  },
  {
    path: '/products/categories',
    name: 'categories',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/products/Categories.vue'),
    children: [
      {
        path: '/products/categories/:params',
        meta: {layout: 'main', auth: true},
        components: {
          helper: Category
        }
      }
    ]
  },
  {
    path: '/products/properties',
    name: 'properties-products',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/products/PropertiesProducts.vue')
  },
  {
    path: '/products/characteristic',
    name: 'characteristic',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/products/CharacteristicProducts.vue')
  },
  {
    path: '/products/warehouses',
    name: 'Warehouses',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/products/Warehouses/Warehouses.vue')
  },
  {
    path: '/products/warehouses/:params',
    name: 'Warehouses',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/products/Warehouses/Warehouses.vue')
  },
  {
    path: '/products/purchases',
    name: 'Purchases',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/products/Purchases/Purchases.vue')
  },
  {
    path: '/products/purchases/:params',
    name: 'Purchases',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/products/Purchases/Purchases.vue')
  },
  {
    path: '/products/suppliers',
    name: 'Suppliers',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/products/Suppliers/Suppliers.vue'),
  },
  {
    path: '/products/suppliers/:params',
    name: 'Suppliers',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/products/Suppliers/Suppliers.vue'),
  },
  {
    path: '/products/import-export',
    name: 'ImportExport',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/products/ImportExport.vue')
  },
  {
    path: '/products/delivery',
    name: 'Delivery',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/products/Delivery.vue')
  },
  {
    path: '/directories/address',
    name: 'directories',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/directories/AddressData.vue'),
    children: [
      {
        path: 'cities',
        name: "cities",
        component: Locality,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'cities/:params',
        name: "cities",
        component: Locality,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'branch',
        name: "branch",
        component: Branch,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'branch/:params',
        name: "branch",
        component: Branch,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'countries',
        name: "countries",
        component: Countries,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'countries/:params',
        name: "countries",
        component: Countries,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'regiones',
        name: "regiones",
        component: Regions,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'regiones/:params',
        name: "regiones",
        component: Regions,
        meta: {layout: 'main', auth: true},
      },
    ]
  },
  {
    path: '/directories/company',
    name: 'directories',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/directories/Company.vue'),
    children: [
      {
        path: 'organizations',
        component: Company,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'departments',
        component: Company,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'departments/:params',
        component: Company,
        meta: {layout: 'main', auth: true},
      }
    ]
  },
  {
    path: '/directories/employees',
    name: 'directories',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/directories/Employees.vue'),
    children: [
      {
        path: 'employee',
        component: Employee,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'employee/:params',
        component: Employee,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'users',
        component: Users,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'users/:params',
        component: Users,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'positions',
        component: Positions,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'positions/:params',
        component: Positions,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'employees-documents',
        component: EmployeesDocuments,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'employees-documents/:params',
        component: EmployeesDocuments,
        meta: {layout: 'main', auth: true},
      }
    ]
  },
  {
    path: '/directories/contractors',
    name: 'directories',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/directories/Contractors.vue'),
    children: [
      {
        path: 'contractor',
        component: Contractor,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'contracts-contractors',
        component: ContractsСontractors,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'contracts-contractors/:params',
        component: ContractsСontractors,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'individual-documents',
        component: IndividualDocuments,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'individual-documents/:params',
        component: IndividualDocuments,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'reason-returning-goods',
        component: ReasonReturningGoods,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'reason-returning-goods/:params',
        component: ReasonReturningGoods,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'reason-canceled-purchase',
        component: ReasonCanceledPurchase,
        meta: {layout: 'main', auth: true},
      },
      {
        path: 'reason-canceled-purchase/:params',
        component: ReasonCanceledPurchase,
        meta: {layout: 'main', auth: true},
      }
    ]
  },
  {
    path: '/directories/prices',
    name: 'directories',
    meta: {layout: 'main', auth: true},
    component: () => import('../views/directories/Price/Prices.vue')
  },
  {
    path: '/login',
    name: 'login',
    meta: {layout: 'empty'},
    component: () => import('../views/Login.vue')
  },
  {
    path: '/testing',
    name: 'testing',
    meta: {layout: 'main'},
    component: () => import('../views/testing.vue')
  },
  {
    path: '/register',
    name: 'register',
    meta: {layout: 'empty'},
    component: () => import('../views/Register.vue')
  },
  {
    path: '/social',
    name: 'social',
    meta: { layout: 'empty' },
    component: () => import('../views/Social.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

console.log('BASE_URL', process.env.BASE_URL)

// console.log(process.env)

// FOR PROD

if(location.host.startsWith('localhost')) {
  // FOR LOCALHOST
  router.beforeEach((to, from, next) => {

    if(to.matched.some(record => record.meta.auth)) {
      if (store.getters.isLoggedIn) {
        console.log('Login true', process.env)
        next()
        return
      }
      next('/login')
    } else {
      console.log('Login false')
      next()
    }
  })
} else {
  router.beforeEach( async (to, from, next) => {
    // console.log(store.getters, localStorage.getItem('token'))
    // axios.get(`https://gateway.esl.in.ua/api/v1/user/checkSession`)
    // axios.get(`https://gateway.esl.in.ua/api/v1/user/checkSession`)
    //   .then({ data: { data: user, access_token } })
    // localStorage.setItem('token', access_token)
    // this.$store.dispatch('changeUserData', user)
    // console.log('storeUser', JSON.parse(localStorage.getItem('domain')).split('.')[0])

    if(localStorage.getItem('token') !== null && localStorage.getItem('token') === "undefined") {
      console.log('CLEAR!!!!!!')
      localStorage.clear()
    }

    if(location.pathname === '/login' && location.host !== MAIN_DOMAIN) window.location.href = `${PROTOCOL}://${JSON.parse(localStorage.getItem('user')).company.user_main_domain}`

    if(to.matched.some(record => record.meta.auth)) {
      // next()
      // if(localStorage.getItem('token') !== null && localStorage.getItem('token') === "undefined") {
      //   console.log('CLEAR!!!!!!')
      //   localStorage.clear()
      // }

      if(location.host === MAIN_DOMAIN) { // Main domain
        if(location.search === "?logout") localStorage.clear()
        if(localStorage.getItem('token')) {
          if(localStorage.getItem('user') !== null) {
            window.location.href = `${PROTOCOL}://${JSON.parse(localStorage.getItem('user')).company.user_main_domain}`
          } else {
            window.location.href = `${PROTOCOL}://${JSON.parse(localStorage.getItem('user_main_domain'))}`
          }
        }
        console.log('LOGIN!!!!')
        next('/login')
      } else { // Sundomain
        console.log('Not isLoggined', store, localStorage.getItem('domain'))
        if(localStorage.getItem('domain') !== null) {
          axios.get(`${PROTOCOL}://${JSON.parse(localStorage.getItem('domain'))}/api/v1/user/auth`, {
            headers: {
              'Authorization': `Bearer ${JSON.parse(localStorage.getItem('token'))}`,
              'Cache-Control': 'no-cache'
            }
          }).then(response => {
            console.log('response', response.data.data)
            if(response.data.data) {
              console.log('access_token', true)
              store.dispatch('updateUser', response.data.data)
            }
          }).catch(err => {
            console.log(err)
            localStorage.clear()
            window.location.href = `${PROTOCOL}://${MAIN_DOMAIN}/login/?logout`
          })
        }

        if (!store.getters.isLoggedIn && location.search) {
          console.log('isLoggined')
          const user_id = location.search.split('=')[1]
          axios.post(`${PROTOCOL}://gateway.${MAIN_DOMAIN}/api/v1/user/login/verify`, { data: { user_id } }).then(data => {
            console.log(data)
            if(data.data.message) {
              window.location.href = `${PROTOCOL}://${MAIN_DOMAIN}/login/?logout`
            } else {
              store.dispatch('updateLogin', data.data)
            }
          })
          next()
          return
        } else if(store.getters.isLoggedIn) {
          next()
          return
        } else if(!store.getters.isLoggedIn && !location.search.startsWith('?user_id')) {
          window.location.href = `${PROTOCOL}://${MAIN_DOMAIN}/login/?logout`
        }

        // TODO Logout window.location.href = "https://esl.in.ua/login/?logout"
      }
    } else {
      next()
    }
   })
}

export default router


// Local storage error json
// Редирект если мы залогинены и домены с компанией не совпадают
// Logout query
