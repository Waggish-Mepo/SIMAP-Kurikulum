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

        <div>
            <div class="mb-4" v-for="(subject, index) in courses" :key="index">
                <h5 class="text-capitalize">{{subject.name}}</h5>
                <div v-if="subject.data.length > 0">
                    <div class="card w-100 p-3 mb-2" v-for="(course, index) in subject.data" :key="index">
                        <div class="d-flex align-items-center text-capitalize">
                            <span class="fas fa-book"></span>
                            {{period.title}} {{period.school_year}} - {{course.caption}} Kelas {{course.entry_year_with_class}}
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p class="text-capitalize">data pelajaran terkait tidak tersedia</p>
                </div>
                <hr>
            </div>
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
            courses: [],
            payload: {
                search: '',
                period: this.$route.params.period
            },
        }
    },
    created() {
        this.showPeriod(this.$route.params.period);
        this.getCourses(this.payload);
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('courses', ['index']),
        ...mapActions('reportPeriods', ['show']),

        showPeriod(id) {
            this.show(id).then((result) => {
                this.period = result;
            })
        },
        getCourses(payload) {
            this.index(payload).then((result) => {
                this.courses = result;
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