<template>
    <div class="mt-2">
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'courses', params: {page: 5} }"><a href="#">Pelajaran</a></router-link></li>
                <li class="breadcrumb-item active" aria-current="page" @click="showCourse(course.id)">{{course.caption}} <span class="fas fa-pen"></span></li>
            </ol>
        </nav>

        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>
        <!-- <div class="card-body table-responsive p-0">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                    </tr>
                </tbody>
            </table>
        </div> -->

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
            course: {},
            modalEdit: false,
            modalDelete: false,
            curriculums: [],
            majors: [],
            subjects: [],
            entry_years: [],
            updateForm: {},
            // payload: {
            //     course: ''
            // },
            // rows: []
        }
    },
    created() {
        this.getCourse(this.$route.params.course);
        this.getSubjects();
        this.getCurriculums();
        this.getMajors();
        this.getEntryYears();
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('courses', ['show', 'edit', 'allCurriculums', 'entryYears']),
        ...mapActions('majors', ['allData']),
        ...mapActions('subjects', ['getAll']),
        // ...mapActions('studentCourses', ['index']),

        getCourse(id) {
            this.show(id).then((result) => {
                this.course = result;
            })
        },
        // getStudentCourse(course) {
        //     this.index(course).then((result) => {

        //     })
        // },
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
                this.modalEdit = true;
            });
        },
        editCourse() {
            let payload = {id: this.updateForm.id, data: this.updateForm};
            this.edit(payload).then((result) => {
                this.modalEdit = false;
                this.getCourse(this.$route.params.course);
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

</style>