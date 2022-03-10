<template>
    <div class="mt-2">
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'reportbooks.periods', params: {page: 7} }"><a href="#">Periode Rapor</a></router-link></li>
                <li class="breadcrumb-item active" aria-current="page">{{period.title + ' - ' + period.school_year}}</li>
            </ol>
        </nav>

        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>
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

        <div class="d-flex justify-content-between mt-4">
            <div></div>
            <select class="form-select btn bg-blue1 text-white w-20" @change="redirectPage">
                <option selected hidden>Atur Kehadiran Siswa</option>
                <option v-for="(group,index) in studentGroups" :key="index" :value="group.id">{{group.name}}</option>
            </select>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <vue-good-table
                            :columns="columns"
                            :rows="rows"
                            :pagination-options="paginationOpts"
                            :sort-options="sortOpts"
                            :fixed-header="true"
                            :line-numbers="true"
                            max-height="800px"
                            styleClass="vgt-table condensed striped"
                        >
                        <template slot="table-row" slot-scope="props">
                            <span v-if="props.column.field === 'id'">
                                <i class="fas fa-expand text-dark" @click="checkStudentExist(props.row.id)"></i>
                            </span>
                        </template>
                        </vue-good-table>
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
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
// modal
import modalComponent from '../../../components/Modal.vue';
export default {
    name: "allStudents",
    components: {
        "modal": modalComponent
    },
    data() {
        return {
            period: {},
            studentGroups: [],
            // modalUpdate: false,
            sortOpts: { enabled: true },
            paginationOpts: {
                enabled: true,
                mode: "records",
                perPage: 40,
                position: "bottom",
                perPageDropdown: [10, 50, 100],
                dropdownAllowAll: true,
                setCurrentPage: 1,
                nextLabel: "Next",
                prevLabel: "Prev",
                rowsPerPageLabel: "Rows per page",
                ofLabel: "of",
                pageLabel: "Page", // for 'pages' mode
                allLabel: "All",
            },
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
        ...mapActions('students', ['indexWithSG']),
        ...mapActions('studentGroups', ['getAll']),
        ...mapActions('reportbooks', ['createOne', 'checkStudent', 'edit']),

        getPeriod(id) {
            this.detail(id).then((result) => {
                this.period = result;
            })
        },
        getStudents() {
            this.indexWithSG().then((result) => {
                this.rows = result;
            })
        },
        getStudentGroups() {
            this.getAll().then((result) => {
                this.studentGroups = result;
            })
        },
        redirectPage(e) {
            let studentGroup = e.target.value;

            this.$router.push({name: 'reportbooks.periods.students.absence', params: {page: 7, period: this.$route.params.period, group: studentGroup}});
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

@media (max-width: 575px) {
    h5.title {
        font-size: 1rem;
    }
    h4 {
        font-size: 0.9rem;
    }
}
</style>