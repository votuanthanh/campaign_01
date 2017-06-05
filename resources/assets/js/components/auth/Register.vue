<template lang="html">
    <div class="tab-pane active" id="home" role="tabpanel" data-mh="log-tab">
        <div class="title h6">Register to Olympus</div>
        <form class="content">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="form-group label-floating">
                        <label class="control-label">First Name</label>
                        <input class="form-control" placeholder="" type="text">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form-group label-floating">
                        <label class="control-label">Last Name</label>
                        <input class="form-control" placeholder="" type="text">
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="form-group label-floating">
                        <label class="control-label">Your Email</label>
                        <input class="form-control" placeholder="" type="email">
                    </div>
                    <div class="form-group label-floating">
                        <label class="control-label">Your Password</label>
                        <input class="form-control" placeholder="" type="password">
                    </div>

                    <div class="form-group date-time-picker label-floating">
                        <label class="control-label">Your Birthday</label>
                        <input name="datetimepicker" value="10/24/1984" />
                        <span class="input-group-addon">
                            <svg class="olymp-calendar-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-calendar-icon"></use></svg>
                        </span>
                    </div>

                    <div class="form-group label-floating is-select">
                        <label class="control-label">Your Gender</label>
                        <select class="selectpicker form-control">
                            <option value="MA">Male</option>
                            <option value="FE">Female</option>
                        </select>
                    </div>

                    <div class="remember">
                        <div class="checkbox">
                            <label>
                                <input name="optionsCheckboxes" type="checkbox">
                                I accept the <a href="#">Terms and Conditions</a> of the website
                            </label>
                        </div>
                    </div>

                    <a href="#" class="btn btn-purple btn-lg full-width">Complete Registration!</a>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {

}
</script>

<style lang="scss">
</style>
