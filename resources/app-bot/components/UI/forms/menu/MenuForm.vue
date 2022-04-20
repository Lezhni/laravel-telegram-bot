<template>
  <div>
    <b-form @submit.prevent="submitUser($event)" @reset="" id="formUser">
      <b-form-row>
        <b-col cols="12">
          <b-form-group
              id="`input-group-menu-1`"
              label="Заголовок"
              label-for="input-menu-title"
              label-class="forms-label"
          >
            <b-form-input
                id="input-menu-title"
                name="title"
                v-model="menuItems.title"
                type="text"
                placeholder="Введите заголовок"
                :required="true"
            ></b-form-input>
          </b-form-group>
        </b-col>

        <b-col cols="12">
          <b-form-group
              label="Текст"
              label-for="input-menu-text"
          >
            <vue-editor v-model="menuItems.text" :editor-toolbar="customToolbar"></vue-editor>
          </b-form-group>
        </b-col>
      </b-form-row>

      <input type="hidden" name="_method" value="PUT">

      <b-form-row v-for="(link, idxLink) in menuItems.links" :key="idxLink">
        <b-col cols="12" md="5">
          <b-form-group
              :id="`input-group-menu-${idxLink}`"
              :label="`Текст ссылки ${idxLink + 1}`"
              :label-for="`input-menu-link-label-${idxLink}`"
              label-class="forms-label"
          >
            <b-form-input
                :id="`input-menu-link-label-${idxLink}`"
                :name="`links[${idxLink}][label]`"
                v-model="link.label"
                type="text"
                placeholder="Введите текст ссылки"
                :required="false"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="5">
          <b-form-group
              :id="`input-group-menu-${idxLink}`"
              :label="`Ссылка ${idxLink + 1}`"
              :label-for="`input-menu-link-${idxLink}`"
              label-class="forms-label"
          >
            <b-form-input
                :id="`input-menu-link-${idxLink}`"
                :name="`links[${idxLink}][link]`"
                v-model="link.link"
                type="url"
                placeholder="https://example.com"
                pattern="(https|http)://.*" size="30"
                :required="true"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="1">
          <div class="mb-2">Удалить</div>
          <b-button @click.prevent="removeLink(idxLink)" size="md" variant="danger" class="mb-2">
            <b-icon icon="trash"></b-icon>
          </b-button>
        </b-col>
      </b-form-row>

      <b-form-row class="mb-3">
        <b-col>
          <b-button
              @click.prevent="addInputRow"
              block
              variant="info"
          >Добавить ссылку
          </b-button>
        </b-col>
      </b-form-row>

      <b-form-row class="mb-3">
        <b-col>
          <b-button
              type="submit"
              variant="success"
              size="sm"
              class="float-right"
          >
            Сохранить
          </b-button>
        </b-col>
      </b-form-row>
    </b-form>

  </div>
</template>

<script>
import {VueEditor} from "vue2-editor";

export default {
  components: {VueEditor},
  data() {
    return {
      passwordError: [],
      htmlForEditor: "",
      customToolbar: [
        ["bold", "italic", "underline", "strike"],
        ["link"]
      ]
    }
  },
  props: {
    getMenuTableData: {
      type: Function,
      required: true
    }
  },
  methods: {
    async submitUser(e) {
      const id = this.$store.getters['getMenuModalItem'].id
      const formData = new FormData(e.target)
      console.log(this.menuItems.text)
      formData.append('text', this.menuItems.text);

      await this.$store.dispatch('setIsLoading', true)
      await this.$store.dispatch('setMenuUpdateItem', {id: id, formData})
          .then(res => {
            if (res.status === 204) {
              this.$root.$emit('bv::hide::modal', 'changeMenuTableItem', '#btnShow')
              this.getMenuTableData()

              this.$notify({
                group: 'server-alerts',
                title: 'Успешный запрос!',
                type: 'success',
                text: 'Пункт меню успешно сохранен'
              });
            }
          })
          .catch(err => {

            this.$notify({
              group: 'server-alerts',
              title: 'Ошибка!',
              type: 'danger',
              text: 'Пункт меню не сохранен'
            });

            if (typeof err.response.data.message === 'string') {
              this.passwordError.length = 0
              this.passwordError.push(err.response.data.message)
            } else if (typeof err.response.data.message === 'object') {
              this.passwordError = err.response.data.message.password ? err.response.data.message.password : []
            }

            this.$store.dispatch('setIsLoading', false)
          })
    },
    addInputRow() {
      this.$store.dispatch('setMenuAddLink')
    },
    removeLink(id) {
      this.$store.dispatch('setMenuRemoveLinkById', id)
    }
  },
  computed: {
    menuItems() {
      return this.$store.getters['getMenuModalItem']
    }
  }
}
</script>

<style scoped>

</style>