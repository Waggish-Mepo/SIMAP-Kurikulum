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
                    <div class="card w-100 p-3 mb-2" v-for="(course, index) in subject.data" :key="index" @click="checkGradebook(course.id)">
                        <!-- <router-link v-bind:to="{ name: 'gradebooks.course.detail', params: {page: 6, period: period.id, course: course.id, gb: 1} }" class="router"> -->
                        <div class="d-flex align-items-center text-capitalize">
                            <span class="fas fa-book"></span>
                            {{period.title}} {{period.school_year}} - {{course.caption}} Kelas {{course.entry_year_with_class}}
                        </div>
                        <!-- </router-link> -->
                    </div>
                </div>
                <div v-else>
                    <p class="text-capitalize">data pelajaran terkait tidak tersedia</p>
                </div>
                <hr>
            </div>
        </div>

        <!-- modal -->
        <modal v-if="modalAdd" @close="modalAdd = false" :action="addGradebook">
            <h5 slot="header">Buat Buku Nilai</h5>
            <div slot="body">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="row mb-3" v-if="K13">
                    <b class="mb-4">Total bobot nilai pengetahuan dan keterampilan harus 100%</b>
                    <div class="form-group col-sm-6">
                        <label class="mb-2">Bobot Pengetahuan</label>
                        <div class="input-group">
                            <input type="number" class="form-control" v-model="knowledge" :class="{'is-invalid': errors.weights}">
                            <div class="input-group-prepend">
                            <div class="input-group-text">%</div>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="errors.weights">
                            {{ errors.weights[0] }}
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="mb-2">Bobot Keterampilan</label>
                        <div class="input-group">
                            <input type="number" class="form-control" v-model="skill" :class="{'is-invalid': errors.weights}">
                            <div class="input-group-prepend">
                            <div class="input-group-text">%</div>
                            </div>
                        </div>
                        <div class="invalid-feedback" v-if="errors.weights">
                            {{ errors.weights[0] }}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="mb-2">Skor Ketuntasan Minimal</label>
                    <input type="number" class="form-control" min="1" max="100" v-model="gradebook.scorebar" :class="{'is-invalid': errors.scorebar}">
                    <div class="invalid-feedback" v-if="errors.scorebar">
                        {{ errors.scorebar[0] }}
                    </div>
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
    name: "periodCourse",
    components: {
        "modal": modalComponent
    },
    data() {
        return {
            period: {},
            courses: [],
            payload: {
                search: ''
            },
            modalAdd: false,
            knowledge: 1,
            skill: 1,
            general: 1,
            K13: false,
            gradebook: {
                title: null,
                report_period_id: this.$route.params.period,
                course_id: null,
                components: [],
                scorebar: null,
                weights: {}
            },
            redirect: null
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
        ...mapActions('courses', ['index', 'show']),
        ...mapActions('reportPeriods', ['detail']),
        ...mapActions('gradebooks', ['checkPeriodCourse', 'create', 'getGradebook']),

        showPeriod(id) {
            this.detail(id).then((result) => {
                this.period = result;
            })
        },
        getCourses(payload) {
            this.index(payload).then((result) => {
                this.courses = result;
            })
        },
        checkGradebook(courseId, periodId) {
            let payload = {report_period: this.$route.params.period, course: courseId};
            this.checkPeriodCourse(payload).then((result) => {
                if (result.data.length < 1) {
                    this.show(courseId).then((value) => {
                        this.gradebook.course_id = courseId;
                        this.gradebook.title = this.period.title + ' ' + this.period.school_year + ' - ' + value.caption + ' Kelas ' + value.entry_year_with_class;
                        if (value.curriculum == "K21 | Sekolah Penggerak") {
                            this.gradebook.components.push('GENERAL');
                        } else {
                            this.gradebook.components.push('KNOWLEDGE', 'SKILL');
                            this.K13 = true;
                        }
                        this.modalAdd = true;
                    });
                } else {
                    let payload = {report_period_id: this.period.id, course_id: courseId};
                    this.getGradebook(payload).then((val) => {
                        this.$router.push({ name: 'gradebooks.course.detail', params: {page: 6, period: this.period.id, course: val.data[0].course_id, gb: val.data[0].id} });
                    });
                }
            })
        },
        addGradebook() {
            if (this.gradebook.components.includes('GENERAL')) {
                let weights = {
                    "general": this.general
                };
                this.gradebook.weights = weights;
            } else {
                let weights = {
                    "knowledge": this.knowledge,
                    "skill": this.skill
                };
                this.gradebook.weights = weights;
            }
            this.create(this.gradebook).then((result) => {
                this.$router.push({ name: 'gradebooks.course.detail', params: {page: 6, period: result.report_period_id, course: result.course_id, gb: result.id} });
            });
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

.card {
    cursor: pointer;
}
</style>