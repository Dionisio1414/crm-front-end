<template>
    <p>
        {{form.desc}}
    </p>
</template>

<script>
    export default {
        name: "Description",
        props: {
            form: Object
        }
    }
</script>

<style scoped lang="scss">
    p {
        width: 100%;
        max-width: 874px;
        margin: 0 auto;
        font-size: 14px;
        line-height: 130%;
        color: #7E7E7E;
    }

</style>