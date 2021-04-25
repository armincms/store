<template></template>
<script>
import { FormField, HandlesValidationErrors, Errors } from 'laravel-nova'

export default { 
  mixins: [FormField, HandlesValidationErrors],
  
  created() {
    this.$nextTick(() => {
      [
        'price', 
        'config->shipping->width', 
        'config->shipping->height', 
        'config->shipping->weight'
      ].forEach(target => {
        document.getElementById(target).onchange = (e) => {
          this.setTotal(e.target.getAttribute('id').split('->').pop() , e.target.value)
        };
      }) 

      // Triggeresists inputs
      document.querySelectorAll('input[type=number]').forEach(field => this.triggerEvent(field, 'change'))


      window.document.addEventListener('change', (e) => {
        var id = e.target.getAttribute('id'),
            targetId = id.split('_').pop();

        if (id === targetId) return 

        if (id.endsWith('_default')) {
          document.querySelectorAll('[id$=_default]:checked').forEach(field => { 
            if (field.checked && field.getAttribute('id') !== id) { 
              this.triggerEvent(field, 'change')
            }
          })
        } else {
          if (targetId !== 'price') targetId = 'config->shipping->' + targetId

          this.triggerEvent(document.getElementById(targetId), 'change')
        }
      })   
    })
  },

  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() { 
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {     
    },

    holder() {
      return _.tap(document.createElement('span'), (holder) => {  
        holder.classList.add('holder', 'text-info', 'py-8', 'flex')   

        holder.append(_.tap(document.createElement('span'), (label) => {  
          label.innerText = Nova.bus.__('Total') + ': '
        }))

        holder.append(_.tap(document.createElement('span'), (amount) => {
          amount.classList.add('amount', 'text-danger')
        }))
      }) 
    }, 

    ensureNumber(value) {
      return parseFloat(value) ? parseFloat(value) : 0;
    },

    setTotal(target, value) {
      document.querySelectorAll('[id$=_'+target+']').forEach((field) => {
        var amount = field.closest('.flex.border-b').querySelector('[class*=amount]')

        if (amount == null) { 
          field.closest('.flex.border-b').append(this.holder()) 
          amount = field.closest('.flex.border-b').querySelector('[class*=amount]') 
        }   

        amount.innerText = this.ensureNumber(field.value) + this.ensureNumber(value)  
      }) 
    },

    triggerEvent(el, type) {
      if (el == null) return;

      if ('createEvent' in document) {
        // IE9+ and other modern browsers
        var e = document.createEvent('HTMLEvents');
        e.initEvent(type, false, true);
        el.dispatchEvent(e);
      } else {
        // IE8
        var e = document.createEventObject();
        e.eventType = type;
        el.fireEvent('on' + e.eventType, e);
      }
    }
  }
};
</script>