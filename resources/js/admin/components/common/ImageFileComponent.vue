<template>
  <div>
    <div class="row">
      <div class="col-11">
        <input type="file" :id=id :name=name accept="image/*" class="form-control bg-white" lang="ja" @change="handleChange" />
      </div>
      <div class="col-1 text-start">
        <button class="btn btn-outline-danger" @click.prevent="remove">削除</button>
      </div>
    </div>
    <div v-if="preview">
      <img id=img-upload class="img-thumbnail mt-1 img-upload" :src="preview" :alt=name />
    </div>
  </div>
</template>
<script>
  export default {
    props: {
      name: String,
      id: String,
      imagePath: String,
    },

    data() {
      return {
        preview: '',
      }
    },

    created() {
      this.preview = this.imagePath;
    },

    methods: {
      handleChange(e) {
        let files = e.target.files || e.dataTransfer.files;

        // 名前の取得
        this.label = files[0].name;

        // Preview
        let reader = new FileReader();
        reader.onload = (e) => {
          this.preview = e.target.result;
        };
        reader.readAsDataURL(files[0]);
      },

      remove() {
        const file = document.getElementById(this.id);

        file.value = '';
        this.label = 'ファイル選択...';
        this.preview = '';
      },
    },
  }
</script>