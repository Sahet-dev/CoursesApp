<script setup>
import { ref } from 'vue'
import {useRouter} from 'vue-router';

const router = useRouter();




</script>

<template>
 <div>
     <div class="container">
         <div class="bg-blue-900 text-gray-200">
             Hello world
         </div>


         <nav>
             <router-link to="/login">Login </router-link>
             <router-link to="/register">  Register </router-link>
         </nav>
     </div>
 </div>
</template>

<style scoped>

</style>
