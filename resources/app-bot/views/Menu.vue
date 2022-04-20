<template>
  <b-container>
    <b-row class="pt-4">
      <b-col class="d-flex justify-content-between align-items-center flex-wrap">
        <h2>Пункты меню</h2>
        <b-button
            size="sm"
            variant="success"
            @click="addNewMenuItem"
            v-b-tooltip.hover
            title="Создать новый пункт меню"
        >
          <b-icon icon="plus"></b-icon> Создать
        </b-button>
      </b-col>
    </b-row>

    <b-row class="mt-4">
      <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
        <div class="d-flex">
          <label class="inputSearchLabel mr-3">Поиск</label>
          <b-form-input size="sm" v-model="textUsersSearchInput" placeholder="Введите текст"></b-form-input>
        </div>
        <div>
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
                  @click="getChangeMenuItemById(data.item.id)"
                  v-b-tooltip.hover
                  title="Изменить пункт меню"
              >
                <b-icon icon="wrench" variant="light"></b-icon>
              </b-button>
              <span>{{ data.item.id }}</span>
            </div>
          </template>

          <template #cell(text)="data">
            <p v-inner-html-dir>{{data.item.text}}</p>
          </template>

          <template #cell(actions)="data">
            <b-button-group>
              <b-button
                  variant="info"
                  size="sm"
                  @click="getChangeMenuItemById(data.item.id)"
                  v-b-tooltip.hover
                  title="Изменить пункт меню"
              >
                <b-icon icon="wrench" variant="light"></b-icon>
              </b-button>
              <b-button
                  variant="danger"
                  size="sm"
                  @click="deleteMenuItemById(data.item.id)"
                  v-b-tooltip.hover
                  title="Удалить пункт меню"
              >
                <b-icon icon="trash"></b-icon>
              </b-button>
            </b-button-group>
          </template>
        </b-table>
      </b-col>
    </b-row>

    <b-row>
      <b-col cols="12" sm="6">
        <b-pagination
            pills
            v-model="currentPage"
            :per-page="perPage"
            :total-rows="usersItems.length"
            aria-controls="my-table"
            class="mb-3 mb-sm-0"
        ></b-pagination>
      </b-col>
      <b-col
          cols="12" sm="6"
          class="d-flex justify-content-end"
      >
        <b-button
            size="sm"
            variant="success"
            @click="addNewMenuItem"
            v-b-tooltip.hover
            title="Создать новый пункт меню"
        >
          <b-icon icon="plus"></b-icon> Создать
        </b-button>
      </b-col>
    </b-row>

    <ChangeMenuTableItem :getMenuTableData="getMenuTableData"/>
    <AddMenuTableItem :getMenuTableData="getMenuTableData"/>
  </b-container>
</template>

<script>
import ChangeMenuTableItem from "../components/UI/modals/users/ChangeMenuTableItem";
import AddMenuTableItem from "../components/UI/modals/users/AddMenuTableItem";

export default {
  components: {ChangeMenuTableItem, AddMenuTableItem},
  directives: {
    innerHtmlDir: {
      inserted(el) {
        el.innerHTML = el.textContent
      },
      componentUpdated(el, binding, vnode) {
        el.innerHTML = vnode.children[0].text
      }
    }
  },
  methods: {
    async getMenuTableData() {
      await this.$store.dispatch('setIsLoading', true)
      await this.$store.dispatch('setMenuTableData')
          .then(res => {
            this.$store.dispatch('setMenuTableItems', res.data.data.nodes)
            this.$store.dispatch('setIsLoading', false)
          })
    },
    async getChangeMenuItemById(id) {
      await this.$store.dispatch('setIsLoading', true)
      await this.$store.dispatch('setMenuChangeItem', id)
          .then(res => {
            this.$store.dispatch('setMenuChangeItemData', res.data.data.node)
            this.$store.dispatch('setIsLoading', false)
            this.$root.$emit('bv::show::modal', 'changeMenuTableItem', '#btnShow')
          })
    },
    async deleteMenuItemById(id) {
      this.$bvModal.msgBoxConfirm(`Вы точно хотите удалить пункт меню?`, {
        title: 'Удаление пункта меню',
        size: 'sm',
        buttonSize: 'sm',
        okVariant: 'danger',
        okTitle: 'Да',
        cancelTitle: 'Нет',
        footerClass: 'footer-buttons p-2',
        hideHeaderClose: false,
        centered: true
      })
          .then( async value => {
            if (value) {
              await this.$store.dispatch('setMenuDeleteItem', id)
                  .then(res => {
                    if (res.status === 202) {
                      this.getMenuTableData()

                      this.$notify({
                        group: 'server-alerts',
                        title: 'Успешный запрос!',
                        type: 'success',
                        text: 'Пункт меню успешно удален'
                      });
                    }
                  }).catch(e => {
                    this.$notify({
                      group: 'server-alerts',
                      title: 'Ошибка!',
                      type: 'danger',
                      text: 'Пункт меню не удален'
                    });
                  })
            }
          })
          .catch(err => {
            console.error(err)
          })
    },
    addNewMenuItem() {
      this.$store.dispatch('setMenuAddItemData')

      this.$root.$emit('bv::show::modal', 'AddMenuTableItem', '#btnShow')
    }
  },
  computed: {
    usersItems() {
      return this.$store.getters['getMenuItems']
    },
    usersHeadings() {
      return this.$store.getters['getMenuHeadings']
    },
    usersProjectsFields() {
      return this.$store.getters['getMenuProjectsFields']
    },
    usersPermissionsFields() {
      return this.$store.getters['getMenuPermissionsFields']
    },
    textUsersSearchInput: {
      get() {
        return this.$store.getters['getMenuInputSearchValue']
      },
      set(value) {
        this.$store.dispatch('setMenuInputSearchValue', value)
      }
    },
    currentPage: {
      get() {
        return this.$store.getters['getMenuCurrentPage']
      },
      set(value) {
        this.$store.dispatch('setMenuCurrentPage', value)
      }
    },
    perPage: {
      get() {
        return this.$store.getters['getMenuPerPage']
      },
      set(value) {
        this.$store.dispatch('setMenuPerPage', value)
      }
    },
    pageOptions() {
      return this.$store.getters['getMenuPageOptions']
    },
  },
  created() {
    this.getMenuTableData()
  },
}
</script>

<style lang="scss">

</style>