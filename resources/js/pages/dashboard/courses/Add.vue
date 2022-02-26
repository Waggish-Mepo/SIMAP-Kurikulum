<template>
    <div class="mt-2">
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'courses', params: {page: 5} }"><a href="#">Pelajaran</a></router-link></li>
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'courses.students', params: {page: 5, course: $route.params.course} }" class="router">{{courseName}}</router-link></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah&Edit Siswa Terdaftar</li>
            </ol>
        </nav>

        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>
    </div>
</template>

<script>
// vuex
import {mapActions, mapMutations, mapGetters, mapState} from 'vuex';
export default {
    name: "studentCourseAdd",
    data() {
        return {
            courseName: null
        }
    },
    created() {
        this.getCourse(this.$route.params.course);
        this.getStudentCourses(this.$route.params.course);
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('studentCourses', ['showSelected', 'create']),
        ...mapActions('courses', ['show']),

        getCourse(id) {
            this.show(id).then((result) => {
                this.courseName = result.caption;
            })
        },
        getStudentCourses(id) {
            this.showSelected(id).then((result) => {

            });
        }
    }
}
</script>

<style scoped>

</style>