<template>
	<div class="text-center banner">
      <div class="container p-5">
          <div class="p-5">

            <h1>Admin DashBoard</h1>
          </div>
      </div>
        <div class="container pb-5">
            <div class="row pb-5">
                <div class="col-md-4">
                	<counter title="Total Events" :value="70"></counter>
                </br>
                </div>
                
                <div class="col-md-4">
                	<counter title="Joined Events" :value="20"></counter>
                </div>
                <br />
                <div class="col-md-4">
                	<counter title="Upcoming Events" :value="60"></counter>
                </br>
            </div>
        </div>
        <br><br><br><br>
<router-view></router-view>
        <div class="container pt-5">
            <h5 class="pb-5">All Events</h5>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <EventTable />
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

  name: 'Admin',
  components: { EventTable, Counter },

  data () {
    return {

    }
  }
};
</script>

<style lang="css" scoped>
</style>