<template>
    <nav id="sidebar">
        <div class="loader" v-if="isLoading"></div>
        <div class="sidebar-header">
            <p>{{period.title}} {{period.school_year}} - {{course.caption}} Kelas {{course.entry_year_with_class}}</p>
            <hr>
        </div>
        <ul class="list-unstyled components">
            <li> <a href="#">Users</a> </li>
        </ul>
    </nav>
</template>

<script>
import { mapActions, mapMutations, mapGetters, mapState } from "vuex";
export default {
    name: "sidebarGradebook",
    data() {
        return {
            course: {},
            period: {},
            studentGroups: []
        }
    },
    created() {
        this.getPeriod(this.$route.params.period);
        this.getCourse(this.$route.params.course);
        this.getStudentGroup(this.$route.params.course);
    },
    computed: {
        ...mapState(['errorMessage', 'errors', 'isLoading']),
    },
    methods: {
        ...mapActions('reportPeriods', ['detail']),
        ...mapActions('courses', ['show']),
        ...mapActions('studentGroups', ['getByCourse']),

        getPeriod(id) {
            this.detail(id).then((result) => {
                this.period = result;
            })
        },
        getCourse(id) {
            this.show(id).then((result) => {
                this.course = result;
            })
        },
        getStudentGroup(id) {
            this.getByCourse(id).then((result) => {
                this.studentGroups = result;
            })
        }
    }
}
</script>

<style scoped>
p {
    font-size: 1em;
    line-height: 1.7em;
}

#sidebar {
    min-width: 180px;
    max-width: 180px;
    background-color: #182A36;
    color: #B4ADAD;
    transition: all 0.3s;
}

#sidebar.active {
    margin-left: -180px;
}

#sidebar .sidebar-header {
    background-color: #182A36;
    color: #fff;
    font-weight: 600;
}

#sidebar .sidebar-header p {
    padding: 10px 10px 0 20px;
}

#sidebar ul li a {
    padding: 8px 0 8px 20px;
    font-size: 1em;
    display: block;
    color: #B4ADAD;
}

#sidebar ul li a:hover {
    color: #fff;
    background: #274355;
}

#sidebar ul li.active>a {
    color: #fff;
    background: #274355;
}

@media(max-width:768px) {
    #sidebar {
        margin-left: -180px;
    }

    #sidebar.active {
        margin-left: 0px;
    }

    #sidebarCollapse span {
        display: none;
    }

    p,
    #sidebar ul li a {
        font-size: 0.9em;
    }
}
</style>