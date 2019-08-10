<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $t('lib.list_users') }} 
                        <div class="float-right">
                            <router-link :to="{name:'admin.users.add'}" class="btn btn-sm btn-success m-1 d-flex">
                                <i class="fas fa-plus"></i>
                                <span class="d-none d-md-block">
                                    &nbsp; {{ $t('lib.add_user') }}    
                                </span>    
                            </router-link>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" v-if="users">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ $t('lib.fullname') }}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tr v-for="(user, index) in users" :key="user.id">
                                <td>
                                    <img :src="user.avatar.xs" class="img img-xs">
                                </td>
                                <td>
                                    <a :href="user.links.profile">
                                        {{ user.fullname }}
                                    </a>
                                </td> 
                                <td>
                                    <span class="badge " :class="'badge-'+user.status.color">{{ user.status.text }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <router-link class="dropdown-item" :to="{name:'admin.users.edit',params:{id:user.id}}">{{$t('lib.edit')}}</router-link>
                                            <a class="dropdown-item" href="#" @click="deleteUser(user.id,index)">{{$t('lib.delete')}}</a>
                                        </div>
                                    </div>
                                </td> 
                            </tr>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                users: []
            }
        },
        created(){
            axios.get($('#api-route').attr('route')+'/users?filter=team').then(response=>{
                this.users = response.data.data;
            })
        },
        methods: {
            deleteUser: function(id,index){
                axios.delete($('#api-route').attr('route')+'/users/'+id).then((e)=>{
                    this.users.splice(index,1)
                    this.$swal('Done', 'User deleted successfuly', 'success');
                })
            }
        }
    }
</script>
