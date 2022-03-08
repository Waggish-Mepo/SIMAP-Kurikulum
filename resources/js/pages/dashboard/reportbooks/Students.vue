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
        <h5>Rapor per Siswa</h5>
        <div class="row">
            <div class="col-4">
                <h4>Kelas 10</h4>
                <p>Kurikulum 2021</p>
            </div>
            <div class="col-4">
                <h4>Kelas 11</h4>
                <p>Kurikulum 2013</p>
            </div>
            <div class="col-4">
                <h4>Kelas 12</h4>
                <p>Kurikulum 2013</p>
            </div>
        </div>

        <div class="table-responsive mt-3s">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <vue-good-table
                            :columns="columns"
                            :rows="rows"
                            :pagination-options="paginationOpts"
                            :sort-options="sortOpts"
                            :fixed-header="true"
                            max-height="800px"
                            styleClass="vgt-table condensed striped"
                        >
                        <template slot="table-row" slot-scope="props">
                            <span v-if="props.column.field === 'id'">
                                <router-link v-bind:to="{ name: 'reportbooks.periods.students.report', params: {page: 7, period: period.id, student: props.row.id} }" class="router">
                                <i class="fas fa-expand text-dark"></i>
                                </router-link>
                            </span>
                        </template>
                        </vue-good-table>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
export default {
    name: "allStudents",
    data() {
        return {
            period: {},
            studentGroup: '',
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
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('reportPeriods', ['detail']),
        ...mapActions('students', ['indexWithSG']),

        getPeriod(id) {
            this.detail(id).then((result) => {
                this.period = result;
            })
        },
        getStudents() {
            this.indexWithSG(this.studentGroup).then((result) => {
                this.rows = result;
            })
        }
    }
}
</script>

<style scoped>
h5 {
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

@media (max-width: 575px) {
    h5 {
        font-size: 1rem;
    }
    h4 {
        font-size: 0.9rem;
    }
}
</style>