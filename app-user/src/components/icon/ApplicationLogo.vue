<template>
    <div class="svg items-center text-center align-middle">
        <img :src="image" alt="Application Logo">
    </div>
</template>

<script setup>
import image from './../../assets/IconTm.png'
</script>


<style scoped>
.svg {
    display: inline-block;
    width: 142px;
    height: 82px;
}
</style>
