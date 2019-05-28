<script>
    import { Line, mixins } from 'vue-chartjs'
    const {reactiveData} = mixins;

    export default {
        extends: Line,

        mixins: [reactiveData],

        props: {
            'dataset': Array,
            'settings': Object,
        },

        mounted () {
            this.chartData = this.createChartDataset();
            //we need to set options manually as options are used to re-render chart when data changes in reactiveData/reactiveProp mixin
            this.options = this.settings.options;
            this.renderChart(this.chartData, this.options);
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
                    datasets.unshift(
                        {
                            ...this.dataset[data],
                            ...{fill:false}
                        }
                    );
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
