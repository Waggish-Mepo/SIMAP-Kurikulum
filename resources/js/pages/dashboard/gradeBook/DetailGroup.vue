<template>
    <div style="margin-top: 70px">
        <div class="loader" v-if="isLoading"></div>
        <nav aria-label="breadcrumb" class="nav-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'gradebooks.period', params: {page: 6} }"><a href="#">Buku Nilai</a></router-link></li>
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'gradebooks.course', params: {page: 6, period: period.id} }" class="router">Buku Nilai {{period.title}}</router-link></li>
                <li class="breadcrumb-item"><router-link v-bind:to="{ name: 'gradebooks.course.detail', params: {page: 6, period: period.id, course: course.id, gb: $route.params.gb} }" class="router">Rapor {{period.title}} - {{course.caption}} Kelas {{course.entry_year_with_class}}</router-link></li>
                <li class="breadcrumb-item active" aria-current="page">Rapor Siswa</li>
            </ol>
        </nav>

        <div class="alert alert-danger my-3" v-if="errorMessage">
            {{ errorMessage }}
        </div>

        <h5 class="text-capitalize">rapor siswa (pengetahuan dan keterampilan)</h5>
        <div class="d-flex flex-column mt-3 text-capitalize">
            <b>Keterangan</b>
            <span><b>PH = </b> penilaian harian;  <b class="ml-2">P = </b> pengetahuan; <b class="ml-2">K = </b> keterampilan; <b class="ml-2">teks bercetak merah = </b> remedial </span>
        </div>

        <div class="row mt-4 d-flex justify-content-center">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{studentGroup.name}}</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" v-if="course.curriculum !== 'K21 | Sekolah Penggerak'">
                                <thead class="text-capitalize text-center bg-muted">
                                    <tr>
                                        <th rowspan="2" style="vertical-align : middle;">no</th>
                                        <th rowspan="2" style="vertical-align : middle;">nama siswa</th>
                                        <th rowspan="2" style="vertical-align : middle;">NIS</th>
                                        <th rowspan="2" style="vertical-align : middle;">predikat</th>
                                        <th rowspan="2" style="vertical-align : middle;">nilai akhir</th>
                                        <th colspan="2" style="vertical-align : middle;">nilai rapot</th>
                                        <th v-for="(component, index) in components" :key="index" colspan="2" style="vertical-align : middle;">{{component.abbreviation}}</th>
                                    </tr>
                                    <tr>
                                        <th>p</th>
                                        <th>k</th>
                                        <th v-for="index in componentTotal" :key="index">{{index | scoreType}}</th>
                                    </tr>
                                </thead>
                                <tbody class="text-capitalize" style="border-top: none !important;">
                                    <tr v-for="(sc, i) in scorecards" :key="i">
                                        <td>{{i+1}}</td>
                                        <td>{{sc.student.name}}</td>
                                        <!-- <td>levi ackerman
                                            <br>
                                            <span class="text-secondary">siswa ini sudah tidak terdaftar pada mata pelajaran ini</span>
                                        </td> -->
                                        <td>{{sc.student.nis}}</td>
                                        <td v-if="sc.predicate_letter">{{sc.predicate_letter.letter}}</td>
                                        <td v-if="!sc.predicate_letter">-</td>
                                        <td>
                                            <span class="text-danger" v-if="sc.final_score < gradebookData.scorebar && sc.final_score !== null">
                                                {{sc.final_score | scoreCheck}}
                                            </span>
                                            <span v-else>{{sc.final_score | scoreCheck}}</span>
                                        </td>
                                        <td>{{sc.knowledge_score | scoreCheck}}</td>
                                        <td>{{sc.skill_score | scoreCheck}}</td>
                                        <td v-for="index in componentTotal" :key="index" class="cursor-pointer" @click="showModalUpdate(sc.student.name, index, sc.id)">
                                            <span v-if="index === 1">
                                                <span v-if="sc.scorecard_components[0]['knowledge_score'] < gradebookData.scorebar && sc.scorecard_components[0]['knowledge_score'] !== null" class="text-danger">
                                                    {{sc.scorecard_components[0]['knowledge_score'] | scoreCheck}}
                                                </span>
                                                <span v-else>
                                                    {{sc.scorecard_components[0]['knowledge_score'] | scoreCheck}}
                                                </span>
                                            </span>
                                            <span v-else-if="index % 2 === 0">
                                                <span v-if="sc.scorecard_components[index/2-1]['skill_score'] < gradebookData.scorebar && sc.scorecard_components[index/2-1]['skill_score'] !== null" class="text-danger">
                                                    {{sc.scorecard_components[index/2-1]['skill_score'] | scoreCheck}}
                                                </span>
                                                <span v-else>
                                                    {{sc.scorecard_components[index/2-1]['skill_score'] | scoreCheck}}
                                                </span>
                                            </span>
                                            <span v-else-if="index % 2 !== 0">
                                                <span v-if="sc.scorecard_components[(index-1)/2]['knowledge_score'] < gradebookData.scorebar && sc.scorecard_components[(index-1)/2]['knowledge_score'] !== null" class="text-danger">
                                                    {{sc.scorecard_components[(index-1)/2]['knowledge_score'] | scoreCheck}}
                                                </span>
                                                <span v-else>
                                                    {{sc.scorecard_components[(index-1)/2]['knowledge_score'] | scoreCheck}}
                                                </span>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered table-hover" v-if="course.curriculum === 'K21 | Sekolah Penggerak'">
                                <thead class="text-capitalize text-center bg-muted">
                                    <tr>
                                        <th>no</th>
                                        <th>nama siswa</th>
                                        <th>NIS</th>
                                        <th>predikat</th>
                                        <th>nilai akhir</th>
                                        <th v-for="(component, index) in components" :key="index">{{component.abbreviation}}</th>
                                    </tr>
                                </thead>
                                <tbody class="text-capitalize" style="border-top: none !important;">
                                    <tr v-for="(sc, i) in scorecards" :key="i">
                                        <td>{{i+1}}</td>
                                        <td>{{sc.student.name}}</td>
                                        <!-- <td>levi ackerman
                                            <br>
                                            <span class="text-secondary">siswa ini sudah tidak terdaftar pada mata pelajaran ini</span>
                                        </td> -->
                                        <td>{{sc.student.nis}}</td>
                                        <td v-if="sc.predicate_letter">{{sc.predicate_letter.letter}}</td>
                                        <td v-if="!sc.predicate_letter">-</td>
                                        <td>
                                            <span class="text-danger" v-if="sc.final_score < gradebookData.scorebar && sc.final_score !== null">
                                                {{sc.final_score | scoreCheck}}
                                            </span>
                                            <span v-else>{{sc.final_score | scoreCheck}}</span>
                                        </td>
                                        <td v-for="(scComponent, index) in sc.scorecard_components" :key="index" class="cursor-pointer" @click="showModalUpdateGeneral(sc.student.name, sc.id, scComponent.id, scComponent.title)">
                                            <span v-if="scComponent.general_score < gradebookData.scorebar && scComponent.general_score !== null" class="text-danger">
                                                {{scComponent.general_score | scoreCheck}}
                                            </span>
                                            <span v-else>
                                                {{scComponent.general_score | scoreCheck}}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal -->
        <modal v-if="modalScore" @close="modalScore = false" :action="updateScore">
            <div slot="header">
                <h5 class="text-capitalize"><b>{{studentName}}</b></h5>
                <span class="text-capitalize">{{componentTitle}}</span>
            </div>
            <div slot="body" class="mt-4">
                <div class="alert alert-danger mb-3" v-if="errorMessage">
                    {{ errorMessage }}
                </div>
                <div class="form-group">
                    <label class="mb-2">Nilai {{scoreType}}</label>
                    <input type="number" placeholder="Contoh: 90" class="form-control" v-model="score">
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
import { mapActions, mapMutations, mapGetters, mapState } from "vuex";
// modal
import modalComponent from '../../../components/Modal.vue';
export default {
    name: "scorecardStudents",
    components: {
        "modal": modalComponent
    },
    data() {
        return {
            period: {},
            course: {},
            studentGroup: {},
            gradebookData: {},
            modalScore: false,
            scorecards: [],
            components: [],
            componentTotal: 0,
            studentName: null,
            componentTitle: null,
            scoreType: null,
            score: null,
            scorecardId: null,
            scorecardComponentId: null,
            scores: {
                knowledge_score: null,
                skill_score: null,
                general_score: null
            }
        }
    },
    created() {
        this.getPeriod(this.$route.params.period);
        this.getCourse(this.$route.params.course);
        this.getStudentGroup(this.$route.params.sg);
        this.getGradebook(this.$route.params.gb);
    },
    watch: {
        '$route.params.sg': {
            handler: function() {
                this.getScoreCards();
                this.getStudentGroup(this.$route.params.sg);
            },
            deep: true,
            immediate: true
        }
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('reportPeriods', ['detail']),
        ...mapActions('courses', ['show']),
        ...mapActions('studentGroups', ['detailStudentGroup']),
        ...mapActions('gradebooks', ['gradebook']),
        ...mapActions('scorecards', ['index', 'detailScorecard']),
        ...mapActions('scorecardComponents', ['scorecardComponent', 'edit']),

        getPeriod(id) {
            this.detail(id).then((result) => {
                this.period = result;
            })
        },
        getCourse(id) {
            this.show(id).then((result) => {
                this.course = result;
                this.getScoreCards();
            })
        },
        getStudentGroup(id) {
            this.detailStudentGroup(id).then((result) => {
                this.studentGroup = result;
            })
        },
        getGradebook(id) {
            this.gradebook(id).then((result) => {
                this.gradebookData = result;
            })
        },
        getScoreCards() {
            let payload = {gb: this.$route.params.gb, sg: this.$route.params.sg}
            this.index(payload).then((result) => {
                this.scorecards = result;
                this.components = result[0]['scorecard_components'];
                this.componentTotal = result[0]['scorecard_components'].length * 2;
            })
        },
        showModalUpdate(name, index, scorecard) {
            this.studentName = name;
            this.scorecardId = scorecard;
            this.detailScorecard(scorecard).then((result) => {
                if(index === 1) {
                    this.componentTitle = this.components[0]['title'];
                    this.scoreType = 'Pengetahuan';
                    this.scorecardComponentId = result.scorecard_components[0]['id'];
                    this.scores.skill_score = result.scorecard_components[0]['skill_score'];
                    this.scores.general_score = result.scorecard_components[0]['general_score'];
                    if(result.scorecard_components[0]['knowledge_score']) {
                        this.score = result.scorecard_components[0]['knowledge_score'];
                    } else {
                        this.score = 0;
                    }
                } else if(index % 2 === 0) {
                    this.componentTitle = this.components[index/2-1]['title'];
                    this.scoreType = 'Keterampilan';
                    this.scorecardComponentId = result.scorecard_components[index/2-1]['id'];
                    this.scores.knowledge_score = result.scorecard_components[index/2-1]['knowledge_score'];
                    this.scores.general_score = result.scorecard_components[index/2-1]['general_score'];
                    if(result.scorecard_components[index/2-1]['skill_score']) {
                        this.score = result.scorecard_components[index/2-1]['skill_score'];
                    } else {
                        this.score = 0;
                    }
                } else if(index % 2 !== 0) {
                    this.componentTitle = this.components[(index-1)/2]['title'];
                    this.scoreType = 'Pengetahuan';
                    this.scorecardComponentId = result.scorecard_components[(index-1)/2]['id'];
                    this.scores.skill_score = result.scorecard_components[(index-1)/2]['skill_score'];
                    this.scores.general_score = result.scorecard_components[(index-1)/2]['general_score'];
                    if(result.scorecard_components[(index-1)/2]['knowledge_score']) {
                        this.score = result.scorecard_components[(index-1)/2]['knowledge_score'];
                    } else {
                        this.score = 0;
                    }
                }
                this.modalScore = true;
            });
        },
        showModalUpdateGeneral(name, scorecardId, scComponentId, title) {
            this.studentName = name;
            this.scoreType = 'Umum';
            this.scorecardId = scorecardId;
            this.componentTitle = title;
            this.scorecardComponent(scComponentId).then((result) => {
                this.scorecardComponentId = result.id;
                this.scores.knowledge_score = result.knowledge_score;
                this.scores.skill_score = result.skill_score;
                if (result.general_score) {
                    this.score = result.general_score;
                } else {
                    this.score = 0;
                }
                this.modalScore = true;
            })
        },
        updateScore() {
            let knowledge, skill, general;
            if (this.scoreType === 'Pengetahuan') {
                skill = this.scores.skill_score;
                knowledge = this.score;
                general = this.scores.general_score;
            } else if (this.scoreType === 'Keterampilan') {
                skill = this.score;
                knowledge = this.scores.knowledge_score;
                general = this.scores.general_score;
            } else if (this.scoreType === 'Umum') {
                skill = this.scores.skill_score;
                knowledge = this.scores.knowledge_score;
                general = this.score;
            }
            let payload = {periodId: this.$route.params.period,
                data: {
                    gradebook_id: this.$route.params.gb,
                    scorecard_id: this.scorecardId,
                    scorecard_component_id: this.scorecardComponentId,
                    knowledge_score: knowledge,
                    skill_score: skill,
                    general_score: general,
                }
            }
            this.edit(payload).then((result) => {
                this.modalScore = false;
                this.getScoreCards();
            })
        }
    }
}
</script>

<style scoped>
.nav-breadcrumb {
    margin-bottom: 20px;
}

.breadcrumb-item {
    font-size: 1rem;
}

h5 {
    font-weight: 600;
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    background-color: #fff;
    border: 1px solid #d2d2dc;
    border-radius: 0;
    max-width: 890px;
    margin: auto;
}

.card .card-title {
    color: #000;
    margin-bottom: 10px;
    text-transform: capitalize;
    font-size: 1rem;
    font-weight: 600;
}

.bg-muted {
    background-color: #f3f3f3;
}

.bg-muted2 {
    background-color: #dadada;
}

.table-bordered {
    border-color: #333 !important;
}

.cursor-pointer {
    cursor: pointer;
}

@media(max-width:1070px) {
    .card {
        max-width: 1070px;
        width: 100%;
    }
}

@media (max-width: 575px) {
    h5 {
        font-size: 0.9rem !important;
    }
    b, span {
        font-size: 0.7rem !important;
    }
    table {
        font-size: 0.8rem !important;
    }
}
</style>
