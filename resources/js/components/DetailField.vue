<template>
    <div>
        <template v-if="field.value !== null">
            <div class="card mb-6 py-3 px-6">
                <template v-for="(x, property) in field.jsonSchema.properties">
                    <template v-if="field.value[property] != '_' && field.value[property] != ''">
                        <div class="flex border-b border-40 -mx-6 px-6" :key="field.value[property]">
                            <div class="w-1/4 py-4">
                                <h4 class="font-normal text-80 capitalize">{{property | stripPunctuation }}</h4>
                            </div>
                            <div class="w-3/4 py-4 break-words">
                                <p class="text-90">
                                {{field.value[property]}}
                                </p>
                            </div>
                        </div>
                    </template>
                </template>
                <div class="flex border-b border-40 -mx-6 px-6 remove-bottom-border">
                    <div class="w-1/4 py-4">
                        <h4 class="font-normal text-80"></h4>
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <h4 class="font-normal text-80">There is no data to show!</h4>
        </template>
    </div>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    filters: {
        stripPunctuation: function(value) {
            if (!value) return ''
            value = value.toString()
            return value.replace(/[^a-z0-9]/gi, ' ')
        }
    }
}
</script>
