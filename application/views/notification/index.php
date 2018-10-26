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
<div class="row justify-content-center" style="padding-bottom: : 20px;"> 

    <div class="col-lg-12 col-xl-12">
       
             <div class="row content-list-head" style="padding-top: 20px;">
            
                <h3><?=$title;?></h3>
            

          </div>
       

        <!-- COntent Here -->
        <div style="height:400px;overflow-y: scroll">
           <ul class="list-group" style="font-size: 9pt;">
                                     <?php if(isset($data_notifications)){
                                foreach ($data_notifications['data'] as $key => $value) { ?>
                                     <li class="list-group-item">
                                          <img alt="Peggy" src="<?=base_url();?>assets/img/bell.jpeg" class="avatar">
                                          
                                                  <span data-filter-by="text" class="SPAN-filter-by-text"><?=date('d-m-Y H:i',strtotime($value->time));?></span>
                                             
                                            
                                                <p><a href="<?=$value->link;?>" ><?=$value->notification;?></a></p>
                                                      
                                          
                                  </li>
                                      <?php }
                              }?>
                                   </ul>
                                   </div>
                     
	     
       <!-- end content -->
    </div>
</div>
