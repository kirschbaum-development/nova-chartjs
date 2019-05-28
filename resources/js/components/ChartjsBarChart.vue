<script>
    import { Bar } from 'vue-chartjs'

    export default {
        extends: Bar,

        props: {
            'data': Object,
        },

        data: function(){
            return {
                'options' : {
                    'responsive':true,
                    'maintainAspectRatio':false,
                    'layout':{
                        'padding':0
                    },
                    'legend':{
                        'display':false
                    },
                    'title':{
                        'display':false
                    },
                    'scales': {
                        'yAxes': [{
                            'display': false,
                        }],
                        'xAxes': [{
                            'display': false,
                        }],
                    },
                }
            }
        },

        mounted () {
            this.renderChart(this.data, this.options);
        },
    }
</script>
