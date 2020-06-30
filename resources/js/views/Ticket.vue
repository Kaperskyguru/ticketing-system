<template>
	<div class="text-center banner p-3 pb-5">
      <div class="container p-5 pb-5">
        <div class="row p-5 pb-5">
          <div class="col-md-8">
            <div class="card card-custom p-5">
              <h5 class="authBtn">Event One</h5>
              <hr />

              <div class="container">
                <small class="authBtnInner pb-3">
                  Lorem Ipsum is simply dummy text of the printing and
                  typesetting industry. Lorem Ipsum has been the industry's
                  standard dummy text ever since the 1500s, when an unknown
                  printer took a galley of type and scrambled it to make a type
                  specimen book. It has survived not only five centuries, but
                  also the leap into electronic typesetting, remaining
                  essentially unchanged. It was popularised in the 1960s with
                  the release of Letraset sheets containing Lorem Ipsum
                  passages, and more recently with desktop publishing software
                  like Aldus PageMaker including versions of Lorem Ipsum.
                </small>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card card-custom p-5">
              <h5 class="authBtn">Event One Ticket</h5>
              <hr />
              <small class="authBtnInner pb-3"
                >Equipped with the cutting edge features that make a 21st
                Century Investment Platform,</small
              >
              <router-link
                to="/"
                class="btn btn-primary customBtn"
                >Buy Ticket</router-link
              >
            </div>
          </div>
        </div>
      </div>
	</div>
</template>

<script>
export default {
  name: 'Ticket',
  data () {
    return {

    }
  }
};
</script>