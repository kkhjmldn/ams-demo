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
<div class="content-container" style="height: 400px;">
                    <div class="chat-module" style="padding-top: 2px;">
                        <div class="message-loader" style='font-size: 10pt;overflow-y: scroll;'>
                                <ul class="list-group load-message" >
                                  
                                  
                                  
                                </ul>
                           
                        </div>
                        <div class="chat-module-bottom">
                            <form class="chat-form">
                                <input type="hidden" name="receiver_id" />
                                <textarea class="form-control" name="message" placeholder="Type message" rows="3" style="overflow: hidden; overflow-wrap: break-word; resize: none; height: 40px;"></textarea>
                                <div class="chat-form-buttons">
                                    <a  class="btn btn-link send">
                                        <i class="fa fa-send"></i>
                                    </a>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                 
                    <div class="sidebar collapse" id="sidebar-collapse">                      
                        <div class="sidebar-content">
                            <div class="chat-team-sidebar text-small">
                                <div class="chat-team-sidebar-top">
                                    <h4>Users</h4>
                                </div>
                                <div class="chat-team-sidebar-bottom">
                                        <ul class="list-group">
                                            <?php 
                                            if(isset($data_users)){
                                                foreach ($data_users['data'] as $key => $value) {?>
                                                  
                                                <li class="list-group-item"><a href="#<?=$value->user_id;?>" class="userlist" data-id='<?=$value->user_id;?>'> <img alt="Peggy" src="<?=base_url();?>assets/img/user.jpg" class="avatar">
                                                    <?=$value->name;?></a></li>
                                                <?php
                                            }
                                        }?>
                                            </ul>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>


       <!-- end content -->
    </div>
</div>
<script type="text/javascript">
        var receiver_id = '';
        $(document).ready(function(){

            $(".userlist").click(function(){
                receiver_id = $(this).attr("data-id");
                $(".load-message").empty();
                $.get("<?=site_url('messages/get_message');?>",{receiver_id:$(this).attr("data-id")},function(e){
                    var resp = JSON.parse(e);

                    $.each(resp.data,function(k,v){

                        var messages = '';
                        if(v.sender_id == "<?=$user_id;?>"){
                            $(".load-message").append('<li class="list-group-item text-right" style="background-color: #ceefd0;margin-left:40px;margin-bottom:10px;border-radius:10px;"><p>'+v.time+'</p>'+v.message+'</li>');
                                                    }else if(v.receiver_id=="<?=$user_id;?>"){
                            $(".load-message").append('<li class="list-group-item text-left" style="background-color: #cee0ed;margin-right:40px;margin-bottom:10px;border-radius:10px;"><p>'+v.time+'</p>'+v.message+'</li>');
                        }
                    });
                });
                 var objDiv = $(".message-loader");

               var h = objDiv.get(0).Height;

               objDiv.animate({scrollTop: '100000px'});
            });

            $(".send").click(function(){

                if(receiver_id==''){
                    $.messager.alert('Warning','Please Select User First!','error');
                }else{
                    var message = $("textarea[name='message']").val();
                    $.post("<?=site_url('messages/send_message');?>",{message:message,receiver_id:receiver_id},function(e){
                        get_message_person(receiver_id);
                    });
                    $("textarea[name='message']").val('');
                }
            });
        });

        function get_message_person(receiver_id){
               $(".load-message").empty();
                $.get("<?=site_url('messages/get_message');?>",{receiver_id:receiver_id},function(e){
                    var resp = JSON.parse(e);

                    $.each(resp.data,function(k,v){

                        var messages = '';
                        if(v.sender_id == "<?=$user_id;?>"){
                            $(".load-message").append('<li class="list-group-item text-right" style="background-color: #ceefd0;margin-left:40px;margin-bottom:10px;"><p>'+v.time+'</p>'+v.message+'</li>');
                                                    }else if(v.receiver_id=="<?=$user_id;?>"){
                            $(".load-message").append('<li class="list-group-item text-left" style="background-color: #cee0ed;margin-right:40px;margin-bottom:10px;"><p>'+v.time+'</p>'+v.message+'</li>');
                        }
                    });
                });

                var objDiv = $(".message-loader");

               var h = objDiv.get(0).Height;

               objDiv.animate({scrollTop: '100000px'});
        }

</script>
