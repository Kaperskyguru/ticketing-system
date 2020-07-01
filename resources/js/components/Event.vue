<template>
	<div class="col-md-4">
	    <div class="card p-5 card-custom">
		    <h5 class="authBtn">{{event.title}}</h5>
		    <hr>
		    <small class="authBtnInner pb-3">
		    	{{event.description}}
		    </small>
		    <router-link :to="{name:'ticket', params:{ id: event.id }}" class="btn btn-primary customBtn">Click Me</router-link>
		</div>
		<br />
	</div>
</template>

<script>
export default {
  name: 'Event',
  props:['event'],

  data () {
    return {

    }
  },
  mounted(){
  	// console.log(this.event)
  }
};
</script>
