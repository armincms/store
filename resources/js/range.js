Nova.booting((Vue, router, store) => {  
	Vue.component('form-armincms-store-range', require('./components/RangeField')) 
	Vue.component('tree-view', require('./components/TreeView')) 
	Vue.component('tree-view-item', require('./components/TreeItem')) 
})
