<template> 
  <ul class="tree-view"> 
    <tree-view-item 
      v-for="item in sortedItems" 
      :key="item.id"
      :item="item"
      :disabled="disabled"
      :ranges="ranges"
      @change="handleChange"
      @checked="checked"
      @unchecked="unchecked" 
    />
  </ul>
</template>

<script> 
export default { 
  props: {
    items: {
      type: Array,
      default: [],
    }, 
    disabled: {
      type: Boolean,
      default: false,
    }, 
    ranges: {
      Type: Number,
      default: 1,
    }, 
  },   

  methods: { 
    checked(item) {
      this.$emit('checked', item)  
      this.$emit('clean', item)  
    },

    unchecked(item) {
      this.$emit('unchecked', item)
    },

    handleChange() {
      this.$emit('change', ...arguments)
    }, 
  },

  computed: {
    sortedItems() { 
      return this.items.sort((item, nextItem) => { 
        return item.name > nextItem.name ? 1 : -1
      })
    }
  },
};
</script>
<style scoped>
ul {
  list-style: none; 
  padding: 0 2px;
  margin: 0;
}
ul ul {
  padding: .5rem 0;
  padding-right: 1.5rem;
}
</style>
