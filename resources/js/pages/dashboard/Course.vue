<template>
    <div class="mt-3">
        <div class="loader" v-if="isLoading"></div>
        <div class="alert alert-danger mb-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="input-group mb-3">
                    <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Pelajaran...." @keyup="searchSubject" v-model="search">
                    <div class="input-group-append">
                        <a href="#" class="btn btn-outline-muted btn-lg shadow-sm bg-white" @click="searchSubject"><i class="fa fa-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <a href="#" class="btn btn-secondary btn-block mt-md-1" @click="getDataSubjects('')">Refresh Data</a>
            </div>
        </div>
        <div class="card w-100 bg-white p-2 mt-3" @click="modalAdd = true">
            <a href="#"><span class="fas fa-plus mr-3"></span> Tambah Pelajaran</a>
        </div>
        <div class="col-12 mt-3">
            <div v-for="(subject, index) in dataSubjects" :key="index" class="mb-2">
                <div class="card w-100 shadow-sm bg-white p-3" data-bs-toggle="collapse" aria-expanded="false" @click="showPanelCollapse(subject.id, index)">
                    <div class="d-flex justify-content-between text-capitalize">
                        <div><span class="fas fa-book"></span>
                        {{subject.name}}</div>
                        <div>
                            <i class="fas fa-arrow-down" :id="index"></i>
                        </div>
                    </div>
                </div>
                <div class="collapse" :id="subject.id">
                    <ul class="list-group">
                        <li class="list-group-item" v-for="(course, index) in filterCourses(subject.id)" :key="index">
                            <a href="#" class="text-capitalize" @click="showCourse(course.id)">{{course.entry_year | checkClass}} | {{course.caption}} | {{course.major_details_string}}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- modal -->
        <modal v-if="modalAdd" @close="modalAdd = false" :action="addCourse">
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
                    <!-- entry year sementara -->
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="2021/2022" v-model="submitForm.entry_year">
                        <label class="form-check-label text-capitalize">
                            kelas 10 | angkatan masuk 2021
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="2020/2021" v-model="submitForm.entry_year">
                        <label class="form-check-label text-capitalize">
                            Kelas 11 | angkatan masuk 2020
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="2019/2020" v-model="submitForm.entry_year">
                        <label class="form-check-label text-capitalize">
                            Kelas 12 | angkatan masuk 2019
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

        <modal v-if="modalEdit" @close="modalEdit = false" :action="editCourse">
            <h5 slot="header">Edit Pelajaran</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group">
                    <label class="mb-2">Kurikulum Acuan</label>
                    <select2 :options="curriculums" v-model="updateForm.curriculum" :class="{'is-invalid': errors.curriculum}"></select2>
                    <div class="invalid-feedback" v-if="errors.curriculum">
                        {{ errors.curriculum[0] }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Jurusan</label>
                    <select2 :options="majors" :reduce="major => major.id" label="abbreviation" v-model="updateForm.majors" :class="{'is-invalid': errors.majors}" multiple></select2>
                    <div class="invalid-feedback" v-if="errors.majors">
                        {{ errors.majors[0] }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Mata Pelajaran</label>
                    <select2 :options="subjects" :reduce="subject => subject.id" label="name" v-model="updateForm.subject_id" :class="{'is-invalid': errors.subject_id}"></select2>
                    <div class="invalid-feedback" v-if="errors.subject_id">
                        {{ errors.subject_id[0] }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Kelas/Angkatan Masuk</label>
                    <small class="text-danger" v-if="errors.entry_year">
                        {{ errors.entry_year[0] }}
                    </small>
                    <!-- entry year sementara -->
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="2021/2022" v-model="updateForm.entry_year">
                        <label class="form-check-label text-capitalize">
                            kelas 10 | angkatan masuk 2021
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="2020/2021" v-model="updateForm.entry_year">
                        <label class="form-check-label text-capitalize">
                            Kelas 11 | angkatan masuk 2020
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="2019/2020" v-model="updateForm.entry_year">
                        <label class="form-check-label text-capitalize">
                            Kelas 12 | angkatan masuk 2019
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Keterangan</label>
                    <input type="text" class="form-control" placeholder="contoh : matematika IT" v-model="updateForm.caption" :class="{'is-invalid': errors.caption}">
                    <div class="invalid-feedback" v-if="errors.caption">
                        {{ errors.caption[0] }}
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger" slot="button" @click="showModalDelete">Hapus</button>
        </modal>

        <modal v-if="modalDelete" @close="modalDelete = false" :deleteOpt="deleteCourse">
            <h5 slot="header">Hapus Pelajaran</h5>
            <div slot="body">
                <span>Yakin untuk menghapus pelajaran <b>{{updateForm.caption}}</b>? Semua data terkait <b>{{updateForm.caption}}</b> akan terhapus.</span>
            </div>
        </modal>
    </div>
</template>

<script>
// modal
import modalComponent from '../../components/Modal.vue';
// vue-select
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
export default {
    name: "Courses",
    components: {
        "modal": modalComponent,
        "select2": vSelect
    },
    data() {
        return {
            modalAdd: false,
            modalEdit: false,
            modalDelete: false,
            subjects: [],
            curriculums: [],
            majors: [],
            courses: [],
            submitForm: {
                curriculum: null,
                caption: null,
                entry_year: null,
                majors: [],
                subject_id: null
            },
            updateForm: {},
            dataSubjects: [],
            search: ''
        }
    },
    created() {
        this.getSubjects();
        this.getDataSubjects(this.search);
        this.getCurriculums();
        this.getMajors();
        this.getCourses();
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('courses', ['create', 'index', 'show', 'edit', 'allCurriculums']),
        ...mapActions('subjects', ['getAll', 'searchByCourse']),
        ...mapActions('majors', ['allData']),

        getMajors() {
            this.allData().then((result) => {
                this.majors = result;
            })
        },
        getDataSubjects(search) {
            this.searchByCourse(search).then((result) => {
                this.dataSubjects = result;
            });
        },
        searchSubject() {
            this.getDataSubjects(this.search);
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
        getCourses() {
            this.index().then((result) => {
                this.courses = result;
            });
        },
        filterCourses(subject) {
            return this.courses.filter(function(course) {
                return course.subject_id == subject;
            });
        },
        addCourse() {
            this.create(this.submitForm).then((result) => {
                this.modalAdd = false;
                this.getDataSubjects(this.search);
                this.getCourses();
            });
        },
        showCourse(id) {
            this.show(id).then((result) => {
                this.updateForm = result;
                this.modalEdit = true;
            });
        },
        editCourse() {
            let payload = {id: this.updateForm.id, data: this.updateForm};
            this.edit(payload).then((result) => {
                this.modalEdit = false;
                this.getDataSubjects(this.search);
                this.getCourses();
            })
        },
        showModalDelete() {
            this.modalEdit = false;
            this.modalDelete = true;
        },
        deleteCourse() {
            console.log('delete');
        }
    }
}
</script>

<style scoped>
a:hover {
    text-decoration: none;
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

.card {
    border: 0 !important;
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

.list-group-item a {
    color: #333;
}

.list-group-item a:hover {
    text-decoration: underline;
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