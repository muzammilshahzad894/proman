<template>
    <Navbar />
    
    <div class="main-section mb-5">
        <router-view />
    </div>
    
    <Footer />
</template>

<script>
import Navbar from '@/components/layout/Navbar.vue';
import Footer from '@/components/layout/Footer.vue';

export default {
    name: "Layout",
    components: {Navbar, Footer},
}
</script>

<style scoped>
.main-section {
    min-height: 50;
}
</style>
