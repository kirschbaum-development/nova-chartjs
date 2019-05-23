<script>
    import { Line } from 'vue-chartjs'

    export default {
        extends: Line,

        props: {
            'dataset': Array,
            'settings': Object,
        },

        mounted () {
            this.renderChart(this.createDataSet(), this.settings.options);
        },

        methods: {
            createDataSet: function() {
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
        }
    }
</script>
