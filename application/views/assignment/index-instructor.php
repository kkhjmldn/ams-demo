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
                <a class="btn btn-round new" onclick="newAssignment()">
                    <i class="fa fa-plus"></i>
                </a>
                &nbsp;
                <span class="badge badge-success">7 New Answers</span>
                <!-- <button class="btn btn-round edit" >
                    <i class="fa fa-pencil"></i>
                </button>
                <button class="btn btn-round delete" >
                    <i class="fa fa-trash"></i>
                </button> -->
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


       <div id="dlg" class="easyui-dialog" style="width:700px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
	        <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px" enctype="multipart/form-data">
	        	<input type="hidden" name="assignment_id" id="assignment_id" >
             <div style="margin-bottom:10px">
                   <input name="name" class="easyui-textbox"  required="false" placeholder="Assignment Name" label="Assignment Name:" style="width:100%;">
              </div>
              <div style="margin-bottom:10px">
                   <input id="course_id" class="easyui-combobox" required="" name="course_id"  data-options="
                    valueField: 'course_id',
                    textField: 'course',
                    label: 'Course Name:',
                    labelPosition: 'Left'
                    ">
              </div>
              <div style="margin-bottom:10px">
                   <select id="personel_id" class="easyui-combogrid" required name="student_id[]" multiline="true" style="width:100%;" data-options="
                    panelWidth: 500,
                    multiple: true,
                    idField: 'user_id',
                    textField: 'name',
                    url: '<?=site_url("users/getStudentJsonComboGrid");?>',
                    method: 'get',
                    
                    columns: [[
                        {field:'name',title:'Name',width:120},
                       {field:'status',title:'Status',width:60,align:'center'}
                    ]],
                    fitColumns: true,
                    label: 'Assignt To:',
                    labelPosition: 'left'
                ">
            </select>
              </div>
	           <div style="margin-bottom:10px">
	                 <input name="content" class="easyui-textbox" multiline="true"  label="Assignment Detail:" style="width:100%;height: 120px;">
	            </div>
              <div style="margin-bottom:10px">
                   <input name="minimum_grade" class="easyui-textbox"  required="false" label="Minimum Grade:" style="width:100%;">
              </div>
              <div style="margin-bottom:10px">
                   <input name="end_time" class="easyui-datetimebox"  required="false" label="End Time:" style="width:100%;">
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
    var url;
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
  		
			var cb = $("#course_id").combobox("reload","<?=site_url('courses/getCourseJSONComboBox');?>");

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

    function newAssignment(){
        
       
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','New Assignment');
            $('#fm').form('clear');
            url = '<?php echo site_url("assignment/add");?>';
        
    }

    function edit(){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','Edit role');
            $('#fm').form('load',row);
            url = '<?php echo site_url("assignment/add");?>'+"?assignment_id="+row.assignment_id;
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
                        message: "Assignment Submitted"
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