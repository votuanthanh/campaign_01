<template lang="html">
    <div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="clients-grid">
				<ul class="cat-list-bg-style align-center sorting-menu">
					<li class="cat-list__item">
                        <router-link to="campaigns">Campaigns</router-link>
                    </li>
					<li class="cat-list__item">
                        <router-link to="events">Events</router-link>
                    </li>
					<li class="cat-list__item">
                        <router-link to="actions">Actions</router-link>
                    </li>
					<li class="cat-list__item">
                        <router-link to="contacts">Contacts</router-link>
                    </li>
				</ul>
				<div class="row sorting-container">
				    <router-view></router-view>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {

}
</script>

<style lang="css">
.cat-list__item .router-link-active {
    background-color: #ff5e3a;
    color: #fff;
}
</style>
