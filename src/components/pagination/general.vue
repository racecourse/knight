<template>
  <div>
    <mu-pagination 
      :total="total" 
      :current.sync="current" 
      :page-size="pageSize"
      :pageCount="pageCount"
      @pageChange="next">
    </mu-pagination>
  </div>
</template>


<script>
export default {
  props: {
    total: {
      type: Number,
      required: true,
    },
    current: {
      type: Number,
      required: true,
      default: 1,
    },
    pageSize: {
      type: Number,
      required: false,
    },
    pageCount: {
      type: Number,
      required: false,
      default: 5,
    },
  },
  methods: {
    next(page) {
      const path = this.$router.history.current.path;
      this.$router.push({ path, query: { page } });
      this.$emit('query');
    }
  }
}
</script>
