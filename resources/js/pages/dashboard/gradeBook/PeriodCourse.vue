<template>
    <div class="mt-2">
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'gradebooks.period', params: {page: 6} }"><a href="#">Buku Nilai Periode</a></router-link></li>
                <li class="breadcrumb-item active" aria-current="page">{{period.title}} {{period.school_year}}</li>
            </ol>
        </nav>

        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>

        <div v-if="subjects.length > 1">
            <div class="mb-4" v-for="(subject, index) in subjects" :key="index">
                <h5 class="text-capitalize">{{subject.name}}</h5>
                <div class="card w-100 p-3 mb-2" v-for="(course, index) in filterCourses(subject.id)" :key="index">
                    <div class="d-flex align-items-center text-capitalize">
                        <span class="fas fa-book"></span>
                        {{period.title}} {{period.school_year}} - {{course.caption}} Kelas {{course.entry_year_with_class}}
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <!-- data null -->
        <div v-else class="w-100 card-not-found">
            <img src="/assets/img/sad.png" alt="not found" class="d-block img m-auto">
            <h5 class="text-center text-capitalize mt-4">data terkait tidak ditemukan</h5>
        </div>
    </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
export default {
    name: "periodCourse",
    data() {
        return {
            period: {},
            subjects: [],
            courses: []
        }
    },
    created() {
        this.showPeriod(this.$route.params.period);
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('subjects', ['getAll']),
        ...mapActions('courses', ['byEntryYears']),
        ...mapActions('reportPeriods', ['show']),

        showPeriod(id) {
            this.show(id).then((result) => {
                this.period = result;
                this.getCourses(result.id);
            })
        },
        getSubjects() {
            this.getAll().then((result) => {
                this.subjects = result;
            })
        },
        filterCourses(subject) {
            return this.courses.filter(function(course) {
                return course.subject_id == subject;
            });
        },
        getCourses(year) {
            this.byEntryYears(year).then((result) => {
                this.courses = result;
                if(result.length < 1) {
                    this.subjects = [];
                } else {
                    this.getSubjects();
                }
            })
        }
    }
}
</script>

<style scoped>
nav {
    margin-bottom: 2rem;
}

h5 {
    font-weight: 600;
}

span.fas.fa-book {
    margin-right: 10px;
}
</style>