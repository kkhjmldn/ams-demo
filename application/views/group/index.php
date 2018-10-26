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
         <table id="dg"  title="Groups" style="height:400px"
                >
            <thead>
                <tr>
                   
                    <th data-options="field:'group_name',width:280">Group</th>
                   
                    <th data-options="field:'total_member',width:80">Total Members</th>
                   
                </tr>
            </thead>
        </table>
	     
       
    </div>
</div>
 <div id="dlg" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
	        <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px" enctype="multipart/form-data">
           <div style="margin-bottom:10px">
                <input name="group_name" class="easyui-textbox" required="true" label="Group Name:" style="width:100%">
            </div>
            <!-- <div style="margin-bottom:10px">
                <input name="description" class="easyui-textbox" multiline="true" required="true" label="Description:" style="width:100%;height: 120px;">
            </div> -->
            <div style="margin-bottom:10px">
                <select id="personel_id" class="easyui-combogrid" name="user_id[]" multiline="true" style="width:100%;height: 120px;" data-options="
                    panelWidth: 500,
                    multiple: true,
                    idField: 'user_id',
                    textField: 'name',
                    url: '<?=site_url("users/geUserJsonComboGrid");?>',
                    method: 'get',
                    
                    columns: [[
                        {field:'name',title:'Name',width:120},
                       {field:'status',title:'Status',width:60,align:'center'}
                    ]],
                    fitColumns: true,
                    label: 'Users:',
                    labelPosition: 'left'
                ">
            </select>
            </div>
            
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
                          
                $('#dlg').dialog('open').dialog('center').dialog('setTitle','New Course');
                $('#fm').form('clear');
                url = '<?php echo site_url("group/save");?>';
        
            }
            function edit(){
                var row = $('#dg').datagrid('getSelected');
                if (row){
                    
                    $('#dlg').dialog('open').dialog('center').dialog('setTitle','Edit Course');
                    //var new_users = row.users.replace(" ","").split(" ");
                    var users = row.users.split(",");

                   $(".easyui-combogrid").combogrid('setValue',users);
                    $('#fm').form('load',row);
                    url = '<?php echo site_url("group/save");?>'+"?group_id="+row.group_id;
                }
            }
            function save(){
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
                            $('#dg').treegrid('reload');
                        } else {
                            $.notify({
                                icon: 'fa fa-check',
                                message: "Item Saved"
                            },{
                                type: 'success'
                            });
                            $('#dlg').dialog('close');        // close the dialog
                            $('#dg').treegrid('reload');    // reload the user data
                            $('#fm').form('clear');
                        }
                    }
                });
            }
            function search(){
                     
                
               
                    $("#dg").treegrid('load',{
                        group :$("#search_").val(),
                        //description:$("#search_").val(),
                        
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
            var dg = $('#dg').treegrid({
                url: '<?=site_url("group/getGroupJson");?>',
                pagination: true,
                clientPaging: false,
                remoteFilter: true,
                rownumbers: false,
                method:'post',
                toolbar:toolbar,
                singleSelect:true,
                idField:'group_id',
                treeField:'group_name'
            });
         
            $(".new").click(function(){
				newItem();
			});
			 $(".edit").click(function(){
				 var row = $('#dg').treegrid('getSelected');
                    if (row){                      
                       
                           edit();
                        
                    }else{
                        $.messager.alert('Warning','Please Select item First!','error');
                    }
			});
			  $(".delete").click(function(){
				 var row = $('#dg').treegrid('getSelected');
                    if (row){                      
                       
                        $.messager.confirm('Confirmation', 'Are you confirm to delete this?', function(r){
                            if (r){
                               $.post("<?=site_url('group/delete');?>",{group_id:row.group_id},function(response){
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
                                $('#dg').treegrid('reload');
                                
                            }
                        });
                        
                    }else{
                        $.messager.alert('Warning','Please Select item First!','error');
                    }
			});


        });
          
        </script>


