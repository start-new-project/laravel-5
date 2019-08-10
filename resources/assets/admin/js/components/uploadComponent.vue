<template>
    <div>
        <input type="file" name="file" id="fileToUpload" @change="previewImage">
        <button @click="uploadFileRequest" class="btn btn-sm btn-success">Send</button>
        <hr>
        <div class="progress" style="height: 3px;">
            <div 
                id="UpProgressBar"
                class="progress-bar" 
                role="progressbar" 
                style="width: 0%;" 
                aria-valuenow="0" 
                aria-valuemin="0" 
                aria-valuemax="100"
                ></div>
        </div>
        <br>
        <img :src="image" alt="" class="img">
        <hr>
        <button v-on:click="alertDisplay">Click me</button>
    </div>
</template>
<script>
export default {
    data(){
        return {
            progressval: 0,
            image:''
        }
    },
    created(){
        alert('Hello page !')   
    },
    methods: {
        uploadFileRequest: function(){  
            //$('#fileToUpload').uploadBFile();
            //$('#fileToUpload').val('')
        },
        previewImage: function(event){
            var input = event.target
            if(input.files && input.files[0]){
                var reader = new FileReader();
                reader.onload = (e)=>{
                    this.image = e.target.result
                }
                reader.readAsDataURL(input.files[0])
            }
        }, 
        alertDisplay() {
            // $swal function calls SweetAlert into the application with the specified configuration.
            this.$swal('Heading', 'this is a Heading', 'success');
            this.$snotify.success('Example body content',null,{
                timeout: 200000,
            });
        }
    }
}
</script>

