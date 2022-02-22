<template>
    <div>
        <!-- <div class="loader" v-if="isLoading"></div>
        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div> -->
        <div class="row my-3">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Angkatan....">
                    <div class="input-group-append">
                        <a href="#" class="btn btn-outline-muted btn-lg shadow-sm bg-white"><i class="fas fa-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex">
                    <a href="#" class="btn btn-primary btn-block mt-md-1">
                        <span class="fas fa-plus"></span> Tambah Mapel
                    </a>
                    <a href="#" class="btn btn-secondary btn-block mt-md-1">Refresh Data</a>
                </div>
            </div>
        </div>

        <div class="card bg-white w-100 shadow-sm p-3 mb-3 text-capitalize">
            <router-link v-bind:to="{ name: 'student_groups', params: {page: 5, batch: 1} }" class="router">
            <div class="d-flex align-items-center text-dark">
                <span class="fas fa-trophy"></span> angkatan 22
            </div>
            </router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: "batch"
}
</script>

<style scoped>
.input-text:focus {
    box-shadow: 0px 0px 0px;
    border-color: #B4ADAD;
    outline: 0px;
}

.form-control {
    border: 1px solid #B4ADAD;
}

.btn-outline-muted {
    color: #535353;
    border-color: #B4ADAD;
    border-radius: 0px !important;
}

.btn-secondary {
    margin-left: 10px !important;
}

.card {
    font-weight: 500;
    font-size: 1rem;
    cursor: pointer;
}

a.router {
    text-decoration: none !important;
    color: #000 !important;
}

.card span {
    margin-right: 8px;
}

@media (max-width: 575px) {
    .btn, .input-text {
        font-size: 0.8rem !important;
    }
    .btn {
        padding: 0.2rem 0.5rem !important;
    }
}
</style>