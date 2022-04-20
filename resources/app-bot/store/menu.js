import axios from "axios";

export default {
    state: {
        menu: [],
        headings: [
            {key: 'id', label: 'ID', sortable: true, class: 'table-id'},
            {key: 'title', label: 'Заголовок', sortable: true},
            {key: 'text', label: 'Текст', sortable: true},
            // {key: 'link', label: 'Ссылка', sortable: true},
            {key: 'actions', label: 'Действия', class: 'table-action'},
        ],
        sortByInput: '',
        pages: {
            isEmpty: false,
            perPage: 10,
            currentPage: 1,
            options: [
                {value: 10, text: 10},
                {value: 25, text: 25},
                {value: 50, text: 50},
                {value: 100, text: 100},
            ]
        },
        modal: {
            menu: {},
            newMenuItem: {}
        }
    },
    mutations: {
        setMenuInputSearchValue(state, val) {
            state.sortByInput = val
        },
        setMenuPerPage(state, value) {
            state.pages.perPage = value
        },
        setMenuCurrentPage(state, value) {
            state.pages.currentPage = value
        },
        setMenuTableItems(state, data) {
            state.menu = data
        },
        setMenuChangeItemData(state, data) {
            state.modal.menu = {
                title: data.title,
                text: data.text,
                links: data.links,
                id: data.id
            }
        },
        setMenuAddItemData(state) {
            state.modal.newMenuItem = {
                id: '',
                title: '',
                links: '',
                text: '',
            }
        },
        setMenuRemoveLinkById(state, id) {
            state.modal.menu.links.splice(id, 1)
        },
        setMenuAddLink(state) {
            if (state.modal.menu.links) {
                state.modal.menu.links.push({
                    label: '',
                    link: ''
                })
            } else {
                state.modal.menu.links = [
                    {label: '', link: ''}
                ]
            }
        },
    },
    actions: {
        async setMenuTableData({commit}) {
            return await axios.get(`/api/telegram-nodes`)
        },
        async setMenuChangeItem({commit}, id) {
            return await axios.get(`/api/telegram-nodes/${id}`)
        },
        async setMenuDeleteItem({commit}, id) {
            return await axios.delete(`/api/telegram-nodes/${id}`)
        },
        async setMenuUpdateItem({commit}, {id, formData}) {
            formData.append('id', id);
            return await axios.post(`/api/telegram-nodes/${id}`, formData)
        },
        async setMenuAddItem({commit}, formData) {
            return await axios.post(`/api/telegram-nodes`, formData)
        },
        setMenuTableItems({commit}, data) {
            commit('setMenuTableItems', data)
        },
        setMenuChangeItemData({commit}, data) {
            commit('setMenuChangeItemData', data)
        },
        setMenuAddItemData({commit}) {
            commit('setMenuAddItemData')
        },
        setMenuInputSearchValue({commit}, value) {
            commit('setMenuInputSearchValue', value)
        },
        setMenuPerPage({commit}, value) {
            commit('setMenuPerPage', value)
        },
        setMenuCurrentPage({commit}, value) {
            commit('setMenuCurrentPage', value)
        },
        setMenuRemoveLinkById({commit}, id) {
            commit('setMenuRemoveLinkById', id)
        },
        setMenuAddLink({commit}) {
            commit('setMenuAddLink')
        },
    },
    getters: {
        getMenuItems(state) {
            return state.menu.filter(item => {
                for (let key in item) {
                    if (String(item[key]).toLowerCase().includes(state.sortByInput.toLowerCase())) {
                        return item
                    }
                }
            })
        },
        getMenuHeadings(state) {
            return state.headings
        },
        getMenuInputSearchValue(state) {
            return state.sortByInput
        },
        getMenuPerPage(state) {
            return state.pages.perPage
        },
        getMenuCurrentPage(state) {
            return state.pages.currentPage
        },
        getMenuPageOptions(state) {
            return state.pages.options
        },
        getMenuModalItem(state) {
            return state.modal.menu
        },
        getMenuAddModalUser(state) {
            return state.modal.newMenuItem
        }
    }
}