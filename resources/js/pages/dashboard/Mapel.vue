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
    <h4 class="text-capitalize mt-3 mb-4 font-weight-bold">mapel dan tim guru</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="input-group mb-3">
                <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Mapel...." @keyup="searchSubject()" v-model="search">
                <div class="input-group-append">
                    <a href="#" class="btn btn-outline-muted btn-lg shadow-sm bg-white" @click="searchSubject()"><i class="fa fa-search"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex">
                <a href="#" class="btn btn-primary btn-block mt-md-1" @click="modalAdd = true">
                    <span class="fas fa-plus"></span> Tambah Mapel
                </a>
                <a href="#" class="btn btn-secondary btn-block mt-md-1" @click="search = ''; getSubjects('')">Refresh Data</a>
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
                    <td v-if="subject.teachers.length >= 1">
                        <a href="#" class="text-dark" @click="showSubject(subject.id)">{{subject.teacher_details_string}}</a>
                        <hr class="hr-teachers">
                        <a href="#" class="text-primary" @click="showSubject(subject.id)">tambah guru</a>
                    </td>
                    <td v-else>
                        <a href="#" class="text-primary" @click="showSubject(subject.id)">pilih guru</a>
                    </td>
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
                <select2 :options="categories" v-model="subject.group" :class="{'is-invalid': errors.group}"></select2>
                <div class="invalid-feedback" v-if="errors.group">
                    {{ errors.group[0] }}
                </div>
            </div>
            <div class="form-group">
                <div class="d-flex flex-column">
                    <label class="mb-1">Nama Mapel</label>
                    <small class="text-capitalize mb-2">pastikan mata pelajaran belum tersedia pada list table mapel</small>
                </div>
                <input type="text" class="form-control" v-model="subject.name" :class="{'is-invalid': errors.name}">
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
                <select2 :options="categories" v-model="teacherSubject.group" :class="{'is-invalid': errors.group}"></select2>
                <div class="invalid-feedback" v-if="errors.group">
                    {{ errors.group[0] }}
                </div>
            </div>
            <div class="form-group">
                <label class="mb-2">Nama Mapel</label>
                <input type="text" class="form-control" v-model="teacherSubject.name" :class="{'is-invalid': errors.name}">
                <div class="invalid-feedback" v-if="errors.name">
                    {{ errors.name[0] }}
                </div>
            </div>
            <div class="form-group">
                <label class="mb-2">Guru Mapel</label>
                <select2 multiple :options="teachers" :reduce="name => name.id" label="name" v-model="teacherSubject.teachers" :class="{'is-invalid': errors.teachers}"></select2>
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
            <p>Yakin akan menghapus mata pelajaran <b class="text-capitalize">{{teacherSubject.name}}</b>?</p>
            <p>Semua data nilai mata pelajaran <b class="text-capitalize">{{teacherSubject.name}}</b> dalam buku nilai akan terhapus.</p>
        </div>
    </modal>
    </div>
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
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('subjects', ['create', 'index', 'detail', 'edit', 'deleteSubjectCascade']),
        ...mapActions('teachers', ['getAll']),
        ...mapActions('subjectTeachers', ['update', 'indexSubjectTeacher']),

        searchSubject() {
            this.getSubjects(this.search);
        },
        getSubjects(search) {
            this.index(search).then((result) => {
                this.dataSubject = result;
            });
        },
        filterSubjects(category) {
            return this.dataSubject.filter(function(subject) {
                return subject.group == category;
            });
        },
        showSubject(id) {
            this.detail(id).then((result) => {
                this.teacherSubject.name = result.name;
                this.teacherSubject.group = result.group;
                this.teacherSubject.order = result.order;
                this.teacherSubject.teachers = result.teachers;
                this.subjectId = result.id;
                this.modalEdit = true;
            })
        },
        addSubject() {
            this.create(this.subject).then((result) => {
                this.modalAdd = false;
                this.subject.name = null;
                this.subject.group = null;
                this.getSubjects('');
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
                this.getSubjects('');
            });
        },
        showModalDelete() {
            this.modalEdit = false;
            this.modalDelete = true;
        },
        deleteSubject() {
            this.deleteSubjectCascade(this.subjectId).then((result) => {
                this.modalDelete = false;
                this.getSubjects('');
            });
        }
    }
}
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

hr.hr-teachers {
    margin: 0.3rem 0 !important;
}

@media (max-width: 575px) {
    h4 {
        font-size: 1rem !important;
    }
    .btn, .input-text, table {
        font-size: 0.8rem !important;
    }
    .btn {
        padding: 0.2rem 0.5rem !important;
    }
}
</style>
