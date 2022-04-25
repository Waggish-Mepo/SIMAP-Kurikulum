<template>
  <div>
    <div class="loader" v-if="isLoading"></div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item" v-if="this.user.role === 'ADMIN'">
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
              mode="remote"
              :columns="columns"
              :rows="rows"
              :pagination-options="{enabled: false}"
              @on-column-filter="onColumnFilter"
              @on-sort-change="onSortChange"
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
            <pagination v-if="!withoutPagination" class="mt-3" :pagination="pages" @paginate="getStudents" :offset="2" :data="payloadGet"></pagination>
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
// pagination
import paginateComponent from '../../../components/Pagination.vue';
export default {
  name: "addStudentRegion",
  components: {
    "modal": modalComponent,
    "pagination": paginateComponent
  },
  data() {
    return {
      modalRedirect: false,
      sortOpts: { enabled: true },
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
      addSuccessed: false,
      user: {},
      pages: {
        total: 0,
        per_page: 20,
        from: 1,
        to: 0,
        current_page: 1,
        last_page: 1,
      },
      payloadGet: {
        search: '',
        searchVal: '',
        page: 1,
        per_page: 20,
        field:"student_group_id",
        sort:"ASC"
      },
      withoutPagination: false
    };
  },
  created() {
    this.getRegion(this.$route.params.region);
    this.getStudents(this.payloadGet);
    this.getUser();
  },
  computed: {
    ...mapState(['errorMessage', 'errors', 'isLoading']),
  },
  methods: {
    ...mapActions('students', ['notSignedStudent', 'studentDetail', 'update']),
    ...mapActions('regions', ['show']),

    getUser() {
      let user = JSON.parse(localStorage.getItem('user_data'));
      this.user = user;
    },
    getRegion(id) {
      this.show(id).then((result) => {
        this.region = result;
      })
    },
    getStudents(payload) {
      this.notSignedStudent(payload).then((result) => {
        this.rows = [];
        if (result.per_page) {
          this.rows = result.data;
          this.pages.total = result.total;
          this.pages.per_page = result.per_page;
          this.pages.from = result.from;
          this.pages.to = result.to;
          this.pages.current_page = result.current_page;
          this.pages.last_page = result.last_page;
        } else {
          if (result.data.length > 1) {
            for (let i = 0; i < result.data.length; i++) {
              if (i == result.data.length-1) {
                this.rows.push(result.data[i][0]);
              } else {
                this.rows.push(result.data[i]);
              }
            }   
          } else {
            this.rows = result.data;
          }
        }
      })
    },
    updateParams(newProps) {
      this.pages = Object.assign({}, this.pages, newProps);
    },
    onColumnFilter(params) {
      this.updateParams(params);
      if(!this.pages.columnFilters["name"] && !this.pages.columnFilters["nis"] && !this.pages.columnFilters["student_group.name"]){
        this.payloadGet.search = "";
        this.payloadGet.searchVal = "";
        this.withoutPagination = false;
        this.getStudents(this.payloadGet);
      }
      else if(this.pages.columnFilters["name"]){
        this.payloadGet.search = "name";
        this.payloadGet.searchVal = this.pages.columnFilters["name"];
        this.withoutPagination = false;
        this.getStudents(this.payloadGet);
      }
      else if(this.pages.columnFilters["nis"]){
        this.payloadGet.search = "nis";
        this.payloadGet.searchVal = this.pages.columnFilters["nis"];
        this.withoutPagination = false;
        this.getStudents(this.payloadGet);
      }
      else if(this.pages.columnFilters["student_group.name"]){
        this.payloadGet.search = "student_group";
        this.payloadGet.searchVal = this.pages.columnFilters["student_group.name"];
        this.withoutPagination = true;
        this.getStudents(this.payloadGet);
      }
    },
    onSortChange(params) {
      if(params[0].type == "none"){
        this.payloadGet.field = "student_group_id";
        this.payloadGet.sort = "ASC";
      }else if(params[0].field == "student_group.name") {
        this.payloadGet.field = "student_group_id";
        this.payloadGet.sort = params[0].type;
      }else{
        this.payloadGet.field = params[0].field;
        this.payloadGet.sort = params[0].type;
      }
      this.getStudents(this.payloadGet);
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
              this.getStudents(this.payloadGet);
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