<template>


    <div>

        <div class="content-wrapper">

            <div class="row">

                <div class=" col-md-12 ">
                    <div class="media align-items-center bg-white mb-20">
                    
                    <div class="media-body">
                        <h3><strong>Reportes</strong></h3>
                    </div>
                    </div>
                </div>

            </div>

<section class="content">


            
</section>


        </div>



    </div>


</template>

<script>

    import LayoutDefault from '../components/layouts/Principal.vue';
    import {misMixins} from '../mixins.js';

       export default {

           created:function(){

               this.$emit('update:layout', LayoutDefault);
             
           },

            data() {
                return {
       

            };
        },


           mixins:[misMixins],

           methods:{





           }
       }


</script>

<style scoped>

.center-block {
    display: table;  /* Instead of display:block */
    margin-left: auto;
    margin-right: auto;
}

.date-picker /deep/ input {
    display: block !important;
    width: 100% !important;
    color: #495057 !important;
    background-color: #fff !important;
    background-clip: padding-box !important;
    border: 1px solid #ced4da !important;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out !important;
    padding: .25rem .5rem !important;
    font-size: .875rem !important;
    line-height: 1.5 !important;
    border-radius: .2rem !important;
}

.align-right{
    margin-left: 150px;
    background-color: #28d094 !important;
    color: white;
}

.btn-success {
     background-color: #28d094 !important;
     border-color: #28d094 !important;
     color: white;
}

.card-tam{
    height: 400px;;
}

.button-position{
    margin-top: 80px;
}

</style>
