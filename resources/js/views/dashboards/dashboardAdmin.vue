<template>

    <div>

<div class="content-wrapper">


        <!-- Main content -->
    <section class="content">

        
         <div class="row">

             <div class="col-xl-4 col-md-4 col-4" >
			  <!-- small box -->
			  <div class="small-box box-inverse bg-success-gradient">
				<div class="inner">
				  <h3></h3>

				  <p>TOTAL CURSOS</p>
				</div>
				<div class="icon text-white">
				  <i class="fa fa-check"></i>
				</div>
				
			  </div>
			</div>

            <div class="col-xl-4 col-md-4 col-4" >
			  <!-- small box -->
			  <div class="small-box box-inverse bg-warning-gradient">
				<div class="inner">
				  <h3></h3>

				  <p>TOTAL USUARIOS</p>
				</div>
				<div class="icon text-white">
				  <i class="fa fa-exclamation-triangle"></i>
				</div>
				
			  </div>
			</div>

            <div class="col-xl-4 col-md-4 col-4" >
			  <!-- small box -->
			  <div class="small-box box-inverse bg-warning-gradient">
				<div class="inner">
				  <h3></h3>

				  <p>TOTAL MATRICULAS</p>
				</div>
				<div class="icon text-white">
				  <i class="fa fa-exclamation-triangle"></i>
				</div>
				
			  </div>
			</div>

         </div>




        </section>

    </div>

    </div>

</template>

<script>

     export default{

        created: function(){



        },

        data (){

            return{

                TotalCursos:0,
                TotalUsuarios:0,
                TotalMatriculas:0,
            

            }
        },

      

        methods:{

        }

     }


</script>