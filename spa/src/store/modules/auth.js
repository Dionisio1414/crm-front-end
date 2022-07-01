import axios from "axios"
import { MAIN_DOMAIN } from '@/domain'
import { PROTOCOL } from '@/domain'

export default {
  state: {
    isAuth: false,
    status: '',
    token: localStorage.getItem('token') === 'undefined' ? null : JSON.parse(localStorage.getItem('token') || null),
    user: JSON.parse(localStorage.getItem('user') || null),
    domain: null,
    protocol: window.location.protocol,
  },
  mutations: {
    authRequest(state){
      state.status = 'loading'
    },
    setUserCompany ( state , newCompanyInfo) {
      state.user.company = { ...newCompanyInfo }
    },
    setToken(state, token){
      state.status = 'success'
      state.token = token
      localStorage.setItem('token', JSON.stringify(token))
    },
    authError(state){
      state.status = 'error'
    },
    logout(state){
      console.log(PROTOCOL)
      state.status = ''
      state.token = ''
      localStorage.removeItem('user')
      localStorage.clear()
      window.location.href = `${PROTOCOL}://${MAIN_DOMAIN}/login/?logout`

    },
    redirectDomain(state) {
      if(document.location.hostname != `${JSON.parse(localStorage.getItem('domain')).split('.')[0]}.${state.user.company.main_domain}` && !document.location.hostname.includes('localhost')) {
        state.domain = JSON.parse(localStorage.getItem('domain')) || `${PROTOCOL}//${JSON.parse(localStorage.getItem('domain')).split('.')[0]}.${state.user.company.main_domain}/`
      } else {
        console.log(document.location.hostname)
      }
    },
    setUser(state, user) {

      if(user) {
        localStorage.setItem('domain', JSON.stringify(user.company.domain))
        localStorage.setItem('user', JSON.stringify(user))
        state.user = user
      }
    },
    registerSocial() {
      console.log('111')
    },
    loginSocial() {
      console.log('222')
    }
  },
  actions: {
    async changeAuth({ commit, getters, state }, payload) {
      try {
        const { data } = await axios.post(`${PROTOCOL}://${JSON.parse(localStorage.getItem('domain') || getters.domain)}/api/v1/user/auth`,
            { data: { ...payload } },
            {
                headers: {'Authorization': `Bearer ${state.token}`}
            })
          console.log(data.data, commit)
          await commit('setUser', data.data)
      } catch (e) {
          console.log(e)
          return false
      }
    },
    async changeCompany ({commit, getters, state}, data) {
      data.domain += `.gateway.${MAIN_DOMAIN}`
      try {
        const newData = await axios.post(`${PROTOCOL}://${JSON.parse(localStorage.getItem('domain') || getters.domain)}/api/v1/settings/main`,
            {data: data},
            {
          headers: {
            'Authorization': `Bearer ${state.token}`
          }
        })
        commit('setUserCompany', newData.data.data)
        commit('setUser', state.user)
      } catch (e) {
        console.log(e);
      }
    },
    async userVerify({commit, rootState}, payload) {
      const token = await rootState.auth.token
      try {
          const { data } = await axios.post(`${PROTOCOL}://gateway.${MAIN_DOMAIN}/api/v1/user/login/verify`,
              { data: payload },
              {
                  headers: {'Authorization': `Bearer ${token}`}
              })
          // commit('updateValid', data)
          console.log('commit', commit, data)
          return data
      } catch (e) {
          console.log(e)
          return false
      }
    },
    resendAction({state}, data) {
      return new Promise((resolve, reject) => {
          axios.post(`${PROTOCOL}://gateway.${MAIN_DOMAIN}/api/v1/user/resend/verification_token`, { data: data }, {
            headers: {
              'Authorization': `Bearer ${state.token}`
            }
          }).then(resp => {
            console.log('resp', resp)
            resolve(resp)
          }).catch(err => {
            console.log('err', err)
            reject(err)
          })
        })
    },
    loginSocial({ commit }, socialData) {
      return new Promise((resolve, reject) => {
        commit('loginSocial')
        let data = {
          email: socialData.email,
          provider: socialData.provider
        }
        console.log(data)
        axios.post(`${PROTOCOL}://gateway.${MAIN_DOMAIN}/api/v1/user/login/social`, { data }, {
          headers: {
            "Access-Control-Allow-Origin": "*",
          }
        })
          .then(resp => {
            const token = resp.data.access_token
            const user = resp.data.user
            axios.defaults.headers.common['Authorization'] = token
            commit('setUser', user)
            commit('setToken', token)
            console.log(resp)
            resolve(resp)
          })
          .catch(err => {
            console.log(err)
            reject(err)
          })
      })
    },
    registerSocial({commit, getters}, socialData) {
      console.log('socialData', socialData, getters)
      return new Promise((resolve, reject) => {
        commit('registerSocial')
        let data = {
          email: socialData.email,
          provider: socialData.provider
        }
        console.log(data)
        axios.post(`${PROTOCOL}://gateway.${MAIN_DOMAIN}/api/v1/user/register/social`, {data}, {
          headers: {
            "Access-Control-Allow-Origin": "*",
          }
        })
          .then(resp => {
            console.log('resp', resp.data)
            if(!resp.data.message) {
              resolve(resp)
              const token = resp.data.data.access_token
              const user = resp.data.data.user
              localStorage.setItem('token', JSON.stringify(token))
              axios.defaults.headers.common['Authorization'] = token
              commit('setToken', token)
              commit('setUser', user)
            } else {
              resolve(resp)
            }
          })
          .catch(err => {
            console.log(err)
            reject(err)
          })
      })
    },
    register({commit}, user){
      return new Promise((resolve, reject) => {
        commit('authRequest')
        let data = ''
        if (user.email) {
          data = {
            email: user.email,
            lang: user.lang
          }
        } else if (user.phone) {
          data = {
            phone: user.phone,
            lang: user.lang
          }
        }

        axios.post(`${PROTOCOL}://gateway.${MAIN_DOMAIN}/api/v1/user/register`, { data: data }, {
          headers: {
            'Content-Type': 'application/json',
            "Access-Control-Allow-Origin": "*",
          }
        })
          .then(resp => {
            console.log('Register data', resp, resp.data.message)
            setTimeout(() => { 
              resolve(resp) 
              console.log('End registration'), 20000})
          })
          .catch(err => {
            // console.log('Register data error', err)
            reject(err)
          })
      })
    },
    login({commit}, user){
      console.log(PROTOCOL)
      return new Promise((resolve, reject) => {
        commit('authRequest')
        let data = ''
        if (user.email) {
          data = {
            email: user.email,
            password: user.password
          }
        } else if (user.phone) {
          data = {
            phone: user.phone,
            password: user.password
          }
        }
        axios.post(`${PROTOCOL}://gateway.${MAIN_DOMAIN}/api/v1/user/login`, {data: data})
        .then(resp => {

            const token = resp.data.access_token
            const user = resp.data.user

            // localStorage.setItem('token', JSON.stringify(token))
            axios.defaults.headers.common['Authorization'] = token
            commit('setUser', user)
            commit('setToken', token)
            // commit('setUser', user)
            // commit('redirectDomain', user)
            resolve(resp)
          })
          .catch(err => {
            commit('authError')
            localStorage.removeItem('token')
            reject(err)
          })
      })
    },
    updateLogin({ commit }, payload) {
      commit('setToken', payload.access_token)
      commit('setUser', payload.user)
    },
    updateToken({ commit }, payload) {
      commit('setToken', payload)
    },
    updateUser({ commit }, payload) {
      commit('setUser', payload)
    },
    changeUserData({commit}, user) {
      commit('setUser', user)
    },
    updateDomain({commit}, domain) {
      commit('setDomain', domain)
    },
    getUid() {
      return 77
    },
    logout({commit, getters, rootState}){
      const domain = rootState.auth.user.company.domain
      axios.get(`${PROTOCOL}://${domain}/api/v1/user/logout`, {
        headers: { 
					'Authorization': `Bearer ${getters.token}`
				}
      }).then(data => {
        console.log(data)
        localStorage.removeItem('token')
        delete axios.defaults.headers.common['Authorization']
        commit('logout')
      })
    },
    async stepsAsyncValidation({ rootState }, payload) {
      const token = await rootState.auth.token
      const domain = await rootState.auth.user.company.domain
      // const protocol = await rootState.auth.protocol 
      console.log('payload', payload)
      try {
          const response = await axios.post(`${PROTOCOL}://${domain}/api/v1/user/update-async-validations`,
              { ...payload },
              {
                  headers: {'Authorization': `Bearer ${token}`}
              })
            
          // commit('updateValid', data)
          return response
      } catch (e) {
          console.log(e)
      }
    },
    async getUserAuth({commit, rootState}){      
      const domain = await rootState.auth.user.company.domain
      const token = await rootState.auth.token
      try {
          const { data: { data:user } } = await axios.get(`${PROTOCOL}://${domain}/api/v1/user/auth`,
              {
                  headers: {'Authorization': `Bearer ${token}`}
              })
            
          await commit('setUser', user)
          console.log(user)
      } catch (e) {
          console.log(e)
      }
    },
  },
  getters: {
    isLoggedIn: state => !!state.token,
    token: state => state.token,
    user: s => s.user,
    domain: s => s.domain,
    protocol: s => s.protocol,
    company: s => s.user.company,
  }

}