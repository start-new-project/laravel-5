<template>
    <div class="col-md-10 offset-md-1">
      <div class="card">
        <div class="card-header">
          <div class="float-right"> 
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" name="active" type="checkbox" value="true" v-model="form.active"> {{$t('lib.active')}}
              </label>
            </div> 
          </div>
          <h3 v-if="type">{{$t('lib.'+type)}}</h3>
          <h3 v-else>{{$t('lib.user')}}</h3>
        </div>
        <div class="card-body">
          <form @submit.prevent="save" @keydown="form.onKeydown($event)">

            <div class="form-group">
              <label>{{$t('lib.fullname')}}</label>
              <input v-model="form.name" type="text" name="name"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
              <has-error :form="form" field="name"></has-error>
            </div>

            <div class="form-group">
              <label>{{$t('lib.email')}}</label>
              <input v-model="form.email" type="text" name="email"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
              <has-error :form="form" field="email"></has-error>
            </div>  

            <div class="form-group">
              <label>{{$t('lib.password')}}</label>
              <input v-model="form.password" type="password" name="password"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
              <has-error :form="form" field="password"></has-error>
            </div>

            <div class="form-group">
              <label>{{$t('lib.birthday')}}</label>
              <input v-model="form.birthday" type="date" name="birthday"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('birthday') }">
              <has-error :form="form" field="birthday"></has-error>
            </div> 

            <fieldset class="form-group">
              <div class="form-check form-check-inline">
                <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="gender" value="0" v-model="form.gender" checked="checked"> {{$t('lib.women')}}
                </label>
              </div>
              <div class="form-check form-check-inline">
                <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="gender" value="1" v-model="form.gender" > {{$t('lib.men')}}
                </label>
              </div>
            </fieldset> 

            <div class="form-group" v-if="!type">
              <label>{{$t('lib.role')}}</label>
              <select name="role" class="form-control" v-model="form.role"  :class="{ 'is-invalid': form.errors.has('role') }">
                <option value="user">{{$t('lib.user')}}</option>
                <option value="student">{{$t('lib.student')}}</option>
                <option value="client">{{$t('lib.client')}}</option>
                <option value="admin">{{$t('lib.admin')}}</option>
              </select>
              <has-error :form="form" field="role"></has-error>
            </div> 
            

            <button :disabled="form.busy" type="submit" class="btn btn-primary">{{$t('lib.save')}}</button>
        </form>
        </div>
      </div>
    </div> 
    
</template>

<script> 


export default {

  data () {
    return {
      form: new Form({
        name: '',
        email: '',
        password: '',
        active: false,
        gender: false,
        role: 'user',
        birthday: '',
      }),
      type: null
    }
  }, 
  methods: {
    save () { 
      var url =  $('#api-route').attr('route')+'/users'
      this.form.post(url)
        .then(data=>{
            this.$swal('Done', 'User created successfuly', 'success');
            this.$router.push({name:'admin.users.index'})
        })
    }
  },
  created(){
    var type = this.$route.params.type;
    if(type!= undefined)
      this.type = type
  }
} 
</script>