export default{
	props: {
		'dataset': Array,
		'additionalDatasets': Array,
		'settings': Object,
	},

	mounted (){
		this.chartData = this.createChartDataset();
		//we need to set options manually as options are used to re-render chart when data changes in reactiveData/reactiveProp mixin
		this.options = this.settings.options;
		this.renderChart(this.chartData, this.options);
	},

	watch: {
		dataset (){
			this.chartData = this.createChartDataset();
		}
	},

    methods: {
        createChartDataset (){
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
