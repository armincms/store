<template>  
  <li 
    class="my-1 w-full" 
    :class="{'text-70': disabled}"
  >
    <span class="flex items-center mb-1">
      <input type="checkbox" class="checkbox" v-model="checked" name="item.id" :disabled="disabled">
      <span class="mx-1">
        {{ item.name }}
        <h4 class="inline cursor-pointer" @click="toggle" v-if="hasChildren">
          <span class="text-danger" v-if="shouldCollapsed">-</span>
          <span class="text-success" v-else>+</span>
        </h4> 
      </span>
      <span class="flex justify-center flex-row w-4/5 mr-auto"> 
        <input 
          v-for="(range, index) in ranges"
          :key="index + item.name"
          v-model="rangeValues[index]"
          :min="calculateRangeMin(index)"
          :max="calculateRangeMax(index)"
          step="0.001"
          type="number" 
          :name="item.name"
          :disabled="! checked || disabled" 
          class="form-control form-input form-input-bordered w-1/5 ml-1" 
        > 
      </span>
    </span> 

    <tree-view 
      :items="item.childrens" 
      v-if="hasChildren"
      v-show="shouldCollapsed"  
      :ranges="ranges" 
      :disabled="shouldDisableTree"
      @change="emitChanges"
      @checked="reset($event)"
    />
  </li>
</template>

<script> 
export default { 
  props: {
    item: {
      type: Object,
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

  data: () => ({
    rangeValues: ["0"],
    collapsed: false,
    checked: false, 
  }), 

  created() {  
    this.checked = this.item.active ? true : false 

    if (this.item.values) {
      this.rangeValues = this.item.values 
      this.checked = true
    }
  }, 

  methods: {
    toggle() {
      this.collapsed = ! this.collapsed;
    }, 

    reset(item) { 
      this.$emit('checked', this.item) 
    },

    emitChanges() {
      this.$emit('change', ...arguments)
    },

    calculateRangeMin(range) {
      let min = this.rangeValues[range - 1]; 

      return min && range ? parseFloat(min) + .001 : 0;
    },

    calculateRangeMax(range) { 
      let max = this.rangeValues[range + 1];

      return max ? parseFloat(max) - .001 : null;
    },
  },

  computed: { 
    hasChildren() {
      return this.item.childrens.length > 0
    }, 

    shouldCollapsed() {
      return this.collapsed;
    },

    shouldDisableTree() {
      return this.disabled || this.checked;
    },
  },

  watch: {
    checked(checked) { 
      this.$emit(checked ? 'checked' : 'unchecked', this.item) 
      this.emitChanges(this.item, checked && ! this.disabled ? this.rangeValues : []) 
    },

    rangeValues(value) { 
      this.emitChanges(this.item, this.disabled ? [] : value)
    },

    disabled(disabled) { 
      this.emitChanges(this.item, disabled ? [] : this.rangeValues) 
    },
  }
};
</script>  