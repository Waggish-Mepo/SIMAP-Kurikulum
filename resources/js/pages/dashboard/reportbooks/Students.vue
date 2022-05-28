<template>
    <div class="mt-2">
        <div class="loader" v-if="isLoading"></div>
        <div v-if="isLoading" class="w-100 card-loading">
            <img src="/assets/img/loading.png" alt="loading" class="d-block m-auto">
        </div>
        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>

        <div v-if="!isLoading">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'reportbooks.periods', params: {page: 7} }"><a href="#">Periode Rapor</a></router-link></li>
                <li class="breadcrumb-item active" aria-current="page">{{period.title + ' - ' + period.school_year}}</li>
            </ol>
        </nav>

        <h5 class="title">Rapor per Siswa</h5>
        <!-- <div class="row">
            <div class="col-3">
                <h4>Kelas 10</h4>
                <p>Kurikulum 2021</p>
            </div>
            <div class="col-3">
                <h4>Kelas 11</h4>
                <p>Kurikulum 2013</p>
            </div>
            <div class="col-3">
                <h4>Kelas 12</h4>
                <p>Kurikulum 2013</p>
            </div>
            <div class="col-3">
                <button class="btn btn-outline-blue" @click="modalUpdate = true"><span class="fas fa-pen"></span>Edit Template Rapor</button>
            </div>
        </div> -->

        <div class="d-flex flex-wrap justify-content-between mt-4">
            <div>
                <router-link v-if="this.role === 'ADMIN'" v-bind:to="{ name: 'reportbooks.periods.attitude.components', params: {page: 7, period: $route.params.period} }"><a href="#" class="btn bg-blue1 text-white text-capitalize">atur komponen nilai sikap</a></router-link>
            </div>
            <select v-if="this.role === 'ADMIN'" class="form-select btn bg-blue1 text-white w-auto" @change="redirectPage">
                <option selected hidden>Atur Kehadiran Siswa</option>
                <option v-for="(group,index) in studentGroups" :key="index" :value="group.id">{{group.name}}</option>
            </select>
            <router-link class="btn bg-blue1 text-white" v-if="this.role === 'TEACHER'" v-bind:to="{name: 'reportbooks.periods.students.absence', params: {page: 7, period: $route.params.period, group: 0, region: regionId}}">
                Atur Kehadiran Siswa
            </router-link>
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
                            @on-column-filter="onColumnFilter"
                            :sort-options="sortOpts"
                            :fixed-header="true"
                            :line-numbers="true"
                            max-height="800px"
                            styleClass="vgt-table condensed striped"
                        >
                        <template slot="table-row" slot-scope="props">
                            <span v-if="props.column.field === 'id'">
                                <i class="fas fa-expand text-dark cursor-pointer" @click="checkStudentExist(props.row.id)"></i>
                            </span>
                        </template>
                        </vue-good-table>
                        <pagination v-if="!withoutPagination" class="mt-3" :pagination="pages" @paginate="getStudents" :offset="2" :data="payloadGet"></pagination>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- modal -->
        <!-- <modal v-if="modalUpdate" @close="modalUpdate = false" :action="updateTemplate">
            <h5 slot="header">Ubah Template Rapor</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2"><b>Kelas 10</b></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="10-k13" value="k13">
                        <label class="form-check-label" for="10-k13">
                            Kurikulum 2013
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="10-k21" value="k21">
                        <label class="form-check-label" for="10-k21">
                            Kurikulum Sekolah Penggerak
                        </label>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2"><b>Kelas 11</b></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="11-k13" value="k13">
                        <label class="form-check-label" for="11-k13">
                            Kurikulum 2013
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="11-k21" value="k21">
                        <label class="form-check-label" for="11-k21">
                            Kurikulum Sekolah Penggerak
                        </label>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2"><b>Kelas 12</b></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="12-k13" value="k13">
                        <label class="form-check-label" for="12-k13">
                            Kurikulum 2013
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="12-k21" value="k21">
                        <label class="form-check-label" for="12-k21">
                            Kurikulum Sekolah Penggerak
                        </label>
                    </div>
                </div>
            </div>
        </modal> -->
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
export default {
    name: "allStudents",
    components: {
        "modal": modalComponent,
        "pagination": paginateComponent
    },
    data() {
        return {
            period: {},
            studentGroups: [],
            // modalUpdate: false,
            sortOpts: { enabled: true },
            rows: [],
            columns: [
                {
                    label: "Nama",
                    field: "name",
                    filterOptions: { enabled: true },
                },
                {
                    label: "NIS",
                    field: "nis",
                    filterOptions: { enabled: true },
                },
                {
                    label: "Rombel",
                    field: "student_group.name",
                    filterOptions: { enabled: true },
                },
                {
                    label: "Lihat Rapor",
                    field: "id",
                    tdClass: "text-center",
                    sortable: false,
                },
            ],
            teacherIds: [],
            role: null,
            regionId: null,
            pages: {
                total: 0,
                per_page: 20,
                from: 1,
                to: 0,
                current_page: 1,
                last_page: 1,
            },
            payloadGet: {
                region: null,
                search: '',
                searchVal: '',
                page: 1,
                per_page: 20,
                field:"student_group_id",
                sort:"ASC"
            },
            withoutPagination: false
        }
    },
    created() {
        this.getPeriod(this.$route.params.period);
        this.getStudents();
        this.getStudentGroups();
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('reportPeriods', ['detail']),
        ...mapActions('students', ['indexWithSG', 'filterByRegion']),
        ...mapActions('studentGroups', ['getAll']),
        ...mapActions('reportbooks', ['createOne', 'checkStudent', 'edit']),
        ...mapActions('regions', ['regionIdByTeacher']),

        getPeriod(id) {
            this.detail(id).then((result) => {
                this.period = result;
            })
        },
        getStudents() {
            let user = JSON.parse(localStorage.getItem('user_data'));
            let id = user.userable_id;
            this.role = user.role;
            if (this.role === 'ADMIN') {
                this.indexWithSG(this.payloadGet).then((result) => {
                    this.rows = [];
                    if (result.per_page) {
                        this.rows = result.data;
                        this.pages.total = result.total;
                        this.pages.per_page = result.per_page;
                        this.pages.from = result.from;
                        this.pages.to = result.to;
                        this.pages.current_page = result.current_page;
                        this.pages.last_page = result.last_page;
                    } else {
                        if (result.data.length > 1) {
                            for (let i = 0; i < result.data.length; i++) {
                                if (i == result.data.length-1) {
                                    this.rows.push(result.data[i][0]);
                                } else {
                                    this.rows.push(result.data[i]);
                                }
                            }   
                        } else {
                            this.rows = result.data;
                        }
                    }
                });
            } else if (this.role === 'TEACHER') {
                this.regionIdByTeacher(id).then((result) => {
                    this.regionId = result[0].id;
                    this.payloadGet.region = result[0].id;
                    this.payloadGet.field = 'nis';
                    this.filterByRegion(this.payloadGet).then((result) => {
                        this.rows = [];
                        if (result.per_page) {
                            this.rows = result.data;
                            this.pages.total = result.total;
                            this.pages.per_page = result.per_page;
                            this.pages.from = result.from;
                            this.pages.to = result.to;
                            this.pages.current_page = result.current_page;
                            this.pages.last_page = result.last_page;
                        } else {
                            if (result.data.length > 1) {
                                for (let i = 0; i < result.data.length; i++) {
                                    if (i == result.data.length-1) {
                                        this.rows.push(result.data[i][0]);
                                    } else {
                                        this.rows.push(result.data[i]);
                                    }
                                }   
                            } else {
                                this.rows = result.data;
                            }
                        }
                    })
                })
            }
        },
        updateParams(newProps) {
            this.pages = Object.assign({}, this.pages, newProps);
        },
        onColumnFilter(params) {
            this.updateParams(params);
            if(!this.pages.columnFilters["name"] && !this.pages.columnFilters["nis"] && !this.pages.columnFilters["student_group.name"]){
                this.payloadGet.search = "";
                this.payloadGet.searchVal = "";
                this.withoutPagination = false;
                this.getStudents();
            }
            else if(this.pages.columnFilters["name"]){
                this.payloadGet.search = "name";
                this.payloadGet.searchVal = this.pages.columnFilters["name"];
                this.withoutPagination = false;
                this.getStudents();
            }
            else if(this.pages.columnFilters["nis"]){
                this.payloadGet.search = "nis";
                this.payloadGet.searchVal = this.pages.columnFilters["nis"];
                this.withoutPagination = false;
                this.getStudents();
            }
            else if(this.pages.columnFilters["student_group.name"]){
                this.payloadGet.search = "student_group";
                this.payloadGet.searchVal = this.pages.columnFilters["student_group.name"];
                this.withoutPagination = true;
                this.getStudents();
            }
        },
        onSortChange(params) {
            if(params[0].type == "none"){
                this.payloadGet.field = "student_group_id";
                this.payloadGet.sort = "ASC";
            } else if(params[0].field == "student_group.name") {
                this.payloadGet.field = "student_group_id";
                this.payloadGet.sort = params[0].type;
            } else{
                this.payloadGet.field = params[0].field;
                this.payloadGet.sort = params[0].type;
            }
            this.getStudents();
        },
        getStudentGroups() {
            this.getAll().then((result) => {
                this.studentGroups = result;
            })
        },
        redirectPage(e) {
            let studentGroup = e.target.value;

            this.$router.push({name: 'reportbooks.periods.students.absence', params: {page: 7, period: this.$route.params.period, group: studentGroup, region: 0}});
        },
        checkStudentExist(student) {
            this.checkStudent(student).then((result) => {
                if(result.length < 1) {
                    let payload = {reportPeriodId: this.period.id, studentId: student };
                    this.createOne(payload).then((result) => {
                        this.$router.push({name: 'reportbooks.periods.students.report', params: {page: 7, period: this.$route.params.period, student: student}});
                    })
                } else {
                    let payloadEdit = {id: result[0].id, data: {}};
                    this.edit(payloadEdit).then((result) => {
                        this.$router.push({name: 'reportbooks.periods.students.report', params: {page: 7, period: this.$route.params.period, student: student}});
                    })
                }
            })
        }
        // updateTemplate() {
        //     console.log('edit');
        // }
    }
}
</script>

<style scoped>
h5.title {
    font-weight: 600 !important;
    font-size: 1.3rem;
    margin-top: 20px;
}

.row {
    margin-top: 30px;
}

h4 {
    font-weight: 600;
    font-size: 1rem;
}

a.router {
    text-decoration: none !important;
    color: #000 !important;
}

.w-20 {
    width: 20%;
}

.btn.bg-blue1 {
    margin-right: 1rem !important;
}

.btn-outline-blue {
    border: 1px solid #182A36;
    color: #182A36 !important;
}

span.fas.fa-pen {
    margin-right: 5px;
}

.cursor-pointer {
    cursor: pointer;
}

@media (max-width: 575px) {
    h5.title {
        font-size: 1rem;
    }
    h4 {
        font-size: 0.9rem;
    }
}
</style>