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
        <div class="row mt-3 mb-sm-3 mb-4">
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Periode...." v-model="payload.search" @keyup="searchPeriods">
                    <div class="input-group-append">
                        <a href="#" class="btn btn-outline-muted btn-lg shadow-sm bg-white" @click="searchPeriods"><i class="fas fa-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <a href="#" class="btn btn-secondary btn-block mt-md-1" @click="refreshPeriods">Refresh Data</a>
            </div>
        </div>

        <div v-if="periods.length > 0">
            <a v-for="(period, index) in periods" :key="index" href="#" class="card w-100 bg-white shadow-sm p-3 d-flex" @click="getReportDetail(period.id)">
                <div class="d-flex">
                    <i class="far fa-calendar text-dark-gray"></i>
                    <div class="d-flex flex-column text-secondary">
                        <h5 class="text-capitalize font-weight-bold text-dark">{{period.title + ' - ' + period.school_year}}</h5>
                        <span class="text-date" v-if="!period.start_date || !period.end_date">rentang waktu belum diatur</span>
                        <span class="text-date" v-else>{{period.start_date | dateFormat}} - {{period.end_date | dateFormat}}</span>
                    </div>
                </div>
            </a>
            <pagination class="mt-3" :pagination="pages" @paginate="getPeriods" :offset="2" :data="payload"></pagination>
        </div>

        <!-- data null -->
        <div v-else class="w-100 card-not-found">
            <img src="/assets/img/sad.png" alt="not found" class="d-block img m-auto">
            <h5 class="text-center text-capitalize mt-4">data terkait tidak ditemukan</h5>
        </div>

        <!-- modal -->
        <modal v-if="modalPrint" @close="modalPrint = false">
            <h5 slot="header">Gagal!</h5>
            <div slot="body">
                <p>Data tidak ditemukan! <b>Rapor belum tersedia.</b> Silahkan untuk menghubungi pembimbing rayon terlebih dahulu.</p>
            </div>
        </modal>
        </div>
    </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
// pagination
import paginateComponent from '../../../components/Pagination.vue';
// modal
import modalComponent from '../../../components/Modal.vue';
export default {
    name: 'reportPeriods',
    components: {
        "pagination": paginateComponent,
        "modal": modalComponent
    },
    data() {
        return {
            periods: [],
            pages: {
                total: 0,
                per_page: 10,
                from: 1,
                to: 0,
                current_page: 1,
                last_page: 1,
            },
            payload: {
                orderBy: '',
                schoolYear: '',
                search: '',
                page: 1,
                per_page: 5
            },
            student: {},
            reportbook: {},
            subjectGroups: [],
            reportNotFound: false,
            modalPrint: false
        }
    },
    created() {
        this.getPeriods(this.payload);
        this.getStudent();
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('reportPeriods', ['index']),
        ...mapActions('students', ['studentDetail']),
        ...mapActions('reportbooks', ['reportbookStudent', 'print', 'printAttitude']),

        getPeriods(search) {
            this.index(search).then((result) => {
                this.periods = result.data;
                this.pages.total = result.total;
                this.pages.per_page = result.per_page;
                this.pages.from = result.from;
                this.pages.to = result.to;
                this.pages.current_page = result.current_page;
                this.pages.last_page = result.last_page;
            })
        },
        searchPeriods() {
            this.getPeriods(this.payload);
        },
        refreshPeriods() {
            this.payload.orderBy = '';
            this.payload.schoolYear = '';
            this.payload.search = '';
            this.getPeriods(this.payload);
        },
        getStudent() {
            let student = JSON.parse(localStorage.getItem('user_data'));
            let id = student.userable_id;
            this.studentDetail(id).then((result) => {
                this.student = result;
            })
        },
        getReportDetail(reportPeriod) {
            let payload = {reportPeriodId: reportPeriod, studentId: this.student.id};
            this.reportbookStudent(payload).then((result) => {
                if (result === 'failed') {
                    this.modalPrint = true;
                } else {
                    this.reportbook = result;
                    this.subjectGroups = result.subjectGroups;
                    this.printReport(reportPeriod);
                }
            });
        },
        printReport(reportPeriod) {
            let payload = {reportPeriodId: reportPeriod, studentId: this.student.id, studentName: this.student.name, studentNIS: this.student.nis};
            this.print(payload).then(() => {});
            if (this.reportbook.curriculum !== 'K21 | Sekolah Penggerak' && this.reportbook.attitude_config.length >= 1) {
                this.printAttitude(payload).then(() => {})   
            }
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
    font-size: 1rem;
    margin-bottom: 10px;
}

a.router {
    text-decoration: none !important;
    color: #000 !important;
}

h5 {
    font-weight: 700;
    font-size: 0.9rem !important;
    margin-bottom: 0.2rem !important;
}

a:hover {
    text-decoration: none !important;
}

i.far.fa-calendar {
    font-size: 1.2rem;
    margin-right: 15px !important;
    padding-top: 0.8rem;
}

.text-date {
    font-size: 0.8rem;
}

@media (max-width: 575px) {
    a {
        font-size: 0.9rem !important;
    }
    h5 {
        font-size: 1rem;
    }
    i.far.fa-calendar {
        font-size: 1rem;
    }
    .btn.btn-secondary,
    .input-text {
        font-size: 0.8rem !important;
    }
    .btn.btn-secondary {
        padding: 0.2rem 0.5rem !important;
    }
}

@media (max-width: 330px) {
    .img {
        max-width: 80px;
    }
    .p-3 {
        padding: 0.8rem !important;
    }
}
</style>