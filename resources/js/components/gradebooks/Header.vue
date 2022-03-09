<template>
    <nav class="navbar navbar-expand-md shadow"> 
        <button type="button" id="sidebarCollapse" class="btn btn-white" @click="showSidebar"> <i class="fas fa-bars"></i> Buku Nilai</button>
    </nav>
</template>

<script>
export default {
    name: "headerGradebook",
    methods: {
        showSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    }
}
</script>

<style scoped>
nav.navbar {
    background: #fff !important;
}

.navbar {
    padding: 10px;
    border: none;
    border-radius: 0;
    margin-bottom: 30px;
    position: fixed;
    width: 100%;
    z-index: 1000;
}

.fas.fa-bars {
    margin-right: 10px;
}
</style>