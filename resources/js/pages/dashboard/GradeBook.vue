<template>
    <div class="mt-3">
        <div class="loader" v-if="isLoading"></div>
        <h4 class="text-capitalize mt-3 mb-4 font-weight-bold">buku nilai dan rapor</h4>
        <div class="alert alert-danger mb-3" v-if="errorMessage">
        {{ errorMessage }}
        </div>
        <div class="row">
        <div class="col-md-6">
            <div class="input-group mb-3">
                <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Buku Nilai...." @keyup="searchSubject()" v-model="search">
                <div class="input-group-append">
                    <a href="#" class="btn btn-outline-muted btn-lg shadow-sm bg-white" @click="searchSubject()"><i class="fa fa-search"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex">
                <a href="#" class="btn btn-primary btn-block mt-md-1">
                    <span class="fas fa-plus"></span> Tambah Buku Nilai
                </a>
                <a href="#" class="btn btn-secondary btn-block mt-md-1">Refresh Data</a>
            </div>
        </div>
    </div>
        <div class="card w-100 p-3 mb-2 shadow-sm bg-white" data-bs-toggle="collapse">
            <a href="/:page/gradebook/nextgradebook" class="text-decoration-none text-dark">
                <div class="d-flex justify-content-between text-capitalize">
                    <div><span class="fas fa-file"></span>Semester Ganjil</div>
                </div>
            </a>
        </div>
        <div class="card p-3 mb-2 shadow-sm bg-white" data-bs-toggle="collapse">
            <a href="#" class="text-decoration-none text-dark">
                <div class="d-flex justify-content-between text-capitalize">
                    <div><span class="fas fa-file"></span>Semester Genap</div>
                </div>
            </a>
        </div>
        <div class="card p-3 mb-2 shadow-sm bg-white" data-bs-toggle="collapse">
            <a href="#" class="text-decoration-none text-dark">
                <div class="d-flex justify-content-between text-capitalize">
                    <div><span class="fas fa-file"></span>Matrikulasi</div>
                </div>
            </a>
        </div>
        <div class="card p-3 mb-2 shadow-sm bg-white" data-bs-toggle="collapse">
            <a href="#" class="text-decoration-none text-dark">
                <div class="d-flex justify-content-between text-capitalize">
                    <div><span class="fas fa-file"></span>Semester Baru</div>
                </div>
            </a>
        </div>
        <div class="card p-3 mb-2 shadow-sm bg-white" data-bs-toggle="collapse">
            <a href="#" class="text-decoration-none text-dark">
                <div class="d-flex justify-content-between text-capitalize">
                    <div><span class="fas fa-file"></span>Semester Ganjil</div>
                </div>
            </a>
        </div>
    </div>
</template>
<style scoped>
.card {
    border: 0 !important;
}
.btn-secondary {
    margin-left: 10px !important;
}
span.fas.fa-file{
    margin-right: 5px;
}
</style>