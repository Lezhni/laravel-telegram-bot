import axios from "axios";

export default {
    state: {
        feedbackItems: []
    },
    mutations: {
        setFeedbackItems(state, items) {
            state.feedbackItems = items
        }
    },
    actions: {
        async setFeedbackData({commit}) {
            return await axios.get(`/api/testimonials`)
        },
        async setDeleteFeedbackById({commit}, id) {
            return await axios.delete(`/api/testimonials/${id}`)
        },
        setFeedbackItems({commit}, data) {
            commit('setFeedbackItems', data)
        },
    },
    getters: {
        getFeedbackItems(state) {
            return state.feedbackItems
        },
    }
}