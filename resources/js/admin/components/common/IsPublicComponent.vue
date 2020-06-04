<template>
  <button v-bind:class="['btn', is_public ? 'btn-primary' : 'btn-danger']" v-on:click="handleClick">{{ status[is_public] }}</button>
</template>
<script>
  const axios = require('axios');
  export default{
    props: {
      updateId: Number,
      isPublic: Number,
      target: String,
    },

    data(){
      return{
        update_id: Number(this.updateId),
        is_public: Number(this.isPublic),
        status:{
          0: '非公開',
          1: '公開中'
        },
      }
    },

    methods:{
      handleClick:function () {

        this.is_public ^= 1;

        // DBの型に合わせる
        let publicStatus = (this.is_public);

        let data = {
          'is_public': publicStatus,
        };

        axios.put('/ajax/' + this.target + '/' + this.update_id + '/is_public', {data} );
      }
    }
  }
</script>
