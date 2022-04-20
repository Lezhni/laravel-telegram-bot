import axios from "axios";

export default {
    state: {
        reorderItems: []
    },
    mutations: {
        setReorderItems(state, data) {
            state.reorderItems = data.map(item => {
                return {
                    id: item.id,
                    text: item.text,
                    children: item.children ? item.children : []
                }
            })
        },
        setReorderUpdateItems(state, items) {
            state.reorderItems = items
        }
    },
    actions: {
        async setReorderData({commit}) {
            return await axios.get(`/api/telegram-nodes/reorder`)
        },
        async setReorderSaveData({commit}, items) {
            return await axios.post(`/api/telegram-nodes/reorder`, items)
        },
        setReorderItems({commit}, data) {
            commit('setReorderItems', data)
        },
        setReorderUpdateItems({commit}, data) {
            commit('setReorderUpdateItems', data)
        }
    },
    getters: {
        getReorderItems(state) {
            return state.reorderItems
        },
    }
}