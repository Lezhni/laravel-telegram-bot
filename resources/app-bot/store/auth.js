export default {
    state: {
        user: {},
        loginForm: {
            email: '',
            password: ''
        },
        authenticated: false
    },
    mutations: {
        setUserEmail(state, email) {
            state.loginForm.email = email
        },
        setUserPassword(state, password) {
            state.loginForm.password = password
        },
        setResetLoginForm(state) {
            state.loginForm.email = ''
            state.loginForm.password = ''
        },
        setAuth(state, auth) {
            this.state.authenticated = auth
        },
        setUser(state, user) {
            state.authenticated = true
            state.user = user
        },
        clearUser(state) {
            state.authenticated = false
            state.user = {}
        }
    },
    actions: {
        setUserEmail({commit}, email) {
            commit('setUserEmail', email)
        },
        setUserPassword({commit}, password) {
            commit('setUserPassword', password)
        },
        setResetLoginForm({commit}) {
            commit('setResetLoginForm')
        },
        setUser({commit}, user) {
            commit('setUser', user)
        },
        async loginUser({commit}, user) {
            return await axios.post('/api/login', user)
        },
        async logoutUser({commit}) {
            return await axios.post('/api/logout')
        },
        setAuth({commit}, auth) {
            commit('setAuth', auth)
        },
        clearUser({commit}) {
            commit('clearUser')
        }
    },
    getters: {
        getUser(state) {
            return state.loginForm
        },
        getUserEmail(state) {
            return state.loginForm.email
        },
        getUserPassword(state) {
            return state.loginForm.password
        },
        getAuth(state) {
            return state.authenticated
        },
        getUserStatus(state) {
            return state.user.name
        },
        getPermissions(state) {
            return state.user.permissions
        }
    }
}
