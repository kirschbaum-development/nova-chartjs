export default{
    methods:{
        getAllowedParametersFromDataset: function(parameters, dataset = []) {
            return parameters.map(key => dataset[key] || 0);
        }
    }
}
