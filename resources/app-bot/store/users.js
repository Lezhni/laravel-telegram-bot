import axios from "axios";

export default {
    state: {
        users: [],
        headings: [
            {key: 'id', label: 'ID', sortable: true, class: 'table-id'},
            {key: 'name', label: 'Имя', sortable: true},
            {key: 'email', label: 'Email', sortable: true},
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
            user: {},
            newUser: {}
        }
    },
    mutations: {
        setUsersInputSearchValue(state, val) {
            state.sortByInput = val
        },
        setUsersPerPage(state, value) {
            state.pages.perPage = value
        },
        setUsersCurrentPage(state, value) {
            state.pages.currentPage = value
        },
        setUsersTableItems(state, data) {
            state.users = data
        },
        setUsersChangeItemData(state, data) {
            state.modal.user = {
                email: data.email,
                id: data.id,
                name: data.name,
            }
        },
        setUsersAddItemData(state) {
            state.modal.newUser = {
                email: '',
                id: '',
                name: '',
            }
        }
    },
    actions: {
        async setUsersTableData({commit}) {
            return await axios.get(`/api/users`)
        },
        async setUsersChangeItem({commit}, id) {
            return await axios.get(`/api/users/${id}`)
        },
        async setUsersDeleteItem({commit}, id) {
            return await axios.delete(`/api/users/${id}`)
        },
        async setUsersUpdateUser({commit}, {id, form}) {
            const formData = new FormData(form)
            formData.append('id', id);
            return await axios.post(`/api/users/${id}`, formData)
        },
        async setUsersAddUser({commit}, {form}) {
            console.log('asdasd')
            const formData = new FormData(form)
            return await axios.post(`/api/users`, formData)
        },
        setUsersTableItems({commit}, data) {
            commit('setUsersTableItems', data)
        },
        setUsersChangeItemData({commit}, data) {
            commit('setUsersChangeItemData', data)
        },
        setUsersAddItemData({commit}) {
            commit('setUsersAddItemData')
        },
        setUsersInputSearchValue({commit}, value) {
            commit('setUsersInputSearchValue', value)
        },
        setUsersPerPage({commit}, value) {
            commit('setUsersPerPage', value)
        },
        setUsersCurrentPage({commit}, value) {
            commit('setCurrentPage', value)
        }
    },
    getters: {
        getUsersItems(state) {
            return state.users.filter(item => {
                for (let key in item) {
                    if (String(item[key]).toLowerCase().includes(state.sortByInput.toLowerCase())) {
                        return item
                    }
                }
            })
        },
        getUsersHeadings(state) {
            return state.headings
        },
        getUsersInputSearchValue(state) {
            return state.sortByInput
        },
        getUsersPerPage(state) {
            return state.pages.perPage
        },
        getUsersCurrentPage(state) {
            return state.pages.currentPage
        },
        getUsersPageOptions(state) {
            return state.pages.options
        },
        getUsersModalUser(state) {
            return state.modal.user
        },
        getUsersAddModalUser(state) {
            return state.modal.newUser
        }
    }
}