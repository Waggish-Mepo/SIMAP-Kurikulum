<template>
    <div class="mt-2">
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'batches', params: {page: 4} }"><a href="#">data siswa</a></router-link></li>
                <li class="breadcrumb-item active" aria-current="page" @click="modalEdit = true">{{batch}} <span class="fas fa-pen"></span></li>
            </ol>
        </nav>

        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Rombel...." @keyup="searchStudentGroup" v-model="payload.search">
                    <div class="input-group-append">
                        <a href="#" class="btn btn-outline-muted btn-lg shadow-sm bg-white"><i class="fas fa-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-lg-0 mb-3">
                <div class="d-flex">
                    <a href="#" class="btn btn-primary btn-block mt-md-1" @click="modalAdd = true">
                        <span class="fas fa-plus"></span> Tambah Rombel
                    </a>
                    <a href="#" class="btn btn-secondary btn-block mt-md-1" @click="refreshStudentGroup">Refresh Data</a>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-2 mb-4">
            <div></div>
            <div class="d-flex flex-wrap float-right">
                <p class="text-capitalize pt-2 font-weight-bold">lihat berdasarkan :</p>
                <select class="form-select border-primary text-primary" @change="sortByMajor">
                    <option selected hidden>Jurusan</option>
                    <option v-for="(major, index) in majors" :key="index" :value="major.id">{{major.abbreviation}}</option>
                </select>
            </div>
        </div>

        <div class="mb-2" v-for="(sg, index) in studentGroups" :key="index">
            <div class="card bg-white w-100 shadow-sm p-3 mb-3 text-capitalize">
                <router-link v-bind:to="{ name: 'students', params: {page: 4, batch: $route.params.batch, group: sg.id} }" class="router">
                    <div class="d-flex align-items-center text-dark text-uppercase">
                        <span class="fas fa-chalkboard"></span> {{sg.name}}
                    </div>
                </router-link>
            </div>
        </div>
        <pagination v-if="studentGroups.length > 0" class="mt-3" :pagination="pages" @paginate="getStudentGroups" :offset="2" :data="payload"></pagination>

        <!-- if data null -->
        <div v-if="studentGroups.length < 1" class="w-100 card-not-found">
            <img src="/assets/img/sad.png" alt="not found" class="d-block img m-auto">
            <h5 class="text-center text-capitalize mt-4">data terkait tidak ditemukan</h5>
        </div>

        <!-- modal -->
        <modal v-if="modalEdit" @close="modalEdit = false" :action="editBatch">
            <h5 slot="header">Edit Angkatan</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group">
                    <label class="mb-2">Nama Angkatan</label>
                    <input type="text" class="form-control" v-model="editForm.batch_name" :class="{'is-invalid': errors.batch_name}" placeholder="Angkatan 22">
                    <div class="invalid-feedback" v-if="errors.batch_name">
                        {{ errors.batch_name[0] }}
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger" slot="button" @click="showModalDelete">Hapus</button>
        </modal>

        <modal v-if="modalDelete" @close="modalDelete = false" :deleteOpt="deleteBatch">
            <h5 slot="header">Hapus Angkatan</h5>
            <div slot="body">
                <span><b>Semua data</b> yang berkaitan dengan <b class="text-capitalize">{{batch}}</b> juga akan <b>terhapus</b> dan <b>tidak dapat diakses kembali</b>. Yakin tetap menghapus data <b class="text-capitalize">{{batch}}</b>?</span>
            </div>
        </modal>

        <modal v-if="modalAdd" @close="modalAdd = false" :action="addStudentGroup">
            <h5 slot="header">Tambah Rombel</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group">
                    <label class="mb-2">Jurusan</label>
                    <select2 :options="majors" :reduce="major => major.id" label="abbreviation" v-model="addForm.major_id" :class="{'is-invalid': errors.major_id}"></select2>
                    <div class="invalid-feedback" v-if="errors.major_id">
                        {{ errors.major_id[0] }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Nama Rombel</label>
                    <input type="text" class="form-control" v-model="addForm.name" :class="{'is-invalid': errors.name}" placeholder="RPL XII-2">
                    <div class="invalid-feedback" v-if="errors.name">
                        {{ errors.name[0] }}
                    </div>
                </div>
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
// pagination
import paginateComponent from '../../../components/Pagination.vue';
export default {
    name: "studentGroup",
    components: {
        "modal": modalComponent,
        "select2": vSelect,
        "pagination": paginateComponent
    },
    data() {
        return {
            studentGroups: [],
            pages: {
                total: 0,
                per_page: 10,
                from: 1,
                to: 0,
                current_page: 1,
                last_page: 1,
            },
            payload: {
                search: '',
                batch: this.$route.params.batch,
                sort: '',
                page: 1,
                per_page: 5
            },
            modalEdit: false,
            editForm: {},
            batch: null,
            modalDelete: false,
            modalAdd: false,
            addForm: {
                name: null,
                batch_id: this.$route.params.batch,
                major_id: null
            },
            majors: []
        }
    },
    created() {
        this.getStudentGroups(this.payload);
        this.getBatch(this.$route.params.batch);
        this.getMajors();
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('studentGroups', ['create', 'index']),
        ...mapActions('batches', ['show', 'edit']),
        ...mapActions('majors', ['allData']),

        getStudentGroups(payload) {
            this.index(payload).then((result) => {
                this.studentGroups = result.data;
                this.pages.total = result.total;
                this.pages.per_page = result.per_page;
                this.pages.from = result.from;
                this.pages.to = result.to;
                this.pages.current_page = result.current_page;
                this.pages.last_page = result.last_page;
            })
        },
        searchStudentGroup() {
            this.getStudentGroups(this.payload);
        },
        refreshStudentGroup() {
            this.payload.search = '';
            this.payload.sort = '';
            this.getStudentGroups(this.payload);
        },
        sortByMajor(e) {
            this.payload.sort = e.target.value;
            this.payload.search = '';
            this.getStudentGroups(this.payload);
        },
        getBatch(id) {
            this.show(id).then((result) => {
                this.editForm = result;
                this.batch = result.batch_name;
            })
        },
        editBatch() {
            let payload = {id: this.editForm.id, data: this.editForm};
            this.edit(payload).then((result) => {
                this.modalEdit = false;
                this.getBatch(result.id);
            })
        },
        showModalDelete() {
            this.modalEdit = false;
            this.modalDelete = true;
        },
        deleteBatch() {
            console.log('delete');
        },
        getMajors() {
            this.allData().then((result) => {
                this.majors = result;
            })
        },
        addStudentGroup() {
            this.create(this.addForm).then((result) => {
                this.modalAdd = false;
                this.addForm.name = null;
                this.addForm.major_id = null;
                this.getStudentGroups(this.payload);
            })
        }
    }
}
</script>

<style scoped>
.btn-outline-muted {
    color: #535353;
    border-color: #B4ADAD;
    border-radius: 0px !important;
}

.btn-secondary {
    margin-left: 10px !important;
}

.card {
    font-weight: 500;
    font-size: 1rem;
    cursor: pointer;
}

a.router {
    text-decoration: none !important;
    color: #000 !important;
}

.card span {
    margin-right: 8px;
}

h5 {
    font-weight: 600;
    margin-bottom: 15px;
}

.form-group {
    margin-bottom: 10px;
}

.form-select {
    width: 8rem;
    margin-left: 8px;
    padding: 0.2rem 2rem 0.2rem 0.5rem !important;
    height: 2.3rem;
    margin-top: 4px;
    font-size: 0.8rem;
}

@media (max-width: 575px) {
    .btn.btn-primary,
    .btn.btn-secondary,
    .input-text,
    .card {
        font-size: 0.8rem !important;
    }
    .btn.btn-primary,
    .btn.btn-secondary {
        padding: 0.2rem 0.5rem !important;
    }
    .form-control {
        padding: 0 0.5rem !important;
    }
    .form-select {
        font-size: 0.8rem !important;
    }
}
</style>