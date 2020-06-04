<template>
  <button v-bind:class="['btn', is_display ? 'btn-primary' : 'btn-danger']" v-on:click="handleClick">{{ status[is_display] }}</button>
</template>
<script>
  const axios = require('axios');
  export default {
    props: {
      updateId: Number,
      isDisplay: Number,
      target: String,
    },

    data() {
      return {
        update_id: Number(this.updateId),
        is_display: Number(this.isDisplay),
        status: {
          0: '非表示',
          1: '表示中'
        },
      }
    },

    methods: {
      handleClick:function () {

        this.is_display ^= 1;

        // DBの型に合わせる
        let display = (this.is_display);

        let data = {
          'is_display': display,
        };

        axios.put('/ajax/' + this.target + '/' + this.update_id + '/is_display', {data} )
      }
    }
  }
</script>