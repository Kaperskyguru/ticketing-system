<template>
	<div class="row pb-5">
		<Event v-for="(event, i) in 6" :key="i" />
	</div>
</template>

<script>
import Event from "./Event.vue";

export default {
  name: 'Events',
  components: { Event },

  data () {
    return {

    }
  }
};
</script>
