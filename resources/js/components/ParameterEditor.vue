<template>
    <div>
        <div class="nova-items-field-input-wrapper flex p-2" v-for="(parameter, index) in this.parameters" :key="index">
            <div class="w-1/4"><h4 class="font-normal text-80">{{parameter}}</h4></div>
            <div class="w-3/4">
                <input
                    v-model="value[parameter]"
                    type="number"
                    :placeholder="parameter"
                    class="flex-1 form-control form-input form-input-bordered"
                />
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            'value': Object,
            'parameters': Array
        },
    }
</script>
