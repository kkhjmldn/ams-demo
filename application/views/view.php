<div class="row">
		    <div class="form-group col-md-12">
		        <h2><?=$page_title;?></h2>
		       <ul class="breadcrumb">
            <?php 
            $i=1;
            if(isset($breadcrumb) && $breadcrumb!=NULL){
                foreach ($breadcrumb as $key => $value) {
                    if($i==sizeof($breadcrumb)){?>
                        <li class="active"><a href="#"><?=$key;?></a></li>
                    <?php }else{?>
                        <li ><a href="<?=$value;?>"><?=$key;?></a></li>
                  <?php  }
                  $i++;
              }   
          }?>
           
        </ul>

		    </div>
		</div>
		
		<div class="row">
		    <div class="form-group col-md-12">
		        <h3>Themes</h3>
		        <p>Here are more themes that you can use</p>
		    </div>
		</div>

		<div class="row">
		    <div class="form-group col-md-12">
		        <a href="#" data-theme="chiller-theme" class="theme chiller-theme selected"></a>
		        <a href="#" data-theme="ice-theme" class="theme ice-theme"></a>
		        <a href="#" data-theme="cool-theme" class="theme cool-theme"></a>
		        <a href="#" data-theme="light-theme" class="theme light-theme"></a>                       
		    </div>
		    <div class="form-group col-md-12">
		        <p>You can also use background image </p>
		    </div>
		    <div class="form-group col-md-12">
		        <a href="#" data-bg="bg1" class="theme theme-bg selected"></a>
		        <a href="#" data-bg="bg2" class="theme theme-bg"></a>
		        <a href="#" data-bg="bg3" class="theme theme-bg"></a>
		        <a href="#" data-bg="bg4" class="theme theme-bg"></a>
		    </div>
		    <div class="form-group col-md-12">
		       <div class="form-check">
		         <label class="form-check-label">
		           <input type="checkbox" class="form-check-input" name="" id="toggle-bg" checked>
		            Background image
		         </label>
		       </div>
		    </div>
		   
		</div>
