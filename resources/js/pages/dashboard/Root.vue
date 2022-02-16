<template>
  <div class="body" id="body-pd">
    <app-header />
    <app-sidebar />
    <div class="mh-100">
      <router-view />
    </div>
  </div>
</template>

<script>
import Header from '../../components/Header.vue';
import Sidebar from '../../components/Sidebar.vue';
export default {
  components: {
    'app-header': Header,
    'app-sidebar': Sidebar
  }
}
</script>

<style>
.mh-100 {
  min-height: 90vh;
  padding-right: 1rem;
  padding-top: 1rem;
}

@media screen and (max-width: 768px) { 
  .mh-100 {
    padding: 3.3rem 1rem 0 1rem;
  }
}
</style>