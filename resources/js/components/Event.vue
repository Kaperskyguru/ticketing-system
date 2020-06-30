<template>
	<div class="col-md-4">
	    <div class="card p-5 card-custom">
		    <h5 class="authBtn">Event One</h5>
		    <hr>
		    <small class="authBtnInner pb-3">
		    	Equipped with the cutting edge features that make a 21st Century 	Investment Platform,
		    </small>
		    <router-link :to="{name:'ticket', params:{ id: 1 }}" class="btn btn-primary customBtn">Click Me</router-link>
		</div>
		<br />
	</div>
</template>

<script>
export default {
  name: 'Event',

  data () {
    return {

    }
  }
};
</script>
