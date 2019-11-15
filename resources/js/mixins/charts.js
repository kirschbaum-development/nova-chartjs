export default{
    methods:{
        createChartDataset: function(){
            let datasets = [...this.additionalDatasets];

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
    }
}
