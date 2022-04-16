<template>
    <div>
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'regions', params: {page: 8} }"><a href="#">Rayon</a></router-link></li>
                <li class="breadcrumb-item active" aria-current="page">{{region.name}}</li>
            </ol>
        </nav>

        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>

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
                            :columns="columns"
                            :rows="rows"
                            :pagination-options="paginationOpts"
                            :sort-options="sortOpts"
                            :fixed-header="true"
                            :line-numbers="true"
                            max-height="800px"
                            styleClass="vgt-table condensed striped"
                        >
                        <template slot="table-row" slot-scope="props">
                            <span v-if="props.column.field === 'id'">
                                <a href="#" title="Hapus">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                </a>
                            </span>
                        </template>
                        </vue-good-table>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus Siswa Dari Rayon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Yakin Akan Menghapus Siswa Ini Dari Rayon </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3 mb-3">
                        <button type="button" class="btn btn-outline-primary me-3">Batal</button>
                        <button type="button" class="btn btn-danger me-3">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
// modal
import modalComponent from '../../../components/Modal.vue';
export default {
    name: 'StudentsInRegion',
    components: {
        "modal": modalComponent
    },
    data(){
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
            region: {}
        }
    },
    created() {
        this.getRegion(this.$route.params.region);
        this.getStudents(this.$route.params.region);
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('students', ['filterByRegion']),
        ...mapActions('regions', ['show']),

        getRegion(id) {
            this.show(id).then((result) => {
                this.region = result;
            })
        },
        getStudents(region) {
            this.filterByRegion(region).then((result) => {
                this.rows = result;
            })
        },
    }
};
</script>

<style scoped>
h4 {
    font-weight: 600;
}
</style>