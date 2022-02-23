<template>
    <div class="mt-3">
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'batches', params: {page: 5} }"><a href="#">data siswa</a></router-link></li>
                <li class="breadcrumb-item" aria-current="page"><router-link v-bind:to="{ name: 'student_groups', params: {page: 5, batch: $route.params.batch} }"><a href="#">{{batchName}}</a></router-link></li>
                <li class="breadcrumb-item active" aria-current="page" @click="modalEdit = true">{{studentGroup}} <span class="fas fa-pen"></span></li>
            </ol>
        </nav>

        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>
        <div class="row my-3">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Siswa....">
                    <div class="input-group-append">
                        <a href="#" class="btn btn-outline-muted btn-lg shadow-sm bg-white"><i class="fas fa-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex">
                    <a href="#" class="btn btn-primary btn-block mt-md-1">
                        <span class="fas fa-plus"></span> Tambah Siswa
                    </a>
                    <a href="#" class="btn btn-secondary btn-block mt-md-1">Refresh Data</a>
                </div>
            </div>
        </div>

        <div class="row mt-2 d-flex justify-content-center">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data siswa rombel RPL XII-2</h4>
                        <p class="card-description">Angkatan 22</p>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>NIS</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Hitesh Chauhan</td>
                                        <td>Engine</td>
                                        <td>
                                            <label class="badge bg-danger">Hapus</label>
                                            <label class="badge bg-green1 text-white">Edit</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Samso Palto</td>
                                        <td>Brakes</td>
                                        <td>
                                            <label class="badge bg-danger">Hapus</label>
                                            <label class="badge bg-green1 text-white">Edit</label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal -->
        <modal v-if="modalEdit" @close="modalEdit = false" :action="editStudentGroup">
            <h5 slot="header">Edit Rombel</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group">
                    <label class="mb-2">Jurusan</label>
                    <select2 :options="majors" :reduce="major => major.id" label="abbreviation" v-model="editForm.major_id" :class="{'is-invalid': errors.major_id}"></select2>
                    <div class="invalid-feedback" v-if="errors.major_id">
                        {{ errors.major_id[0] }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Nama Rombel</label>
                    <input type="text" class="form-control" v-model="editForm.name" :class="{'is-invalid': errors.name}" placeholder="RPL XII-2">
                    <div class="invalid-feedback" v-if="errors.name">
                        {{ errors.name[0] }}
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger" slot="button" @click="showModalDeleteStudentGroup">Hapus</button>
        </modal>

        <modal v-if="modalDeleteStudentGroup" @close="modalDeleteStudentGroup = false" :deleteOpt="deleteStudentGroup">
            <h5 slot="header">Hapus Angkatan</h5>
            <div slot="body">
                <span><b>Semua data</b> yang berkaitan dengan <b class="text-capitalize">{{studentGroup}}</b> juga akan <b>terhapus</b> dan <b>tidak dapat diakses kembali</b>. Yakin tetap menghapus data <b class="text-capitalize">{{studentGroup}}</b>?</span>
            </div>
        </modal>
    </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
// modal
import modalComponent from '../../../components/Modal.vue';
// vue-select
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
    name: "studentData",
    components: {
        "modal": modalComponent,
        "select2": vSelect
    },
    data() {
        return {
            batchName: null,
            studentGroup: null,
            majors: [],
            editForm: {},
            batch: {},
            modalEdit: false,
            modalDeleteStudentGroup: false
        }
    },
    created() {
        this.getMajors();
        this.showStudentGroup(this.$route.params.group);
        this.showBatch(this.$route.params.batch);
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('majors', ['allData']),
        ...mapActions('StudentGroups', ['detail', 'edit']),
        ...mapActions('batches', ['show']),

        getMajors() {
            this.allData().then((result) => {
                this.majors = result;
            })
        },
        showStudentGroup(id) {
            this.detail(id).then((result) => {
                this.editForm = result;
                this.studentGroup = result.name;
            })
        },
        editStudentGroup() {
            let payload = {id: this.$route.params.group, data: this.editForm};
            this.edit(payload).then((result) => {
                this.modalEdit = false;
                this.showStudentGroup(result.id);
            })
        },
        showModalDeleteStudentGroup() {
            this.modalEdit = false;
            this.modalDeleteStudentGroup = true;
        },
        deleteStudentGroup() {
            console.log('delete');
        },
        showBatch(id) {
            this.show(id).then((result) => {
                this.batch = result;
                this.batchName = result.batch_name;
            })
        }
    }
}
</script>

<style scoped>
.breadcrumb {
    font-size: 1.2rem;
    text-transform: capitalize;
}

.breadcrumb .breadcrumb-item a {
    color: #333;
}

.breadcrumb .breadcrumb-item.active {
    cursor: pointer;
}

.breadcrumb-item span {
    font-size: 0.9rem;
    margin-left: 5px;
}

.input-text:focus {
    box-shadow: 0px 0px 0px;
    border-color: #B4ADAD;
    outline: 0px;
}

.form-control {
    border: 1px solid #B4ADAD;
}

.btn-outline-muted {
    color: #535353;
    border-color: #B4ADAD;
    border-radius: 0px !important;
}

.btn-secondary {
    margin-left: 10px !important;
}

/* table */
.flex {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    background-color: #fff;
    border: 1px solid #d2d2dc;
    border-radius: 0;
}

.card .card-title {
    color: #000;
    margin-bottom: 10px;
    text-transform: capitalize;
    font-size: 1rem;
    font-weight: 600;
}

.card .card-description {
    color: #B4ADAD;
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
}

.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    -ms-overflow-style: -ms-autohiding-scrollbar;
}

.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}

.table thead th {
    border-top: 0;
    border-bottom-width: 1px;
    font-size: 1rem;
    text-transform: uppercase;
}

.table td {
    font-size: 1rem;
    padding: 0.5rem;
}

.badge {
    border-radius: 0;
    font-size: 14px;
    line-height: 1;
    padding: 0.3rem 0.5rem;
    font-weight: normal;
    cursor: pointer;
}

@media (max-width: 575px) {
    .btn, .input-text {
        font-size: 0.8rem !important;
    }
    .btn {
        padding: 0.2rem 0.5rem !important;
    }
    .breadcrumb {
        font-size: 1rem;
    }
}
</style>