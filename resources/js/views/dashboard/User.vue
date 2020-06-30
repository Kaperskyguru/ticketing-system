<template>
<div class="text-center banner">
      <div class="container p-5">
          <div class="p-5">
            <h1>User DashBoard</h1>
          </div>
      </div>
        <div class="container pb-5">
            <div class="row pb-5">
                <div class="col-md-6">
                	<counter title="Total Events" :value="2"></counter>
                </br>
                </div>
                
                <div class="col-md-6">
                	<counter title="Total Amount Spent" :value="$243"></counter>
                </div>
                <br />
            </div>
        </div>
        <br><br><br><br>

        <div class="container pt-5 pb-5">
            <h5 class="pb-5">All Events</h5>
            <div class="row pb-5">
                <div class="col-md-12">
                    <div class="card">
                        <event-table />
                    </div>
                </div>
            </div>
        </div>
        
      </div>
    </div>
</template>

<script>
import Counter from '../../components/Counter'
import EventTable from '../../components/EventTable'
export default {

  name: 'User',
  components: { Counter, EventTable },

  data () {
    return {

    }
  }
};
</script>

<style lang="css" scoped>
</style>