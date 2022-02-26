<template>
  <div class="mt-5">
    <div class="loader" v-if="isLoading"></div>
    <div class="alert alert-danger mb-3" v-if="errorMessage">
      {{ errorMessage }}
    </div>
    <div class="card w-100 bg-white p-3 mb-md-5 mb-3">
        <div class="d-flex flex-wrap">
            <h5 class="text-capitalize pt-2">lihat berdasarkan :</h5>
            <select class="form-select border-primary text-primary" @change="sortBySchoolYear">
                <option selected hidden>Tahun Ajaran</option>
                <option v-for="(year, index) in schoolYearList" :key="index" :value="year">{{year}}</option>
            </select>
        </div>
    </div>
    <div class="card w-100 card-add bg-white p-3 mb-3" @click="modalAdd = true">
        <a href="#"><span class="fas fa-plus mr-3"></span> Tambah Periode Rapor</a>
    </div>
    <div v-if="data.length > 0">
        <a v-for="(report, index) in data" :key="index" href="#" class="card w-100 bg-white shadow-sm p-3" @click="showReportPeriod(report.id)">
            <div class="d-flex">
                <i class="far fa-calendar text-dark-gray"></i>
                <div class="d-flex flex-column text-secondary">
                    <h5 class="text-capitalize font-weight-bold text-dark">{{report.title + ' - ' + report.school_year}}</h5>
                    <span class="text-date" v-if="!report.start_date || !report.end_date">rentang waktu belum diatur</span>
                    <span class="text-date" v-else>{{report.start_date | dateFormat}} - {{report.end_date | dateFormat}}</span>
                </div>
            </div>
        </a>
        <pagination class="mt-3" :pagination="pages" @paginate="getReportPeriods" :offset="2" :data="payloadGet"></pagination>
    </div>
    <!-- data null -->
    <div v-else class="w-100 card-not-found">
        <img src="/assets/img/sad.png" alt="not found" class="d-block img m-auto">
        <h5 class="text-center text-capitalize mt-4">data terkait tidak ditemukan</h5>
    </div>

    <!-- modal -->

    <modal v-if="modalAdd" @close="modalAdd = false" :action="addReportPeriod">
        <h5 slot="header">Tambah Periode Rapor</h5>
        <div slot="body">
            <div class="alert alert-danger mb-3" v-if="errorMessage">
                {{ errorMessage }}
            </div>
            <div class="form-group">
                <label class="mb-2">Periode Rapor</label>
                <input type="text" class="form-control" v-model="submitAddForm.title" :class="{'is-invalid': errors.title}">
                <div class="invalid-feedback" v-if="errors.title">
                    {{ errors.title[0] }}
                </div>
            </div>
        </div>
    </modal>

    <modal v-if="modalEdit" @close="modalEdit = false" :action="updateReportPeriod">
        <h5 slot="header">Atur Periode Rapor</h5>
        <div slot="body">
            <div class="alert alert-danger mb-3" v-if="errorMessage">
                {{ errorMessage }}
            </div>
            <div class="form-group mb-2">
                <label class="mb-2">Periode Rapor</label>
                <input type="text" class="form-control" v-model="submitEditForm.title" :class="{'is-invalid': errors.title}">
                <div class="invalid-feedback" v-if="errors.title">
                    {{ errors.title[0] }}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label>Tanggal Mulai</label>
                    <input type="date" class="form-control" v-model="submitEditForm.start_date" :class="{'is-invalid': errors.start_date}">
                    <div class="invalid-feedback" v-if="errors.start_date">
                        {{ errors.start_date[0] }}
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label>Tanggal Selesai</label>
                    <input type="date" class="form-control" v-model="submitEditForm.end_date" :class="{'is-invalid': errors.end_date}">
                    <div class="invalid-feedback" v-if="errors.end_date">
                        {{ errors.end_date[0] }}
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-outline-danger" slot="button" @click="showModalDelete">Hapus</button>
    </modal>

    <modal v-if="modalDelete" @close="modalDelete = false" :deleteOpt="deleteReportPeriod">
        <h5 slot="header">Hapus Periode Rapor</h5>
        <div slot="body">
            <span><b>Semua data</b> yang berkaitan dengan periode <b class="text-capitalize">{{submitEditForm.title}}</b> juga akan <b>terhapus</b> dan <b>tidak dapat diakses kembali</b>. Yakin tetap menghapus periode rapor <b class="text-capitalize">{{submitEditForm.title}}</b>?</span>
        </div>
    </modal>
  </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
// modal
import modalComponent from '../../components/Modal.vue';
// pagination
import paginateComponent from '../../components/Pagination.vue';
export default {
    name: "periodeRapor",
    components: {
        "modal": modalComponent,
        "pagination": paginateComponent
    },
    data() {
        return {
            modalAdd: false,
            modalEdit: false,
            modalDelete: false,
            pages: {
                total: 0,
                per_page: 10,
                from: 1,
                to: 0,
                current_page: 1,
                last_page: 1,
            },
            payloadGet: {
                orderBy: '',
                schoolYear: '',
                search: '',
                page: 1,
                per_page: 5
            },
            data: [],
            submitAddForm: {
                title: null,
            },
            submitEditForm: {
                title: null,
                start_date: null,
                end_date: null
            },
            schoolYearList: []
        }
    },
    created() {
        this.getReportPeriods(this.payloadGet);
        this.getSchoolYears();
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('reportPeriods', ['create', 'index', 'show', 'edit', 'schoolYears']),

        getSchoolYears() {
            this.schoolYears().then((result) => {
                this.schoolYearList = result;
            })
        },
        getReportPeriods(payload) {
            this.index(payload).then((result) => {
                this.data = result.data;
                this.pages.total = result.total;
                this.pages.per_page = result.per_page;
                this.pages.from = result.from;
                this.pages.to = result.to;
                this.pages.current_page = result.current_page;
                this.pages.last_page = result.last_page;
            })
        },
        sortBySchoolYear(e) {
            this.payloadGet.schoolYear = e.target.value;
            this.payloadGet.orderBy = 'school_year';
            this.getReportPeriods(this.payloadGet);
        },
        addReportPeriod() {
            this.create(this.submitAddForm).then((result) => {
                this.modalAdd = false;
                this.payloadGet.schoolYear = '';
                this.submitAddForm.title = null;
                this.getReportPeriods(this.payloadGet);
            });
        },
        showReportPeriod(id) {
            this.show(id).then((result) => {
                this.submitEditForm = result;
                this.modalEdit = true;
            });
        },
        updateReportPeriod() {
            let payload = {id: this.submitEditForm.id, data: this.submitEditForm};
            this.edit(payload).then((result) => {
                this.modalEdit = false;
                this.payloadGet.schoolYear = '';
                this.getReportPeriods(this.payloadGet);
            });
        },
        showModalDelete() {
            this.modalEdit = false;
            this.modalDelete = true;
        },
        deleteReportPeriod() {
            console.log('delete');
        }
    }
}
</script>

<style scoped>
.card {
    font-size: 1rem;
    margin-bottom: 10px;
}

.card-add {
    border: none !important;
}

a:hover {
    text-decoration: none !important;
}

h5 {
    font-weight: 700;
    font-size: 0.9rem !important;
    margin-bottom: 0.2rem !important;
}

i {
    font-size: 1.2rem;
    margin-right: 15px !important;
    padding-top: 0.8rem;
}

.text-date {
    font-size: 0.8rem;
}

.form-select {
    width: 10rem;
    margin-left: 8px;
}

@media (max-width: 575px) {
    a {
        font-size: 0.9rem !important;
    }
    .form-select {
        font-size: 0.8rem !important;
        width: 8rem;
    }
    h5 {
        font-size: 1rem;
    }
    i {
        font-size: 1rem;
    }
}

@media (max-width: 330px) {
    .img {
        max-width: 80px;
    }
    .p-3 {
        padding: 0.8rem !important;
    }
    .form-select {
        margin-left: 0;
        margin-top: 5px;
        padding: 0.2rem 2rem 0.2rem 0.5rem !important;
    }
    .text-secondary {
        font-size: 0.8rem !important;
    }
}
</style>
