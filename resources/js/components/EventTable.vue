<template>
	<table class="table table-sm table-hover table-responsive text-left table-bordered">
		<thead>
		    <tr>
			    <th scope="col">#</th>
			    <th scope="col">Title</th>
			    <th scope="col">Description</th>
			    <th scope="col">Price</th>
			    <th scope="col">Event Date</th>
			    <th scope="col">Actions</th>
		    </tr>
		</thead>
		<tbody>
		    <tr v-for="(event , i) in events">
			    <th scope="row">{{i+1}}</th>
			    <td>{{ textPrefix(event.title, 40, '...') }}</td>
			    <td>{{ textPrefix(event.description, 40, '...') }}</td>
			    <td>{{ event.ticket_price }}</td>
			    <td>{{ event.event_date }}</td>
			    <td>
			    	<button type="button" class="btn btn-primary">View</button>
			    	<button type="button" class="btn btn-danger">Delete</button>
			    </td>
		    </tr>
		</tbody>
	</table>
</template>

<script>
export default {

  name: 'EventTable',

  props:['events'],

  data () {
    return {

    }
  },

  methods:{
  	textPrefix(text, length, suffix = null) {
  	    if (text.length > length) {
  	      return text.substring(0, length) + suffix;
  	    } else {
  	      return text;
  	    }
  	  }
  }
};
</script>

<style lang="css" scoped>
</style>