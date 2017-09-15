<template>
    <div class="container page-error">
        <img src="/images/error-img.png" title="error">
        <router-link :to="{ name: 'homepage' }">
            {{ $t('errors.back_homepage')}}
        </router-link>
    </div>
</template>
<style lang="scss">
    .page-error {
        text-align: center;
        img {
            width: 60%;
            margin-top: 150px;
        }
        a {
            font-size: 30px;
            display: block;
            margin-top: 50px;
        }
    }
</style>
