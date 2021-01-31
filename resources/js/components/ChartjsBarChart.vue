<script>
    import { Bar, mixins } from 'vue-chartjs'
    import charts from "../mixins/charts";

    const {reactiveData} = mixins;

    export default {
        extends: Bar,

        mixins: [reactiveData, charts],

        props: {
            'dataset': Array,
            'additionalDatasets': Array,
            'settings': Object,
        },

        mounted () {
            this.chartData = this.createChartDataset();
            //we need to set options manually as options are used to re-render chart when data changes in reactiveData/reactiveProp mixin
            this.options = this.settings.options;
            this.replaceToolTipTemplate();
            this.renderChart(this.chartData, this.options);
        },

        watch: {
            dataset: function() {
                this.chartData = this.createChartDataset();
            }
        }
    }
</script>
