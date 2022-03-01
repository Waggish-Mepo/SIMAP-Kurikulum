<template>
<nav aria-label="Page navigation example" class="d-flex justify-content-between">
    <div class="dropdown dropright">
        <a class="btn btn-white dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 0.9rem" @click="dropDown">
            Rows per page
        </a>

        <div class="dropdown-menu" id="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="#" v-on:click.prevent="changePerPage(10)">10</a>
            <a class="dropdown-item" href="#" v-on:click.prevent="changePerPage(25)">25</a>
            <a class="dropdown-item" href="#" v-on:click.prevent="changePerPage(50)">50</a>
            <a class="dropdown-item" href="#" v-on:click.prevent="changePerPage(75)">75</a>
            <a class="dropdown-item" href="#" v-on:click.prevent="changePerPage(100)">100</a>
            <a class="dropdown-item" href="#" v-on:click.prevent="changePerPage(150)">150</a>
            <a class="dropdown-item" href="#" v-on:click.prevent="changePerPage(pagination.total)">all</a>
        </div>
    </div>
    <ul class="pagination">
        <li class="page-item">
            <div class="pt-1" style="margin-right: 8px">
                <span>{{pagination.current_page}} of {{pagination.last_page}} page</span>
            </div>
        </li>
        <li v-if="pagination.current_page > 1" class="page-item">
            <a href="#" aria-label="Previous" v-on:click.prevent="changePage(pagination.current_page - 1)" class="page-link">
                <span aria-hidden="true">Prev</span>
            </a>
        </li>
        <li class="page-item">
            <input type="number" style="width: 80px; height: 33px; margin: 0 10px;" class="form-control" v-on:input.prevent="changePageByInput" id="inputPage" v-model="inputPage">
        </li>
        <li v-if="pagination.current_page < pagination.last_page" class="page-item">
            <a href="#" aria-label="Next" v-on:click.prevent="changePage(pagination.current_page + 1)" class="page-link">
                <span aria-hidden="true">Next</span>
            </a>
        </li>
    </ul>
</nav>
</template>

<script>
export default {
    name: "paginationComponent",
    props: {
        pagination: {
            type: Object,
            required: true
        },
        offset: {
            type: Number,
            default: 2
        },
        data:{
            type: Object,
            required: true
        }
    },
    data(){
        return{
            inputPage: "",
        }
    },
    methods : {
        changePage(page) {
            this.pagination.current_page = page;
            this.data.page = page;
            this.$emit('paginate', this.data);
        },
        changePerPage(val) {
            this.pagination.per_page = val;
            this.data.per_page = val;
            this.$emit('paginate', this.data);
        },
        changePageByInput() {
            if(this.inputPage <= this.pagination.last_page){
                this.pagination.current_page = this.inputPage;
                this.data.page = this.inputPage;
                this.$emit('paginate', this.data);
            }
        },
        dropDown() {
            let menu = document.getElementById('dropdown-menu');
            menu.classList.toggle('show');
        }
    }
}
</script>

<style scoped>

</style>