<template>
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
          <vue-editor v-model="htmlForEditor" :editor-toolbar="customToolbar"></vue-editor>
          <b-form-invalid-feedback :force-show="textareaError.show">
            {{ textareaError.text }}
          </b-form-invalid-feedback>
        </b-form-group>
      </b-col>
    </b-form-row>
    <b-form-row v-for="(link, idxLink) in links" :key="idxLink">
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
              v-model="menuItems.link"
              type="url"
              placeholder="https://example.com"
              pattern="(https|http)://.*" size="30"
              :required="true"
          ></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols="12" md="1">
        <div class="mb-2">Удалить</div>
        <b-button @click.prevent="removeLink" size="md" variant="danger" class="mb-2">
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
</template>

<script>
import {VueEditor} from "vue2-editor";

export default {
  components: {VueEditor},
  data() {
    return {
      passwordError: [],
      textareaError: {
        show: false,
        text: ''
      },
      links: 0,
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
      await this.$store.dispatch('setIsLoading', true)
      const formData = new FormData(e.target)

      formData.append('text', this.htmlForEditor);

      await this.$store.dispatch('setMenuAddItem', formData)
          .then(res => {
            if (res.status === 201) {
              this.$root.$emit('bv::hide::modal', 'AddMenuTableItem', '#btnShow')
              this.getMenuTableData()

              this.$notify({
                group: 'server-alerts',
                title: 'Успешный запрос!',
                type: 'success',
                text: 'Пункт меню успешно создан'
              });
            }
          })
          .catch(err => {
            if (typeof err.response.data.message === 'string') {
              this.passwordError.length = 0
              this.passwordError.push(err.response.data.message)
            } else if (typeof err.response.data.message === 'object') {
              this.passwordError = err.response.data.message.password ? err.response.data.message.password : []
              this.textareaError.show = !!err.response.data.message.text
              this.textareaError.text = err.response.data.message.text[0]
            }

            this.$store.dispatch('setIsLoading', false)
          })
    },
    addInputRow() {
      this.links++
    },
    removeLink() {
      this.links--
    }
  },
  computed: {
    menuItems() {
      return this.$store.getters['getMenuAddModalUser']
    }
  }
}
</script>

<style scoped>
@import "~vue2-editor/dist/vue2-editor.css";
</style>