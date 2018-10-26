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
                
            </div>
            

          </div>
           
            <!-- Content Here  -->
            	<div class="row">
            		<div class="col-lg-3 ">
            			<div class="easyui-panel" title="Courses" style="height: 400px;">
            			 <ul id="courses" class="easyui-tree" data-options="
                    		url:'<?=site_url();?>/courses/getCourseJsonTree',
		                    method:'get',
		                    animate:true,
		                    formatter:function(node){
		                        var s = node.text;
		                        if (node.children){
		                            s += '&nbsp;<span style=\'color:blue\'>(' + node.children.length + ')</span>';
		                        }
		                        return s;
		                    }
		                ">
		               
		            </ul>
		        </div>

            		</div>
            		<div class="col-lg-9">
            			<div class="easyui-panel" title="Assignments" id="assignment_container" style="height: 400px;padding: 20px;">
            				
            			</div>
            		</div>
            	</div>

            <!-- end content -->
          </div>
        </div>
      </div>


       <div id="dlg" class="easyui-dialog" style="width:700px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
	        <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px" enctype="multipart/form-data">
	        	<input type="hidden" name="assignment_id" id="assignment_id" >
	           <div style="margin-bottom:10px">
	                 <input name="answer" class="easyui-textbox" multiline="true" label="Answer:" style="width:100%;height: 120px;">
	            </div>
	            <div style="margin-bottom:10px">
	                <input name="file" class="easyui-filebox" multiline="true"  label="File:" style="width:50%;">
	            </div>
	            
	        </form>
	    </div>
	    <div id="dlg-buttons">
	        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save()" style="width:90px">Save</a>
	        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	    </div>


  <script type="text/javascript">
  	$(document).ready(function(){

  			$('#courses').tree('expandAll');
  			$('#courses').tree({
					onClick: function(node){
						var id = node.id;
						$.post("<?=site_url('assignment/getAssignmentJsonTree');?>",{course_id:id},function(e){
							 $("#assignment_container").empty();
							 $("#assignment_container").append("<table  id='assignment_tree_container' class='easyui-datagrid' ></table>");
							var res = JSON.parse(e);
							$.each(res,function(k,v){
								$("#assignment_tree_container").append(v.tree_node);
							});
							
						});

						
					
					}
				});
	$.post("<?=site_url('assignment/getAssignmentJsonTree');?>",{course_id:'all'},function(e){
				
							 $("#assignment_container").empty();
							 $("#assignment_container").append("<table  id='assignment_tree_container' class='easyui-datagrid' ></table>");
							var res = JSON.parse(e);
							$.each(res,function(k,v){
								$("#assignment_tree_container").append(v.tree_node);
							});
							
						});

		

		$('#assignment_tree_container').datagrid();

  	});

  	function load_assignment(assignment_id)
  	{
  		$.post("<?=site_url('assignment/getAssignmentJsonById');?>",{assignment_id:assignment_id },function(e){
			 $("#assignment_container").empty();
			 
			var res = JSON.parse(e);
			$.each(res.data,function(k,v){
				$("#assignment_container").append(v.assignment_loader);
			});
		});
  	}
  	function load_assignment_answer(assignment_answer_id)
  	{
  		$.post("<?=site_url('assignment/getAssignmentAnswerJsonById');?>",{assignment_answer_id:assignment_answer_id },function(e){
			 $("#assignment_container").empty();
			 
			var res = JSON.parse(e);
			$.each(res.data,function(k,v){
				$("#assignment_container").append(v.assignment_loader);
			});
		});
  	}

  	/*function load_form_answer()
  	{
  		var form = '<hr /><table width="100%" ><form id="fm" method="post" enctype="multipart/form-data">'+'<tr><td valign="top" width="100%"  ><b>Answer</b></td><td><b>:</b></td><td><input name="description" class="easyui-textbox" multiline="true" required="true" label="Description:" style="width:400px;height: 120px;"></td></tr><tr><td valign="top" width="100%"  ><b>Answer</b></td><td><b>:</b></td><td><input class="easyui-filebox" /></td></tr>'+
	               '</form>'+
	            '</table><hr />';
  		$("#form_answer_loader").append(form);
  	}*/
  	function load_form_answer(assignment_id)
    {
        //alert(assignment_id);
        $(document).ready(function(){
	    	$('#assignment_id').val(assignment_id);
	    })  ;  
        $('#dlg').dialog('open').dialog('center').dialog('setTitle','Answer Assignment');
        $('#fm').form('clear');
        url = '<?php echo site_url("assignment/save");?>';

    }
    function edit(){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','Edit role');
            $('#fm').form('load',row);
            url = '<?php echo site_url("assignment/save");?>'+"?assignment_id="+row.assignment_id;
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
                console.log(result);
                if (result=="failed"){
                    $.notify({
                        icon: 'fa fa-close',
                        message: "Failed Submitting Answer"
                    },{
                        type: 'danger'
                    });
                } else {
                    $.notify({
                        icon: 'fa fa-check',
                        message: "Answer Submitted"
                    },{
                        type: 'success'
                    });
                    $('#dlg').dialog('close');        // close the dialog
                    $('#dg').datagrid('reload');    // reload the user data
                    $('#fm').form('clear');
                    window.location.reload();
                }
            }
        });
    }

  </script>