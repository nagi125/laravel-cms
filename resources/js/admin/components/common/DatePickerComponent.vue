<template>
  <datepicker :language="ja" :format="DatePickerFormat" :name="target" :value="value" :required=true :input-class="'form-control datepicker bg-white'"></datepicker>
</template>

<script>
  import Datepicker from 'vuejs-datepicker';
  import {ja} from 'vuejs-datepicker/dist/locale'
  export default {
    components: {
      Datepicker
    },

    props: {
      target: String,
      value: String,
    },

    data() {
      return {
        DatePickerFormat: 'yyyy-MM-dd',
        ja: ja
      }
    },

    mounted: function() {
    },

  }
</script>

<style lang="scss">
  .vdp-datepicker {
    .vdp-datepicker__calendar {
      span.sat {
        color: #3490dc;
      }

      span.sun {
        color: #e3342f;
      }
    }
  }
</style>