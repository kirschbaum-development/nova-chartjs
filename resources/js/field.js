Nova.booting((Vue, router, store) => {
    Vue.component('index-nova-chartjs', require('./components/IndexField'))
    Vue.component('detail-nova-chartjs', require('./components/DetailField'))
    Vue.component('form-nova-chartjs', require('./components/FormField'))
})
