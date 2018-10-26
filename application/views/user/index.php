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
<div class="row justify-content-center"> 

    <div class="col-lg-12 col-xl-12">
       
             <div class="row content-list-head" style="padding-top: 20px;">
            <div class="col-auto">
                <h3><?=$title;?></h3>
                <button class="btn btn-round new" >
                    <i class="fa fa-plus"></i>
                </button>
                <button class="btn btn-round edit" >
                    <i class="fa fa-pencil"></i>
                </button>
                <button class="btn btn-round delete" >
                    <i class="fa fa-trash"></i>
                </button>
            </div>
            

          </div>
       

        <!-- COntent Here -->
        <div class="d-flex flex-row-reverse"> <button type="file" class="btn btn-primary " onclick="document.getElementById('getFile').click()"><i class="fa fa-arrow-circle-down"></i> Import CSV</button>
        <input type='file' id="getFile" style="display:none"></div>
        <br />
        <table id="dg"  style="height:400px;"
                >
            <thead>
                <tr>
                   
                    <th width="20%" data-options="field:'username',width:100">Username</th>
                    <th width="20%" data-options="field:'name',width:100">Name</th>
                    <th width="20%" data-options="field:'email',width:100">Email</th>
                   <th width="20%" data-options="field:'role',width:100">Role</th>
                    
                </tr>
            </thead>
        </table>
	     
       
    </div>
</div>
 <div id="dlg" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
	        <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px" enctype="multipart/form-data">
	           <div style="margin-bottom:10px">
	                <input name="username" class="easyui-textbox" required="true" label="Username:" style="width:100%">
	            </div>
                <div style="margin-bottom:10px">
                    <input name="name" class="easyui-textbox" required="true" label="Full Name:" style="width:100%">
                </div>
                <div style="margin-bottom:10px">
                    <input name="password" class="easyui-passwordbox" required="true" label="Passowrd:" style="width:100%">
                </div>
                 <div style="margin-bottom:10px">
                    <input name="password_confirm" class="easyui-passwordbox" required="true" label="Retype Passowrd:" style="width:100%">
                </div>
                 <div style="margin-bottom:10px">
                    <input name="email" class="easyui-textbox" required="true" label="Email:" style="width:100%">
                </div>
                 <div style="margin-bottom:10px">
                   <input id="role_id" class="easyui-combobox" name="role_id" style="width:100%;" data-options="
                    valueField: 'role_id',
                    textField: 'role',
                    label: 'Role:',
                    labelPosition: 'top'
                    ">
                </div>
               <!--  <div style="margin-bottom:10px">
                    <input name="file" class="easyui-filebox" required="true" label="Avatar:" style="width:100%">
                </div> -->
	            
	            
	        </form>
	    </div>
	    <div id="dlg-buttons">
	        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save()" style="width:90px">Save</a>
	        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	    </div>
       



 <script>
       
            var url;
            function newItem()
            {
                          
                $('#dlg').dialog('open').dialog('center').dialog('setTitle','New User');
                $('#fm').form('clear');
                url = '<?php echo site_url("users/save");?>';
        
            }
            function edit(){
                var row = $('#dg').datagrid('getSelected');
                if (row){
                    $('#dlg').dialog('open').dialog('center').dialog('setTitle','Edit User');
                    $('#fm').form('load',row);
                    url = '<?php echo site_url("users/save");?>'+"?user_id="+row.user_id;
                }
            }
            function save(){
                if($("input[name='password']").val() == $("input[name='password_confirm']").val()){
                    $('#fm').form('submit',{
                        url: url,
                        onSubmit: function(){
                            return $(this).form('validate');
                        },
                        success: function(result){
                            console.log(result);
                            var result = eval('('+result+')');
                            if (result=="failed"){
                                $.notify({
                                    icon: 'fa fa-close',
                                    message: "Failed Adding Item"
                                },{
                                    type: 'danger'
                                });
                            } else {
                                $.notify({
                                    icon: 'fa fa-check',
                                    message: "Item Saved"
                                },{
                                    type: 'success'
                                });
                                $('#dlg').dialog('close');        // close the dialog
                                $('#dg').datagrid('reload');    // reload the user data
                                $('#fm').form('clear');
                            }
                        }
                    });
                }else{
                    $.messager.alert('Warning','Not Valid Password Confirmation. Retype Correct Password','error');
                }
            }
            function search(){
                     
                
               
                    $("#dg").datagrid('load',{
                        name :$("#search_").val(),
                        username:$("#search_").val(),
                        
                    });
                
                
           }
        </script>
        <script type="text/javascript">

           $(document).ready(function(){ 

            var toolbar = [
            {
                text:'<input type="text" id="search_" class="form-control form-control-sm" size=50 placeholder="Search, Name , or Description then click Search " />'
            },
            {
                text:'<i class="fa fa-search"></i> Search',
                iconCls:'icon-search',
                handler :function(){
                    search();
                }
            }
            ];
            var dg = $('#dg').datagrid({
                url: '<?=site_url("users/getUsersJson");?>',
                pagination: true,
                clientPaging: false,
                remoteFilter: true,
                rownumbers: false,
                method:'post',
                toolbar:toolbar,
                singleSelect:true
            });

            $("#role_id").combobox('reload',"<?=site_url('roles/getRolesJSONCOmboBox');?>");

            $(".new").click(function(){
				newItem();
			});
			 $(".edit").click(function(){
				 var row = $('#dg').datagrid('getSelected');
                    if (row){                      
                       
                           edit();
                        
                    }else{
                        $.messager.alert('Warning','Please Select item First!','error');
                    }
			});
			  $(".delete").click(function(){
				 var row = $('#dg').datagrid('getSelected');
                    if (row){                      
                       
                        $.messager.confirm('Confirmation', 'Are you confirm to delete this?', function(r){
                            if (r){
                               $.post("<?=site_url('users/delete');?>",{user_id:row.user_id},function(response){
                                    if(response=="success"){
                                        $.notify({
                                            icon: 'fa fa-check',
                                            message: "You Deleted an Item"
                                        },{
                                            type: 'success'
                                        });
                                    }else{
                                        $.notify({
                                            icon: 'fa fa-close',
                                            message: "Failed Deleted an Item"
                                        },{
                                            type: 'danger'
                                        });
                                    }
                                });
                                $('#dg').datagrid('reload');
                                
                            }
                        });
                        
                    }else{
                        $.messager.alert('Warning','Please Select item First!','error');
                    }
			});


        });
          
        </script>


