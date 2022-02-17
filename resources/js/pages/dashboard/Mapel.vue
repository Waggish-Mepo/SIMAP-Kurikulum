<template>
  <div>
    <div class="loader" v-if="isLoading"></div>
    <h4 class="text-capitalize mt-3 mb-4 font-weight-bold">mapel dan tim guru</h4>
    <div class="alert alert-danger my-3" v-if="errorMessage">
      {{ errorMessage }}
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="input-group mb-3"> 
                <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Mapel...." @keyup="searchSubject()" v-model="search">
                <div class="input-group-append"> 
                    <a href="#" class="btn btn-outline-muted btn-lg shadow-sm bg-white" @click="searchSubject()"><i class="fa fa-search"></i></a> 
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-flex">
                <a href="#" class="btn btn-primary btn-block mt-1" @click="modalAdd = true">
                    <span class="fas fa-plus"></span> Tambah Mapel
                </a>
                <a href="#" class="btn btn-secondary btn-block mt-1" @click="search = ''; getSubjects('')">Refresh Data</a>
            </div>
        </div>
    </div>
    <div class="table-responsive p-0 card-table mt-3">
        <table class="table table-bordered text-capitalize bg-white">
            <thead>
                <tr class="text-center">
                    <th></th>
                    <th>mata pelajaran</th>
                    <th>tim guru</th>
                </tr>
            </thead>
            <tbody style="border-top: 0;" v-for="(category, index) in categories" :key="index">
                <tr>
                    <th colspan="3" class="r-category">{{category | groupSubject}}</th>
                </tr>
                <tr v-for="(subject,index) in filterSubjects(category)" :key="index">
                    <td class="text-center">:</td>
                    <td><a href="#" class="text-dark" @click="showSubject(subject.id)">{{subject.name}}</a></td>
                    <td><a href="#" class="text-primary" @click="showSubject(subject.id)">pilih guru</a></td>
                    <!-- ini yang tampil cuman si yg if teacher.teachers -->
                    <!-- <td>
                        <ul>
                            <div v-for="(teacher,index) in filterSubjectTeachers(subject.id)" :key="index">
                                <li v-if="!teacher.teachers"><a href="#" class="text-primary" @click="showSubject(subject.id)">pilih guru</a></li>
                                <li v-if="teacher.teachers">{{teacher.teachers}}</li>
                            </div>
                        </ul>
                    </td> -->
                </tr>
            </tbody>
        </table>
    </div>

    <!-- modal -->

    <modal v-if="modalAdd" @close="modalAdd = false" :action="addSubject">
        <h5 slot="header">Tambah Mapel</h5>
        <div slot="body">
            <div class="alert alert-danger mb-3" v-if="errorMessage">
                {{ errorMessage }}
            </div>
            <div class="form-group">
                <label class="mb-2">Kelompok Mapel</label>
                <select2 :options="categories" v-model="subject.group"></select2>
                <div class="invalid-feedback" v-if="errors.group">
                    {{ errors.group[0] }}
                </div>
            </div>
            <div class="form-group">
                <div class="d-flex flex-column">
                    <label class="mb-1">Nama Mapel</label>
                    <small class="text-capitalize mb-2">pastikan mata pelajaran belum tersedia pada list table mapel</small>
                </div>
                <input type="text" class="form-control" v-model="subject.name">
                <div class="invalid-feedback" v-if="errors.name">
                    {{ errors.name[0] }}
                </div>
            </div>
        </div>
    </modal>

    <modal v-if="modalEdit" @close="modalEdit = false" :action="editSubject">
        <h5 slot="header">Edit Mapel</h5>
        <div slot="body">
            <div class="alert alert-danger mb-3" v-if="errorMessage">
                {{ errorMessage }}
            </div>
            <div class="form-group">
                <label class="mb-2">Kelompok Mapel</label>
                <select2 :options="categories" v-model="teacherSubject.group"></select2>
                <div class="invalid-feedback" v-if="errors.group">
                    {{ errors.group[0] }}
                </div>
            </div>
            <div class="form-group">
                <label class="mb-2">Nama Mapel</label>
                <input type="text" class="form-control" v-model="teacherSubject.name">
                <div class="invalid-feedback" v-if="errors.name">
                    {{ errors.name[0] }}
                </div>
            </div>
            <div class="form-group">
                <label class="mb-2">Guru Mapel</label>
                <select2 multiple :options="teachers" :reduce="name => name.id" label="name" v-model="teacherSubject.teachers"></select2>
                <div class="invalid-feedback" v-if="errors.teachers">
                    {{ errors.teachers[0] }}
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-outline-danger" slot="button" @click="showModalDelete">Hapus</button>
    </modal>

    <modal v-if="modalDelete" @close="modalDelete = false" :deleteOpt="deleteSubject">
        <h5 slot="header">Hapus Mapel</h5>
        <div slot="body">
            <p>Yakin akan menghapus mata pelajaran <b class="text-capitalize">bahasa indonesia</b>?</p>
            <p>Semua data nilai mata pelajaran <b class="text-capitalize">bahasa indonesia</b> dalam buku nilai akan terhapus.</p>
        </div>
    </modal>
  </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
// modal
import modalComponent from '../../components/Modal.vue';
// vue-select
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
    name: "subjects",
    components: {
        "modal": modalComponent,
        "select2": vSelect
    },
    data() {
        return {
            modalAdd: false,
            subject: {
                name: null,
                group: null,
            },
            categories: [
                'A (Muatan Nasional)',
                'B (Muatan Kewilayahan)',
                'C (Muatan Produktif)',
                'C1 (Dasar Bidang Keahlian)',
                'C2 (Dasar Program Keahlian)',
                'C3 (Dasar Kompetensi Keahlian)'
            ],
            modalEdit: false,
            teachers: [],
            modalDelete: false,
            dataSubject: [],
            teacherSubject: {
                group: null,
                name: null,
                order: null,
                subject_id: null,
                teachers: []
            },
            subjectId: null,
            dataSubjectTeacher: [],
            techerNotFound: null,
            teachersMatch: [],
            search: ''
        }
    },
    created() {
        this.getSubjects('');
        this.getTeachers();
        // this.getSubjectTeachers();
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('subjects', ['create', 'index', 'show', 'edit']),
        ...mapActions('teachers', ['getAll']),
        ...mapActions('subjectTeachers', ['update', 'indexSubjectTeacher']),

        searchSubject() {
            this.getSubjects(this.search);
        },
        getSubjects(search) {
            this.index(search).then((result) => {
                if(search != '') {
                    this.dataSubject = result.data;
                }else {
                    this.dataSubject = result;
                }
            });
        },
        filterSubjects(category) {
            return this.dataSubject.filter(function(subject) {
                return subject.group == category;
            });
        },
        // ini buat ambil data semua dr subject-teacher
        // kalau mau pake ini jan lupa di created nya di aktif in lagi
        // getSubjectTeachers() {
        //     this.indexSubjectTeacher().then((result) => {
        //         this.dataSubjectTeacher = result;
        //     });
        // },
        // ini nge filter biar tampil per subject_id
        // filterSubjectTeachers(subjectId) {
        //     return this.dataSubjectTeacher.filter(function(teacher) {
        //         return teacher.subject_id == subjectId;
        //     });
        // },
        showSubject(id) {
            this.show(id).then((result) => {
                this.teacherSubject.name = result.name;
                this.teacherSubject.group = result.group;
                this.teacherSubject.order = result.order;
                this.subjectId = result.id;
                this.modalEdit = true;
            })
        },
        addSubject() {
            this.create(this.subject).then((result) => {
                this.modalAdd = false;
                this.getSubjects();
            })
        },
        getTeachers() {
            this.getAll().then((result) => {
                this.teachers = result;
            })
        },
        editSubject() {
            let payloadSubject = {id: this.subjectId, data: {
                name: this.teacherSubject.name,
                group: this.teacherSubject.group,
                order: this.teacherSubject.order
            }};
            let payloadSubjectTeacher = {subjectId: this.subjectId, data: {
                subject_id: this.subjectId,
                teachers: this.teacherSubject.teachers
            }};
            this.edit(payloadSubject).then(() => {});
            this.update(payloadSubjectTeacher).then(() => {
                this.modalEdit = false;
                this.getSubjects();
            });
        },
        showModalDelete() {
            this.modalEdit = false;
            this.modalDelete = true;
        },
        deleteSubject() {
            console.log('hapus');
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

.card-table {
    width: 100%;
    display: block;
    margin: auto;
}

.r-category {
    padding-left: 4rem;
}

td, .th-middle {
    vertical-align : middle;
}

.form-group {
    margin-bottom: 10px;
}
</style>