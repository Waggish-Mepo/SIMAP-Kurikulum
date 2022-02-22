<template>
    <div>
        <div class="loader" v-if="isLoading"></div>
        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>
        <div class="row my-3">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Angkatan...." v-model="search" @keyup="searchBatches">
                    <div class="input-group-append">
                        <a href="#" class="btn btn-outline-muted btn-lg shadow-sm bg-white" @click="searchBatches"><i class="fas fa-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex">
                    <a href="#" class="btn btn-primary btn-block mt-md-1" @click="modalAdd = true">
                        <span class="fas fa-plus"></span> Tambah Angkatan
                    </a>
                    <a href="#" class="btn btn-secondary btn-block mt-md-1" @click="refreshBatches">Refresh Data</a>
                </div>
            </div>
        </div>

        <div class="card bg-white w-100 shadow-sm p-3 mb-2 text-capitalize" v-for="(batch, index) in batches" :key="index">
            <!-- @click="showBatch(batch.id)" -->
            <router-link v-bind:to="{ name: 'student_groups', params: {page: 5, batch: batch.id} }" class="router">
            <div class="d-flex align-items-center text-dark">
                <span class="fas fa-trophy"></span> {{batch.batch_name}}
            </div>
            </router-link>
        </div>

        <!-- if data null -->
        <div v-if="batches.length < 1" class="w-100 card-not-found">
            <img src="/assets/img/sad.png" alt="not found" class="d-block img m-auto">
            <h5 class="text-center text-capitalize mt-4">data terkait tidak ditemukan</h5>
        </div>

        <!-- modal -->
        <modal v-if="modalAdd" @close="modalAdd = false" :action="addBatch">
            <h5 slot="header">Tambah Angkatan</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group">
                    <label class="mb-2">Nama Angkatan</label>
                    <input type="text" class="form-control" v-model="submitAddForm.batch_name" :class="{'is-invalid': errors.batch_name}" placeholder="Angkatan 22">
                    <div class="invalid-feedback" v-if="errors.batch_name">
                        {{ errors.batch_name[0] }}
                    </div>
                </div>
            </div>
        </modal>

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
                <span><b>Semua data</b> yang berkaitan dengan <b class="text-capitalize">{{editForm.batch_name}}</b> juga akan <b>terhapus</b> dan <b>tidak dapat diakses kembali</b>. Yakin tetap menghapus data <b class="text-capitalize">{{editForm.batch_name}}</b>?</span>
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
    name: "batch",
    components: {
        "modal": modalComponent
    },
    data() {
        return {
            batches: [],
            search: '',
            modalAdd: false,
            submitAddForm: {
                batch_name: null
            },
            modalEdit: false,
            editForm: {},
            modalDelete: false
        }
    },
    created() {
        this.getBatches(this.search);
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('batches', ['create', 'index', 'show', 'edit']),

        getBatches(search) {
            this.index(search).then((result) => {
                this.batches = result;
            })
        },
        searchBatches() {
            this.getBatches(this.search);
        },
        refreshBatches() {
            this.search = '';
            this.getBatches(this.search);
        },
        addBatch() {
            this.create(this.submitAddForm).then((result) => {
                this.modalAdd = false;
                this.getBatches(this.search);
            });
        },
        showBatch(id) {
            this.show(id).then((result) => {
                this.editForm = result;
                this.modalEdit = true;
            })
        },
        editBatch() {
            let payload = {id: this.editForm.id, data: this.editForm};
            this.edit(payload).then((result) => {
                this.modalEdit = false;
                this.getBatches(this.search);
            })
        },
        showModalDelete() {
            this.modalEdit = false;
            this.modalDelete = true;
        },
        deleteBatch() {
            console.log('delete');
        }
    }
}
</script>

<style scoped>
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

.card {
    font-weight: 500;
    font-size: 1rem;
    cursor: pointer;
}

.card-not-found {
    margin-top: 25px;
}

.img {
    max-width: 130px;
    width: 100%;
}

a.router {
    text-decoration: none !important;
    color: #000 !important;
}

.card span {
    margin-right: 8px;
}

@media (max-width: 575px) {
    .btn, .input-text {
        font-size: 0.8rem !important;
    }
    .btn {
        padding: 0.2rem 0.5rem !important;
    }
}
</style>