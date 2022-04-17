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

    <div class="alert alert-success my-3" v-if="addSuccessed">
      <span>Siswa berhasil ditambahkan ke data rayon <b>{{region.name}}</b>. <router-link v-bind:to="{ name: 'regions.students', params: { page: 8, region: $route.params.region } }"><a href="#">Lihat data siswa rayon</a></router-link></span>
    </div>

    <h4 class="text-capitalize mt-3 mb-4 font-weight-bold">data siswa belum terdaftar rayon apapun</h4>
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
            <div slot="selected-row-actions">
              <a href="#" class="btn btn-sm btn-success" @click="getSelected">Tambahkan</a>
            </div>
            </vue-good-table>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- modal -->
    <modal v-if="modalRedirect" @close="modalRedirect = false" :redirectOpt="redirect">
      <h5 slot="header">Berhasil Menmabahkan Siswa</h5>
      <div slot="body">
        <span>Siswa berhasil ditambahkan ke data rayon <b>{{region.name}}</b>. Lihat data siswa rayon?</span>
      </div>
    </modal>
  </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
// modal
import modalComponent from '../../../components/Modal.vue';
export default {
  name: "addStudentRegion",
  components: {
    "modal": modalComponent
  },
  data() {
    return {
      modalRedirect: false,
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
      region: {},
      addPayload: [],
      finalPayload: [],
      addSuccessed: false
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
    ...mapActions('students', ['notSignedStudent', 'studentDetail', 'update']),
    ...mapActions('regions', ['show']),

    getRegion(id) {
      this.show(id).then((result) => {
        this.region = result;
      })
    },
    getStudents() {
      this.notSignedStudent().then((result) => {
        this.rows = result;
      })
    },
    selectionChanged(e) {
      this.addPayload = e.selectedRows;
    },
    getSelected() {
      for (let i = 0; i < this.addPayload.length; i++) {
        if (!this.finalPayload.includes(this.addPayload[i].id)) {
          this.finalPayload.push(this.addPayload[i].id);
        }
      };
      for (let x = 0; x < this.finalPayload.length; x++) {
        this.studentDetail(this.finalPayload[x]).then((result) => {
          result.region_id = this.$route.params.region;
          let payload = {id: this.finalPayload[x], data: result};
          this.update(payload).then((result) => {
            if(x == this.finalPayload.length-1) {
              this.getStudents();
              this.modalRedirect = true;
            }
          });
        });
      };
    },
    redirect() {
      this.$router.push({ name: 'regions.students', params: { page: 8, region: this.$route.params.region } });
    }
  },
};
</script>

<style scoped>
h4 {
  font-weight: 600;
}
</style>