<template>
    <div>
        <v-container>
            <v-row align="center" justify="center" class="mt-md-4 mt-sm-4 mt-lg-8">
                <v-col cols="12" md="3" sm="6" align-self="center">
                    <slot name='header'>
                    </slot>
                </v-col>
            </v-row>
            <v-row align="center" justify="center">
                <v-col cols="12" md="6" align-self="center">
                    <slot name='card'/>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
    export default {
        name: "FormLayout"
    }
</script>

<style scoped>

</style>
