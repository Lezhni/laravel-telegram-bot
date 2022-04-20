export default {
    state: {
        isLoading: false,
        contentLoading: true
    },
    mutations: {
        setIsLoading(state, isLoading) {
            state.isLoading = isLoading
        },
        setContentLoading(state, value) {
            state.contentLoading = value
        }
    },
    actions: {
        setIsLoading({commit}, isLoading) {
            commit('setIsLoading', isLoading)
        },
        setContentLoading({commit}, value) {
            commit('setContentLoading', value)
        }
    },
    getters: {
        getIsLoading(state) {
            return state.isLoading
        },
        getContentLoading(state) {
            return state.contentLoading
        }
    }
}
