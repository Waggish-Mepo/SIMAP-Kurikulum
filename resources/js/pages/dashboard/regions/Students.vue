<template>
    <div>
        <div class="loader" v-if="isLoading"></div>
        <div v-if="isLoading" class="w-100 card-loading">
            <img src="/assets/img/loading.png" alt="loading" class="d-block m-auto">
        </div>
        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>

        <div v-if="!isLoading">
        <nav aria-label="breadcrumb" v-if="this.user.role === 'ADMIN'">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'regions', params: {page: 8} }"><a href="#">Rayon</a></router-link></li>
                <li class="breadcrumb-item active" aria-current="page">{{region.name}}</li>
            </ol>
        </nav>

        <h4 class="text-capitalize mt-3 mb-4 font-weight-bold">Daftar Siswa Rayon {{region.name}}</h4>
        <div class="col-md-6">
            <div class="d-flex">
                <router-link v-bind:to="{ name: 'regions.students.add', params: {page: 8, region: region.id} }" class="btn btn-primary btn-block mt-md-1">
                    <span class="fas fa-plus"></span> Tambah Siswa
                </router-link>
            </div>
        </div>
        <div class="table-responsive mt-3">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <vue-good-table
                            mode="remote"
                            :columns="columns"
                            :rows="rows"
                            :pagination-options="{enabled: false}"
                            @on-sort-change="onSortChange"
                            @on-column-filter="onColumnFilter"
                            :sort-options="{enabled: true}"
                            :fixed-header="true"
                            :line-numbers="true"
                            max-height="800px"
                            styleClass="vgt-table condensed striped"
                        >
                        <template slot="table-row" slot-scope="props">
                            <span v-if="props.column.field === 'id'">
                                <a href="#" title="Hapus" @click="showStudent(props.row.id)">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                </a>
                            </span>
                        </template>
                        </vue-good-table>
                        <pagination v-if="!withoutPagination" class="mt-3" :pagination="pages" @paginate="getStudents" :offset="2" :data="payloadGet"></pagination>
                    </tr>
                </tbody>
            </table>
        </div>

        <modal v-if="modalDelete" @close="modalDelete = false" :deleteOpt="deleteStudent">
            <h5 slot="header">Hapus Siswa dari Rayon</h5>
            <div slot="body">
                <span>Yakin untuk menghapus siswa <b>{{student.name}}</b> dari data rayon <b>{{region.name}}</b>?</span>
            </div>
        </modal>
        </div>
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
    name: 'StudentsInRegion',
    components: {
        "modal": modalComponent,
        "pagination": paginateComponent
    },
    data(){
        return {
            modalDelete: false,
            columns: [
                {
                    label: 'NIS',
                    field: 'nis',
                    filterOptions: { enabled: true },
                },
                {
                    label: 'Nama',
                    field: 'name',
                    filterOptions: { enabled: true },
                },
                {
                    label: 'Rombel',
                    field: 'student_group.name',
                    filterOptions: { enabled: true },
                },
                {
                    label: "Aksi",
                    field: "id",
                    tdClass: "text-center",
                    sortable: false,
                    filterOptions: { enabled: false},
                },
            ],
            rows: [],
            region: {},
            student: {},
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
                region: this.$route.params.region,
                search: '',
                searchVal: '',
                page: 1,
                per_page: 20,
                field:"student_group_id",
                sort:"ASC"
            },
            withoutPagination: false
        }
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
        ...mapActions('students', ['filterByRegion', 'studentDetail', 'update']),
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
            this.filterByRegion(payload).then((result) => {
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
            } else if(params[0].field == "student_group.name") {
                this.payloadGet.field = "student_group_id";
                this.payloadGet.sort = params[0].type;
            } else{
                this.payloadGet.field = params[0].field;
                this.payloadGet.sort = params[0].type;
            }
            this.getStudents(this.payloadGet);
        },
        showStudent(id) {
            this.studentDetail(id).then((result) => {
                this.student = result;
                this.modalDelete = true;
            })
        },
        deleteStudent() {
            this.student.region_id = null;
            let payload = {id: this.student.id, data: this.student};
            this.update(payload).then((result) => {
                this.modalDelete = false;
                this.getStudents(this.payloadGet);
            })
        }
    }
};
</script>

<style scoped>
h4 {
    font-weight: 600;
}
</style>