<template>
  <b-container>
    <b-row class="pt-4">
      <b-col>
        <h2>Пользователи</h2>
      </b-col>
    </b-row>

    <b-row class="mt-4">
      <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
        <div class="d-flex">
          <label class="inputSearchLabel mr-3">Поиск</label>
          <b-form-input size="sm" v-model="textUsersSearchInput" placeholder="Введите текст"></b-form-input>
        </div>
        <div class="d-flex">
          <b-form-select v-model="perPage" :options="pageOptions" size="sm"></b-form-select>
        </div>
      </div>
    </b-row>

    <b-row class="responsive-row">
      <b-col>
        <b-table
            striped
            bordered
            hover
            responsive
            :items="usersItems"
            :fields="usersHeadings"
            :per-page="perPage"
            :current-page="currentPage"
        >

          <template #cell(id)="data">
            <div class="d-flex justify-content-start align-items-center">
              <b-button
                  variant="info"
                  size="sm"
                  class="mr-4"
                  @click="getChangeUsersItemById(data.item.id)"
                  v-b-tooltip.hover
                  title="Изменить пользователя"
              >
                <b-icon icon="wrench" variant="light"></b-icon>
              </b-button>
              <span>{{ data.item.id }}</span>
            </div>
          </template>

          <template #cell(actions)="data">
            <b-button-group>
              <b-button
                  variant="info"
                  size="sm"
                  @click="getChangeUsersItemById(data.item.id)"
                  v-b-tooltip.hover
                  title="Удалить пользователя"
              >
                <b-icon icon="wrench" variant="light"></b-icon>
              </b-button>
              <b-button
                  variant="danger"
                  size="sm"
                  @click="deleteUsersItemById(data.item.id)"
                  v-b-tooltip.hover
                  title="Изменить пользователя"
              >
                <b-icon icon="trash"></b-icon>
              </b-button>
            </b-button-group>
          </template>
        </b-table>
      </b-col>
    </b-row>

    <b-row>
      <b-col>
        <b-pagination
            pills
            v-model="currentPage"
            :per-page="perPage"
            :total-rows="usersItems.length"
            aria-controls="my-table"
            class="m-0"
        ></b-pagination>
      </b-col>
      <b-col
          class="d-flex justify-content-end"
      >
        <b-button
            size="sm"
            variant="success"
            @click="addNewUser"
            v-b-tooltip.hover
            title="Создать нового пользователя"
        >
          <b-icon icon="plus"></b-icon> Создать
        </b-button>
      </b-col>
    </b-row>

    <ChangeUsersTableItem :getUsersTableData="getUsersTableData"/>
    <AddUsersTableItem :getUsersTableData="getUsersTableData"/>
  </b-container>
</template>

<script>
import ChangeUsersTableItem from "../components/UI/modals/users/ChangeUsersTableItem";
import AddUsersTableItem from "../components/UI/modals/users/AddUsersTableItem";

export default {
  data() {
    return {
      selected: [],
      options: [
        {text: 'Orange', value: 'orange'},
        {text: 'Apple', value: 'apple'},
        {text: 'Pineapple', value: 'pineapple'},
        {text: 'Grape', value: 'grape'}
      ]
    }
  },
  components: {ChangeUsersTableItem, AddUsersTableItem},
  methods: {
    async getUsersTableData() {
      await this.$store.dispatch('setIsLoading', true)
      await this.$store.dispatch('setUsersTableData')
          .then(res => {
            this.$store.dispatch('setUsersTableItems', res.data.data.users)
            this.$store.dispatch('setIsLoading', false)
          })
    },
    async getChangeUsersItemById(id) {
      await this.$store.dispatch('setIsLoading', true)
      await this.$store.dispatch('setUsersChangeItem', id)
          .then(res => {
            this.$store.dispatch('setUsersChangeItemData', res.data.data.user)
            this.$store.dispatch('setIsLoading', false)
            this.$root.$emit('bv::show::modal', 'changeUsersTableItem', '#btnShow')
          })
    },
    async deleteUsersItemById(id) {
      this.$bvModal.msgBoxConfirm(`Вы точно хотите удалить пользователя?`, {
        title: 'Удаление пользователя',
        size: 'sm',
        buttonSize: 'sm',
        okVariant: 'danger',
        okTitle: 'Да',
        cancelTitle: 'нет',
        footerClass: 'footer-buttons p-2',
        hideHeaderClose: false,
        centered: true
      })
          .then( async value => {
            if (value) {
              await this.$store.dispatch('setUsersDeleteItem', id)
                  .then(res => {
                    if (res.status === 202) {
                      this.getUsersTableData()

                      this.$notify({
                        group: 'server-alerts',
                        title: 'Успешный запрос!',
                        type: 'success',
                        text: 'Пользователь успешно удален'
                      });
                    }
                  }).catch(e => {
                    this.$notify({
                      group: 'server-alerts',
                      title: 'Ошибка!',
                      type: 'danger',
                      text: 'Пользователь не удален'
                    });
                  })
            }
          })
          .catch(err => {
            console.error(err)
          })
    },
    addNewUser() {
      this.$store.dispatch('setUsersAddItemData')

      this.$root.$emit('bv::show::modal', 'AddUsersTableItem', '#btnShow')
    }
  },
  computed: {
    usersItems() {
      return this.$store.getters['getUsersItems']
    },
    usersHeadings() {
      return this.$store.getters['getUsersHeadings']
    },
    usersProjectsFields() {
      return this.$store.getters['getUsersProjectsFields']
    },
    usersPermissionsFields() {
      return this.$store.getters['getUsersPermissionsFields']
    },
    textUsersSearchInput: {
      get() {
        return this.$store.getters['getUsersInputSearchValue']
      },
      set(value) {
        this.$store.dispatch('setUsersInputSearchValue', value)
      }
    },
    currentPage: {
      get() {
        return this.$store.getters['getUsersCurrentPage']
      },
      set(value) {
        this.$store.dispatch('setUsersCurrentPage', value)
      }
    },
    perPage: {
      get() {
        return this.$store.getters['getUsersPerPage']
      },
      set(value) {
        this.$store.dispatch('setUsersPerPage', value)
      }
    },
    pageOptions() {
      return this.$store.getters['getUsersPageOptions']
    },
  },
  created() {
    this.getUsersTableData()
  },
}
</script>

<style scoped>

</style>