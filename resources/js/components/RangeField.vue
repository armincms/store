<template>
  <default-field :field="field" :errors="errors" :fullWidthContent=true>
    <template slot="field"> 
      <div class="flex w-full">
        <tree-view 
          @change="handleChange" 
          :items="countries"  
          :ranges="range"  
          :class="{
            'w-4/5': range < field.maxRange, 
            'w-full': range >= field.maxRange
          }" 
        />
        <button 
          v-if="range < field.maxRange" 
          type="button" 
          @click="addNewRange" 
          class="btn btn-default btn-primary inline-flex items-center relative"
        >
          <span>{{ __('Add New Range') }}</span>
        </button>
      </div>
    </template>
  </default-field>
</template>

<script> 
import { 
  FormField, 
  HandlesValidationErrors, 
  Errors,
  Minimum, 
} from 'laravel-nova' 

export default {
  mixins: [FormField, HandlesValidationErrors],  

  data: () => ({
    range:1,   
    resources: {
      country: [],
      state: [],
      city: [],
      zone: [],
    }
  }), 

  async created() { 
    Object.keys(this.resources).forEach(range => this.getResources(range));  
    this.range = this.field.range
  },

  methods: { 
    /*
     * Set the initial value for the field
     */
    setInitialValue() {
      this.value = {}
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(this.field.attribute, JSON.stringify(_.values(this.value)))  
    }, 

    /**
     * Get the resources based on the current page, search, filters, etc.
     */
    async getResources(range) { 
      await this.$nextTick(() => {
        return Minimum(
          Nova.request().get('/nova-api/' + this.field[range], { 
            params: this.resourceRequestQueryString,
          }),
          300
        ).then(({ data }) => {
          this.resources[range] = []
          this.resources[range] = data.resources  
        })
      })
    },

    getCountryStates(country) {
      let states = this.resources.state.filter(state => {
        return ! country || this.checkParent(state, country)
      });   

      return states.map(state => {
        return _.tap(this.mapResource(state, []), resource => {
          resource.childrens = this.getStateCities(resource.id)
          resource.resourceName = this.field.state
          resource.values = this.field.value.states[resource.id] 
        })
      }) 
    }, 

    getStateCities(state) {
      let cities = this.resources.city.filter(city => {
        return ! state || this.checkParent(city, state) 
      });

      return cities.map(city => {
        return _.tap(this.mapResource(city, []), resource => {
          resource.childrens = this.getCityZones(resource.id)
          resource.resourceName = this.field.city
          resource.values = this.field.value.cities[resource.id] 
        })
      }) 
    }, 

    getCityZones(city) {
      let zones = this.resources.zone.filter(zone => {
        return ! city || this.checkParent(zone, city)  
      });

      return zones.map(zone => {
        return _.tap(this.mapResource(zone), resource => {
          resource.resourceName = this.field.zone
          resource.values = this.field.value.zones[resource.id] 
        })
      }) 
    },

    mapResource(resource, childrens = []) { 
      return {
        name: resource.fields.find(field => field.attribute == 'name').value,
        id: resource.fields.find(field => field.attribute == 'id').value, 
        childrens: childrens
      } 
    },

    checkParent(resource, parentId) {
      return resource.fields.find(field => field.belongsToId == parentId) ? true : false
    },

    addNewRange() {
      this.range++
    }, 

    handleChange(item, values) {
      let key = item.id +'-'+ item.resourceName;

      if (values.length) {
        this.$set(this.value, key, {
          id: item.id,
          resource: item.resourceName,
          values: values,
        }) 
      } else if (this.value.hasOwnProperty(key)) {
        this.$delete(this.value, key)
      } 
    },
  },

  computed: { 
    countries() {   
      return this.resources.country.map(country => {
        return _.tap(this.mapResource(country), resource => {
          resource.childrens = this.getCountryStates(resource.id) 
          resource.resourceName = this.field.country
          resource.values = this.field.value.countries[resource.id] 
        }); 
      });
    },  

    ranges() {
      return ['country', 'state', 'city', 'zone']
    },

    /**
     * Build the resource request query string.
     */
    resourceRequestQueryString() {
      return {
        search: null, 
        perPage: 99999999999,
      }
    },
  }
};
</script>
<style>
.tree ul {
  list-style-type: none;
  overflow: hidden;
  padding: 1rem 0;
  margin: 0;
}
.tree ul ul {
  padding: .5rem 1.5rem;
}
</style>
