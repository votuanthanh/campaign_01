<template>
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="ui-block">
                    <div class="ui-block-title">
                        <i id="see-more">{{ text }}</i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data : () => {
            return {

            }
        },
        props: [
            'text'
        ],
        method: {

        },
        mounted: {

        }
    }

</script>

<style type="scss">

</style>
