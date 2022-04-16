<template>
  <div>
    <div class="loader" v-if="isLoading"></div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link v-bind:to="{ name: 'regions', params: { page: 8 } }">
            <a href="#">Rayon</a>
          </router-link>
        </li>
        <li class="breadcrumb-item">
          <router-link v-bind:to="{ name: 'regions.students', params: { page: 8, region: $route.params.region } }">
            <a href="#">{{region.name}}</a>
          </router-link>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
          Tambah Siswa Rayon
        </li>
      </ol>
    </nav>

    <div class="alert alert-danger my-3" v-if="errorMessage">
      {{ errorMessage }}
    </div>

    <h4 class="text-capitalize mt-3 mb-4 font-weight-bold">data seluruh siswa</h4>
    <div class="table-responsive mt-3">
      <table class="table table-borderless">
        <tbody>
          <tr>
            <vue-good-table
              :columns="columns"
              :rows="rows"
              :pagination-options="paginationOpts"
              :sort-options="sortOpts"
              :fixed-header="true"
              @on-selected-rows-change="selectionChanged"
              :select-options="{ enabled: true }"
              max-height="800px"
              styleClass="vgt-table condensed striped"
            >
            </vue-good-table>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
export default {
  name: "my-component",
  data() {
    return {
      // modalUpdate: false,
      sortOpts: { enabled: true },
      paginationOpts: {
        enabled: true,
        mode: "records",
        perPage: 40,
        position: "bottom",
        perPageDropdown: [10, 50, 100],
        dropdownAllowAll: true,
        setCurrentPage: 1,
        nextLabel: "Next",
        prevLabel: "Prev",
        rowsPerPageLabel: "Rows per page",
        ofLabel: "of",
        pageLabel: "Page", // for 'pages' mode
        allLabel: "All",
      },
      columns: [
        {
          label: "NIS",
          field: "nis",
          filterOptions: { enabled: true },
        },
        {
          label: "Nama",
          field: "name",
          filterOptions: { enabled: true },
        },
        {
          label: "Rombel",
          field: "student_group.name",
          filterOptions: { enabled: true },
        },
      ],
      rows: [],
      region: {}
    };
  },
  created() {
    this.getRegion(this.$route.params.region);
    this.getStudents();
  },
  computed: {
    ...mapState(['errorMessage', 'errors', 'isLoading']),
  },
  methods: {
    ...mapActions('students', ['indexWithSG']),
    ...mapActions('regions', ['show']),

    getRegion(id) {
      this.show(id).then((result) => {
        this.region = result;
      })
    },
    getStudents() {
      this.indexWithSG().then((result) => {
        this.rows = result;
      })
    },
    selectionChanged(e) {
      console.log(e.selectRows);
    },
  },
};
</script>

<style scoped>
h4 {
  font-weight: 600;
}
</style>