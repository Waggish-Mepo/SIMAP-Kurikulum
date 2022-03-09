<template>
    <div class="mt-2">
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'reportbooks.periods', params: {page: 7} }"><a href="#">Periode Rapor</a></router-link></li>
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'reportbooks.periods.students', params: {page: 7, period: period.id} }" class="router"><a href="#">{{period.title + ' - ' + period.school_year}}</a></router-link></li>
                <li class="breadcrumb-item active" aria-current="page">{{studentGroup.name}}</li>
            </ol>
        </nav>

        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>
        <h5 class="mt-5 title">Absensi Rombel {{studentGroup.name}}</h5>
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
                                <i class="fas fa-expand text-dark"></i>
                            </span>
                        </template>
                        </vue-good-table>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- modal -->
        <modal v-if="modalUpdate" @close="modalUpdate = false" :action="updateAbsence">
            <h5 slot="header">Atur Kehadiran</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Sakit</label>
                    <input type="number" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Izin</label>
                    <input type="number" class="form-control">
                </div>
                <div class="form-group">
                    <label class="mb-2">Alfa</label>
                    <input type="number" class="form-control">
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
export default {
    name: "absences",
    components: {
        "modal": modalComponent
    },
    data() {
        return {
            period: {},
            studentGroup: {},
            modalUpdate: false,
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
                    field: "student.name",
                    filterOptions: { enabled: true },
                },
                {
                    label: "NIS",
                    field: "student.nis",
                    filterOptions: { enabled: true },
                },
                {
                    label: "Sakit",
                    field: "sick",
                    filterOptions: { enabled: true },
                },
                {
                    label: "Izin",
                    field: "absent",
                    filterOptions: { enabled: true },
                },
                {
                    label: "Alfa",
                    field: "alpha",
                    filterOptions: { enabled: true },
                },
                {
                    label: "Edit",
                    field: "id",
                    tdClass: "text-center",
                    sortable: false,
                },
            ],
        }
    },
    created() {
        this.getPeriod(this.$route.params.period);
        this.getStudentGroup(this.$route.params.group);
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('reportPeriods', ['detail']),
        ...mapActions('studentGroups', ['detailStudentGroup']),

        getPeriod(id) {
            this.detail(id).then((result) => {
                this.period = result;
            })
        },
        getStudentGroup(id) {
            this.detailStudentGroup(id).then((result) => {
                this.studentGroup = result;
            })
        },
        updateAbsence() {
            console.log('edit');
        }
    }
}
</script>

<style scoped>
h5.title {
    font-weight: 600 !important;
    font-size: 1.3rem;
    margin-top: 20px;
}

@media (max-width: 575px) {
    h5.title {
        font-size: 1rem;
    }
}
</style>