export default{
    methods:{
        getAllowedParametersFromDataset: function(parameters, dataset = []) {
            return parameters.map(key => _.has(dataset, key) ? dataset[key] : 0);
        }
    }
}
