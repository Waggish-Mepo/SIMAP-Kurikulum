<template>
    <div class="mt-3">
        <!-- <div class="loader" v-if="isLoading"></div>
        <div class="alert alert-danger my-3" v-if="errorMessage">
        {{ errorMessage }}
        </div> -->
        <div class="row my-3">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="text" class="form-control input-text shadow-sm bg-white" placeholder="Cari Rombel....">
                    <div class="input-group-append">
                        <a href="#" class="btn btn-outline-muted btn-lg shadow-sm bg-white"><i class="fas fa-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex">
                    <a href="#" class="btn btn-primary btn-block mt-md-1">
                        <span class="fas fa-plus"></span> Tambah Siswa
                    </a>
                    <a href="#" class="btn btn-secondary btn-block mt-md-1">Refresh Data</a>
                </div>
            </div>
        </div>

        <div class="row mt-2 d-flex justify-content-center">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data siswa rombel RPL XII-2</h4>
                        <p class="card-description">Angkatan 22</p>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>NIS</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Hitesh Chauhan</td>
                                        <td>Engine</td>
                                        <td>
                                            <label class="badge bg-danger">Hapus</label>
                                            <label class="badge bg-green1 text-white">Edit</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Samso Palto</td>
                                        <td>Brakes</td>
                                        <td>
                                            <label class="badge bg-danger">Hapus</label>
                                            <label class="badge bg-green1 text-white">Edit</label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "studentData",
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

/* table */
.flex {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    background-color: #fff;
    border: 1px solid #d2d2dc;
    border-radius: 0;
}

.card .card-title {
    color: #000;
    margin-bottom: 10px;
    text-transform: capitalize;
    font-size: 1rem;
    font-weight: 600;
}

.card .card-description {
    color: #B4ADAD;
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
}

.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    -ms-overflow-style: -ms-autohiding-scrollbar;
}

.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}

.table thead th {
    border-top: 0;
    border-bottom-width: 1px;
    font-size: 1rem;
    text-transform: uppercase;
}

.table td {
    font-size: 1rem;
    padding: 0.5rem;
}

.badge {
    border-radius: 0;
    font-size: 14px;
    line-height: 1;
    padding: 0.3rem 0.5rem;
    font-weight: normal;
    cursor: pointer;
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