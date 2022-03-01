<template>
    <div class="wrapper">
        <grade-books-sidebar />
        <div class="content">
            <grade-books-header />
            <div class="content-wrapper">
                <router-view />
            </div>
        </div>
    </div>
</template>

<script>
import Header from '../../components/gradebooks/Header.vue';
import Sidebar from '../../components/gradebooks/Sidebar.vue';
export default {
  components: {
    'grade-books-header': Header,
    'grade-books-sidebar': Sidebar
  }
}
</script>

<style scoped>
.wrapper {
    display: flex;
    width: 100%;
    align-items: stretch;
}

.content {
    width: 100%;
    min-height: 100vh;
    transition: all 0.3s;
}

.content-wrapper {
    padding: 15px;
}
</style>