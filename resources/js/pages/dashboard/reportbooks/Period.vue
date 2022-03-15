<template>
    <div>
        <div class="loader" v-if="isLoading"></div>
        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>
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
            <a v-for="(period, index) in periods" :key="index" href="#" class="card w-100 bg-white shadow-sm p-3 d-flex" @click="showModal(period.id, period.school_year)">
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
        <modal v-if="modalCurriculum" @close="modalCurriculum = false" :action="setCurriculum">
            <h5 slot="header">Ubah Template Rapor</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2"><b>Kelas 10</b></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="10-k13" value="K13 Revisi 2017 | Permendikbud No. 37 Tahun 2018" v-model="grade10.curriculum">
                        <label class="form-check-label" for="10-k13">
                            Kurikulum 2013
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="10-k21" value="K21 | Sekolah Penggerak" v-model="grade10.curriculum">
                        <label class="form-check-label" for="10-k21">
                            Kurikulum Sekolah Penggerak
                        </label>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2"><b>Kelas 11</b></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="11-k13" value="K13 Revisi 2017 | Permendikbud No. 37 Tahun 2018" v-model="grade11.curriculum">
                        <label class="form-check-label" for="11-k13">
                            Kurikulum 2013
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="11-k21" value="K21 | Sekolah Penggerak" v-model="grade11.curriculum">
                        <label class="form-check-label" for="11-k21">
                            Kurikulum Sekolah Penggerak
                        </label>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2"><b>Kelas 12</b></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="12-k13" value="K13 Revisi 2017 | Permendikbud No. 37 Tahun 2018" v-model="grade12.curriculum">
                        <label class="form-check-label" for="12-k13">
                            Kurikulum 2013
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="12-k21" value="K21 | Sekolah Penggerak" v-model="grade12.curriculum">
                        <label class="form-check-label" for="12-k21">
                            Kurikulum Sekolah Penggerak
                        </label>
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
// pagination
import paginateComponent from '../../../components/Pagination.vue';
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
            modalCurriculum: false,
            entry_years: [],
            grade10: {
                curriculum: 'K21 | Sekolah Penggerak',
                entry_year: null,
                school_year: null,
            },
            grade11: {
                curriculum: 'K13 Revisi 2017 | Permendikbud No. 37 Tahun 2018',
                entry_year: null,
                school_year: null,
            },
            grade12: {
                curriculum: 'K13 Revisi 2017 | Permendikbud No. 37 Tahun 2018',
                entry_year: null,
                school_year: null,
            },
            periodId: null
        }
    },
    created() {
        this.getEntryYears();
        this.getPeriods(this.payload);
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('reportPeriods', ['index', 'show']),
        ...mapActions('courses', ['entryYears']),
        ...mapActions('reportbooks', ['checkReportbook', 'create']),

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
        getEntryYears() {
            this.entryYears().then((result) => {
                this.entry_years = result;
                this.grade10.entry_year = result[10];
                this.grade11.entry_year = result[11];
                this.grade12.entry_year = result[12];
            })
        },
        showModal(period, schoolYear) {
            this.checkReportbook(period).then((result) => {
                if(result.length >= 1) {
                    this.$router.push({name: 'reportbooks.periods.students', params: {page: 7, period: period}});
                } else {
                    this.periodId = period;
                    this.grade10.school_year = schoolYear;
                    this.grade11.school_year = schoolYear;
                    this.grade12.school_year = schoolYear;
                    this.modalCurriculum = true;
                }
            })
        },
        setCurriculum() {
            let array = [];
            array.push(this.grade10, this.grade11, this.grade12);
            let payload = {reportPeriodId: this.periodId, data: array};
            this.create(payload).then((result) => {
                this.$router.push({name: 'reportbooks.periods.students', params: {page: 7, period: this.periodId}});
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