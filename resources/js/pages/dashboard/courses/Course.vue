<template>
    <div class="mt-3">
        <div class="loader" v-if="isLoading"></div>
        <div class="alert alert-danger mb-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Mata Pelajaran...." @keyup="searchSubject" v-model="payload.search">
                    <div class="input-group-append">
                        <a href="#" class="btn btn-outline-muted btn-lg shadow-sm bg-white" @click="searchSubject"><i class="fa fa-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <a href="#" class="btn btn-secondary btn-block mt-md-1" @click="refreshDataSubjects">Refresh Data</a>
            </div>
        </div>
        <div class="card w-100 bg-white p-2 mt-3" @click="modalAdd = true">
            <a href="#"><span class="fas fa-plus mr-3"></span> Tambah Pelajaran</a>
        </div>
        <div class="col-12 mt-3">
            <div v-for="(subject, index) in courses" :key="index" class="mb-2">
                <div class="card card-subject w-100 shadow-sm bg-white p-3" data-bs-toggle="collapse" aria-expanded="false" @click="showPanelCollapse(subject.id, index)">
                    <div class="d-flex justify-content-between text-capitalize">
                        <div><span class="fas fa-book"></span>
                        {{subject.name}}</div>
                        <div>
                            <i class="fas fa-arrow-down" :id="index"></i>
                        </div>
                    </div>
                </div>
                <div class="collapse" :id="subject.id">
                    <ul class="list-group" v-if="subject.data.length > 0">
                        <li class="list-group-item" v-for="(course, index) in subject.data" :key="index">
                            <router-link v-bind:to="{ name: 'courses.students', params: {page: 5, course: course.id} }" class="router">
                            <a href="#" class="text-capitalize">Kelas {{course.entry_year_with_class}} | {{course.caption}} | {{course.major_details_string}}</a>
                            </router-link>
                        </li>
                    </ul>
                    <ul class="list-group" v-if="subject.data.length < 1">
                        <li class="list-group-item text-capitalize text-center">belum terdapat data pelajaran</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- if data null -->
        <div v-if="courses.length < 1" class="w-100 card-not-found">
            <img src="/assets/img/sad.png" alt="not found" class="d-block img m-auto">
            <h5 class="text-center text-capitalize mt-4">data terkait tidak ditemukan</h5>
        </div>

        <!-- modal -->
        <modal v-if="modalAdd" @close="modalAdd = false" :action="checkCourse">
            <h5 slot="header">Tambah Pelajaran</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group">
                    <label class="mb-2">Kurikulum Acuan</label>
                    <select2 :options="curriculums" v-model="submitForm.curriculum" :class="{'is-invalid': errors.curriculum}"></select2>
                    <div class="invalid-feedback" v-if="errors.curriculum">
                        {{ errors.curriculum[0] }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Jurusan</label>
                    <select2 :options="majors" :reduce="major => major.id" label="abbreviation" v-model="submitForm.majors" :class="{'is-invalid': errors.majors}" multiple></select2>
                    <div class="invalid-feedback" v-if="errors.majors">
                        {{ errors.majors[0] }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Mata Pelajaran</label>
                    <select2 :options="subjects" :reduce="subject => subject.id" label="name" v-model="submitForm.subject_id" :class="{'is-invalid': errors.subject_id}"></select2>
                    <div class="invalid-feedback" v-if="errors.subject_id">
                        {{ errors.subject_id[0] }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Kelas/Angkatan Masuk</label>
                    <small class="text-danger" v-if="errors.entry_year">
                        {{ errors.entry_year[0] }}
                    </small>
                    <div v-for="(year, index) in entry_years" :key="index" class="form-check">
                        <input class="form-check-input" type="radio" :value="year" v-model="submitForm.entry_year" :id="index">
                        <label class="form-check-label text-capitalize" :for="index">
                            {{ "Kelas " +  index + " | Angkatan Masuk " + year.substr(0, 4) }}
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Keterangan</label>
                    <input type="text" class="form-control" placeholder="contoh : matematika IT" v-model="submitForm.caption" :class="{'is-invalid': errors.caption}">
                    <div class="invalid-feedback" v-if="errors.caption">
                        {{ errors.caption[0] }}
                    </div>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
// modal
import modalComponent from '../../../components/Modal.vue';
// vue-select
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
export default {
    name: "Courses",
    components: {
        "modal": modalComponent,
        "select2": vSelect,
    },
    data() {
        return {
            modalAdd: false,
            subjects: [],
            curriculums: [],
            majors: [],
            courses: [],
            subject: {},
            submitForm: {
                curriculum: null,
                caption: null,
                entry_year: null,
                majors: [],
                subject_id: null
            },
            payload: {
                search: '',
                period: ''
            },
            entry_years: [],
        }
    },
    created() {
        this.getSubjects();
        this.getCurriculums();
        this.getMajors();
        this.getCourses(this.payload);
        this.getEntryYears();
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('courses', ['create', 'index', 'allCurriculums', 'entryYears']),
        ...mapActions('subjects', ['getAll', 'searchByCourse', 'detail']),
        ...mapActions('majors', ['allData']),

        getMajors() {
            this.allData().then((result) => {
                this.majors = result;
            })
        },
        getEntryYears() {
            this.entryYears().then((result) => {
                this.entry_years = result;
            })
        },
        searchSubject() {
            this.getCourses(this.payload);
        },
        refreshDataSubjects() {
            this.payload.search = '';
            this.getCourses(this.payload);
        },
        getSubjects() {
            this.getAll().then((result) => {
                this.subjects = result;
            })
        },
        getCurriculums() {
            this.allCurriculums().then((result) => {
                this.curriculums = result;
            })
        },
        showPanelCollapse(PBody, PTitle) {
            let panelBody = document.getElementById(PBody);
            panelBody.classList.toggle('show');
            let iconTitle = document.getElementById(PTitle);
            iconTitle.classList.toggle('fa-arrow-down');
            iconTitle.classList.toggle('fa-arrow-up');
        },
        getCourses(payload) {
            this.index(payload).then((result) => {
                this.courses = result;
            });
        },
        autoCaption(id) {
            this.detail(id).then((result) => {
                this.submitForm.caption = result.name;
                this.addCourse();
            });
        },
        checkCourse() {
            if (!this.submitForm.caption) {
                this.autoCaption(this.submitForm.subject_id);
            } else {
                this.addCourse();
            }
        },
        addCourse() {
            this.create(this.submitForm).then((result) => {
                this.modalAdd = false;
                this.submitForm.curriculum = null;
                this.submitForm.caption = null;
                this.submitForm.entry_year = null;
                this.submitForm.majors = [];
                this.submitForm.subject_id = null;
                this.getCourses(this.payload);
            });
        }
    }
}
</script>

<style scoped>
a:hover {
    text-decoration: none;
}

.btn-outline-muted {
    color: #535353;
    border-color: #B4ADAD;
    border-radius: 0px !important;
}

.card {
    border: 0 !important;
}

.card-subject {
    cursor: pointer;
}

.card a {
    font-weight: 500;
    padding: 8px 10px;
    font-size: 16px;
}

.list-group {
    border-radius: 0 !important;
}

.list-group-item {
    border-left: 0 !important;
    border-right: 0 !important;
    background-color: #eaeaea !important;
    padding-left: 3rem;
}

.list-group-item a.router {
    color: #333;
}

.list-group-item a.router:hover {
    text-decoration: underline;
}

a.router {
    text-decoration: none !important;
    color: #000 !important;
}

span.fas.fa-book {
    margin-right: 5px;
}

.form-group {
    margin-bottom: 15px;
}

label.mb-2 {
    font-weight: 600;
}

@media (max-width: 470px) {
    .input-text,
    .card a {
        font-size: 0.9rem;
    }

    .btn {
        font-size: 0.8 !important;
        padding: 0.2rem 0.5rem !important;
    }

    i {
        font-size: 1rem;
    }

    .list-group-item {
        font-size: 0.8rem;
    }

    .card a {
        padding: 4px 5px;
    }
}
</style>
