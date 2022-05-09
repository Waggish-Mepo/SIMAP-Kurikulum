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
        <h4 class="text-capitalize mt-3 mb-4 font-weight-bold">rayon</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Rayon...." @keyup="searchRegion()" v-model="payloadGet.search">
                    <div class="input-group-append">
                        <a href="#" class="btn btn-outline-muted btn-lg shadow-sm bg-white" @click="searchRegion()"><i class="fa fa-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex">
                    <a href="#" class="btn btn-primary btn-block mt-md-1" @click="modalAdd = true">
                        <span class="fas fa-plus"></span> Tambah Rayon
                    </a>
                    <a href="#" class="btn btn-secondary btn-block mt-md-1" @click="payloadGet.search = ''; getRegions(payloadGet)">Refresh Data</a>
                </div>
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
                            :sort-options="sortOpts"
                            :fixed-header="true"
                            :line-numbers="true"
                            max-height="800px"
                            styleClass="vgt-table condensed striped"
                        >
                        <template slot="table-row" slot-scope="props">
                            <a href="#" class="text-dark" v-if="props.column.field === 'name'" @click="showRegion(props.row.id)">
                                {{props.row.name}}
                            </a>
                            <span v-if="props.column.field === 'teacher.name'">
                                <a href="#" v-if="props.row.teacher == null || props.row.teacher.length < 1" @click="showRegion(props.row.id)">Pilih Guru</a>
                                <a href="#" class="text-dark" v-else @click="showRegion(props.row.id)">{{props.row.teacher.name}}</a>
                            </span>
                            <span v-if="props.column.field === 'id'">
                                <router-link v-bind:to="{ name: 'regions.students', params: {page: 8, region: props.row.id} }">
                                    <a href="#">Pilih Siswa Rayon</a>
                                </router-link>
                            </span>
                        </template>
                        </vue-good-table>
                        <pagination class="mt-3" :pagination="pages" @paginate="getRegions" :offset="2" :data="payloadGet"></pagination>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- modal -->
        <modal v-if="modalAdd" @close="modalAdd = false" :action="addRegion">
            <h5 slot="header">Buat Rayon</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Nama Rayon</label>
                    <input type="text" class="form-control" placeholder="Contoh: Ciawi 1" v-model="regionPayload.name">
                </div>
            </div>
        </modal>

        <modal v-if="modalEdit" @close="modalEdit = false" :action="updateRegion">
            <h5 slot="header">Edit Rayon</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Nama Rayon</label>
                    <input type="text" class="form-control" placeholder="Contoh: Ciawi 1" v-model="regionEditPayload.name">
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Pembimbing Rayon</label>
                    <select2 :options="teachers" :reduce="name => name.id" label="name" v-model="regionEditPayload.teacher_id"></select2>
                </div>
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
// vue-select
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
    name: 'Regions',
    components: {
        "modal": modalComponent,
        "select2": vSelect,
        "pagination": paginateComponent
    },
    data(){
        return {
            modalAdd: false,
            modalEdit: false,
            sortOpts: { enabled: true },
            columns: [
                {
                    label: 'Rayon',
                    field: 'name',
                    filterOptions: { enabled: false },
                },
                {
                    label: 'Pembimbing Rayon',
                    field: 'teacher.name',
                    filterOptions: { enabled: false },
                },
                {
                    label: "Aksi",
                    field: "id",
                    sortable: false,
                    filterOptions: { enabled: false},
                },
            ],
            rows: [],
            teachers: [],
            regionPayload: {
                name: '',
                teacher_id: null
            },
            regionEditPayload: {},
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
                page: 1,
                per_page: 20,
                field:"created_at",
                sort:"ASC"
            },
        }
    },
    created() {
        this.getRegions(this.payloadGet);
        this.getTeachers();
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('regions', ['index', 'create', 'show', 'edit']),
        ...mapActions('teachers', ['getAll']),

        searchRegion() {
            this.getRegions(this.payloadGet);
        },
        getRegions(payload) {
            this.index(payload).then((result) => {
                this.rows = result.data;
                this.pages.total = result.total;
                this.pages.per_page = result.per_page;
                this.pages.from = result.from;
                this.pages.to = result.to;
                this.pages.current_page = result.current_page;
                this.pages.last_page = result.last_page;
            })
        },
        onSortChange(params) {
            if(params[0].type == "none"){
                this.payloadGet.field = "created_at";
                this.payloadGet.sort = "ASC";
            }else{
                this.payloadGet.field = params[0].field;
                this.payloadGet.sort = params[0].type;
            }
            this.getRegions(this.payloadGet);
        },
        getTeachers() {
            this.getAll().then((result) => {
                this.teachers = result;
            })
        },
        addRegion() {
            this.create(this.regionPayload).then((result) => {
                this.modalAdd = false;
                this.regionPayload.name = '';
                this.getRegions(this.payloadGet);
            })
        },
        showRegion(id) {
            this.show(id).then((result) => {
                this.regionEditPayload = result;
                this.modalEdit = true;
            })
        },
        updateRegion() {
            let payload = {id: this.regionEditPayload.id, data: this.regionEditPayload};
            this.edit(payload).then((result) => {
                this.modalEdit = false;
                this.getRegions(this.payloadGet);
            })
        }
    }
};
</script>

<style scoped>
h4 {
    font-weight: 600;
}

.btn-outline-muted {
    color: #535353;
    border-color: #B4ADAD;
    border-radius: 0px !important;
}

.btn-secondary {
    margin-left: 10px !important;
}
</style>
