<template>
    <div class="mt-2">
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'courses', params: {page: 5} }"><a href="#">Pelajaran</a></router-link></li>
                <li class="breadcrumb-item active" aria-current="page" @click="showCourse(course.id)">{{course.caption}} Kelas {{course.entry_year_with_class}}<span class="fas fa-pen"></span></li>
            </ol>
        </nav>

        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>
        <div class="row my-3">
            <div class="col-12">
                <router-link v-bind:to="{ name: 'courses.students.add', params: {page: 5, course: $route.params.course} }" class="btn btn-primary btn-block mt-md-1">
                    <span class="fas fa-plus"></span> Tambah Siswa
                </router-link>
            </div>
        </div>

        <div class="alert alert-info mb-5">data berikut merupakan data siswa jurusan <strong>{{course.major_details_string}} kelas {{course.entry_year_with_class}}</strong> yang terdaftar pada pelajaran.</div>
        <div class="col-12 mt-3">
            <div v-for="(sc, index) in studentCourses" :key="index" class="mb-2">
                <div class="card card-course w-100 shadow-sm bg-white p-3" data-bs-toggle="collapse" aria-expanded="false" @click="showPanelCollapse(sc.id, index)">
                    <div class="d-flex justify-content-between text-capitalize">
                        <div><span class="fas fa-book"></span>
                        {{sc.name}}</div>
                        <div>
                            <i class="fas fa-arrow-down" :id="index"></i>
                        </div>
                    </div>
                </div>
                <div class="collapse" :id="sc.id">
                    <div class="card-table w-100">
                        <div class="card-body" v-if="sc.data.length > 0">
                            <h4 class="card-title">Data siswa rombel {{sc.name}}</h4>
                            <p class="card-description text-capitalize">{{course.caption}} Kelas {{course.entry_year_with_class}}</p>
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <vue-good-table
                                                :columns="columns"
                                                :rows="sc.data"
                                                :pagination-options="{ enabled: false }"
                                                :sort-options="sortOpts"
                                                :fixed-header="true"
                                                :line-numbers="true"
                                                max-height="800px"
                                                styleClass="vgt-table condensed striped"
                                            >
                                            <template slot="table-row" slot-scope="props">
                                                <span v-if="props.column.field === 'id'">
                                                    <a
                                                    href="#"
                                                    title="Hapus Siswa"
                                                    @click="showModalDeleteStudent(props.row.id, props.row.name)"
                                                    >
                                                    <label class="badge bg-danger text-white">Hapus</label>
                                                    </a>
                                                </span>
                                            </template>
                                            </vue-good-table>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-body text-center" v-else>Belum ada siswa terdaftar</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- if data null -->
        <div v-if="studentCourses.length < 1" class="w-100 card-not-found">
            <img src="/assets/img/sad.png" alt="not found" class="d-block img m-auto">
            <h5 class="text-center text-capitalize mt-4">data terkait tidak ditemukan</h5>
        </div>

        <!-- modal -->
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
                <div class="form-group" v-if="roleUser === 'ADMIN'">
                    <label class="mb-2">Mata Pelajaran</label>
                    <select2 :options="subjects" :reduce="subject => subject.id" label="name" v-model="updateForm.subject_id" :class="{'is-invalid': errors.subject_id}"></select2>
                    <div class="invalid-feedback" v-if="errors.subject_id">
                        {{ errors.subject_id[0] }}
                    </div>
                </div>
                <div class="form-group" v-if="roleUser === 'TEACHER'">
                    <label class="mb-2">Mata Pelajaran</label>
                    <input type="text" class="form-control" :value="subjectName" disabled>
                </div>
                <div class="form-group">
                    <label class="mb-2">Kelas/Angkatan Masuk</label>
                    <small class="text-danger" v-if="errors.entry_year">
                        {{ errors.entry_year[0] }}
                    </small>
                    <div v-for="(year, index) in entry_years" :key="index" class="form-check">
                        <input class="form-check-input" type="radio" :value="year" v-model="updateForm.entry_year">
                        <label class="form-check-label text-capitalize">
                            {{ "Kelas " +  index + " | Angkatan Masuk " + year.substr(0, 4) }}
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Keterangan</label>
                    <input type="text" class="form-control" placeholder="contoh : Matematika IT" v-model="updateForm.caption" :class="{'is-invalid': errors.caption}">
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

        <modal v-if="modalDeleteStudent" @close="modalDeleteStudent = false" :deleteOpt="deleteStudent">
            <h5 slot="header">Hapus Siswa Dari Pelajaran</h5>
            <div slot="body">
                <span>Yakin untuk menghapus siswa <b>{{payloadDelete.name}}</b>? siswa tidak akan terdaftar dalam pelajaran lagi, dan semua data terkait akan terhapus</span>
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
    name: "studentCourse",
    components: {
        "modal": modalComponent,
        "select2": vSelect
    },
    data() {
        return {
            roleUser: null,
            subjectName: null,
            course: {},
            modalEdit: false,
            modalDelete: false,
            modalDeleteStudent: false,
            payloadDelete: {
                id: null,
                name: null
            },
            curriculums: [],
            majors: [],
            subjects: [],
            entry_years: [],
            updateForm: {},
            studentCourses: [],
            columns: [
                {
                    label: "NIS",
                    field: "nis",
                    filterOptions: { enabled: true },
                },
                {
                    label: "Nama",
                    field: "name",
                    filterOptions: { enabled: true },
                },
                {
                    label: "Jenis Kelamin",
                    field: "jk",
                    filterOptions: { enabled: true },
                },
                {
                    label: "Aksi",
                    field: "id",
                    tdClass: "text-center",
                    sortable: false,
                },
            ],
            sortOpts: { enabled: true },
        }
    },
    created() {
        this.getRole();
        this.getCourse(this.$route.params.course);
        this.getStudentCourse(this.$route.params.course);
        this.getSubjects();
        this.getCurriculums();
        this.getMajors();
        this.getEntryYears();
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('courses', ['show', 'edit', 'allCurriculums', 'entryYears', 'deleteCourseCascade']),
        ...mapActions('majors', ['allData']),
        ...mapActions('subjects', ['getAll', 'detail']),
        ...mapActions('studentCourses', ['index', 'deleteCourseStudent']),

        getRole() {
            let user = JSON.parse(localStorage.getItem('user_data'));
            this.roleUser = user.role;
        },
        getCourse(id) {
            this.show(id).then((result) => {
                this.course = result;
            })
        },
        getStudentCourse(course) {
            this.index(course).then((result) => {
                this.studentCourses = result;
            })
        },
        showPanelCollapse(PBody, PTitle) {
            let panelBody = document.getElementById(PBody);
            panelBody.classList.toggle('show');
            let iconTitle = document.getElementById(PTitle);
            iconTitle.classList.toggle('fa-arrow-down');
            iconTitle.classList.toggle('fa-arrow-up');
        },
        getCurriculums() {
            this.allCurriculums().then((result) => {
                this.curriculums = result;
            })
        },
        getMajors() {
            this.allData().then((result) => {
                this.majors = result;
            })
        },
        getSubjects() {
            this.getAll().then((result) => {
                this.subjects = result;
            })
        },
        getEntryYears() {
            this.entryYears().then((result) => {
                this.entry_years = result;
            })
        },
        showCourse(id) {
            this.show(id).then((result) => {
                this.updateForm = result;
                this.detail(result.subject_id).then((result) => {
                    this.subjectName = result.name;
                });
                this.modalEdit = true;
            });
        },
        editCourse() {
            let payload = {id: this.updateForm.id, data: this.updateForm};
            this.edit(payload).then((result) => {
                this.modalEdit = false;
                this.getCourse(this.$route.params.course);
                this.getStudentCourse(this.$route.params.course);
            })
        },
        showModalDelete() {
            this.modalEdit = false;
            this.modalDelete = true;
        },
        deleteCourse() {
            this.deleteCourseCascade(this.course.id).then((result) => {
                this.modalDelete = false;
            });
        },
        showModalDeleteStudent(id, name) {
            this.payloadDelete.id = id;
            this.payloadDelete.name = name;
            this.modalDeleteStudent = true;
        },
        deleteStudent() {
            let payload = {id: this.course.id, studentId: this.payloadDelete.id};

            this.deleteCourseStudent(payload).then((result) => {
                this.modalDeleteStudent = false;
                this.getStudentCourse(this.$route.params.course);
            });
        }
    }
}
</script>

<style scoped>
a:hover {
    text-decoration: none;
}

.card {
    border: 0 !important;
}

.card-course {
    cursor: pointer;
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

/* table */
.flex {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
}

.card-table {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    border: none;
    border-radius: 0;
    background-color: #eaeaea;
}

.card-table .card-title {
    color: #000;
    margin-bottom: 10px;
    text-transform: capitalize;
    font-size: 1rem;
    font-weight: 600;
}

.card-table .card-description {
    color: #000;
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
}

.badge {
    border-radius: 0;
    font-size: 14px;
    line-height: 1;
    padding: 0.3rem 0.5rem;
    font-weight: normal;
    cursor: pointer;
}

@media (max-width: 470px) {
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
}
</style>

