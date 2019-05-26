<template>
    <div class="flex border-b border-40">
        <div class="w-full py-4">
            <chartjs-range-chart
                :dataset="dataset"
                :settings="field.settings"
            />
        </div>
    </div>
</template>

<script>
import ChartjsRangeChart from "./ChartjsRangeChart";

export default {
    components: {
        ChartjsRangeChart
    },

    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data() {
        return {
            dataset: [
                {
                    label: `${this.field.label} ${this.resourceId}`,
                    borderColor: this.field.settings.color||this.getRandomColor(),
                    data: this.getAllowedParametersFromDataset(this.field.settings.parameters, this.field.value)
                }
            ]
        }
    },

    methods: {
        getRandomColor: function() {
            return "#"+((1<<24)*Math.random()|0).toString(16);
        },

        getAllowedParametersFromDataset: function(parameters, dataset = []) {
            return parameters.map(key => dataset[key] || 0);
        }
    }
}
</script>
