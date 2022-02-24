<template>
    <div class="d-flex justify-content-center flex-wrap box bg-blue1">
        <div class="text-box text-white">
            <h2>404!<br />Oooopsss! Page not found!</h2>
            <h3>
                This page doesn't exist or was removed we suggest you back to
                Dashboard
            </h3>
            <router-link v-bind:to="{ name: 'dashboard' }">
                <a href="#" class="text-white"
                    ><span class="fas fa-arrow-left"></span>Back</a
                >
            </router-link>
        </div>
        <img
            src="/assets/img/error_page.png"
            alt="error 404"
            class="img-error-404"
        />
    </div>
</template>

<style scoped>
h2 {
    font-weight: 700;
    font-size: 2rem;
}
.box {
    min-height: 100vh;
    width: 100%;
    margin: auto;
}
.text-box {
    max-width: 500px;
    text-align: right;
    padding-top: 15rem;
}
img {
    max-width: 600px;
    width: 100%;
}
@media (max-width: 590px) {
    h2 {
        font-size: 2rem;
    }
    .text-box {
        max-width: 350px;
        padding-top: 5rem;
    }
    h3 {
        font-size: 1rem;
    }
    img {
        max-width: 400px;
    }
}
@media (max-width: 375px) {
    img {
        display: none;
    }
    .text-box {
        padding-top: 17rem;
        max-width: 250px;
        padding-right: 1rem;
    }
}
</style>
