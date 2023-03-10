import IndexField from './components/IndexField';
import DetailField from './components/DetailField';
import FormField from './components/FormField';

Nova.booting(Vue => {
    Vue.component('index-nova-chartjs', IndexField);
    Vue.component('detail-nova-chartjs', DetailField);
    Vue.component('form-nova-chartjs', FormField);
});
