<script>
    import { Line, mixins } from 'vue-chartjs'
    const {reactiveProp} = mixins

    export default {
        extends: Line,

        mixins: [reactiveProp],

        props: {
            'dataset': Array,
            'settings': Object,
        },

        mounted () {
            this.chartData = this.createChartDataset();
            this.renderChart(this.chartData, this.settings.options);
        },

        methods: {
            createChartDataset: function(){
                let datasets = [
                    {
                        label: 'Low',
                        borderColor: this.settings.lowColor || '#f87900',
                        fill: '+1',
                        backgroundColor: this.settings.fillColor || 'rgba(20,20,20,0.2)',
                        data: this.settings.low
                    },
                    {
                        label: 'High',
                        borderColor: this.settings.highColor || '#007979',
                        fill: false,
                        data: this.settings.high
                    }
                ];

                for (let data in this.dataset) {
                    datasets.unshift({...this.dataset[data],...{fill:false}});
                }

                return {
                    labels: this.settings.parameters,
                    datasets: datasets
                }
            }
        },

        watch: {
            dataset: function() {
                this.chartData = this.createChartDataset();
            }
        }
    }
</script>
