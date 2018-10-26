<!doctype html>
<html>
    <head>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script><script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-52115242-14"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
            
              gtag('config', 'UA-52115242-14');
        </script>
        <meta charset="utf-8">
        <title>Assignment Management System - </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A project management Bootstrap theme by Medium Rare">
        <link href="<?=base_url();?>assets/img/favicon.ico" rel="icon" type="image/x-icon">
        <link href="<?=base_url();?>assets/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
        <link href="<?=base_url();?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all">
        
        <link href="<?=base_url();?>assets/css/theme.css" rel="stylesheet" type="text/css" media="all">
          <?php if(isset($css)){
            foreach ($css as $key => $value) {?>
              <link rel="stylesheet" type="text/css" href="<?=$value;?>">
        <?php }
        }
        ?> 
        <script type="text/javascript" src="<?=base_url();?>assets/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/js/autosize.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/popper/popper.min.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/js/prism.min.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/js/draggable.bundle.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/js/swap-animations.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/dropzone/dropzone.min.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/js/list.min.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?=base_url();?>assets/js/theme.js"></script>
        <script src="<?php echo base_url('node_modules/socket.io/node_modules/socket.io-client/socket.io.js');?>"></script>


        <?php if(isset($js)){
            foreach ($js as $key => $value) {?>
               <script type="text/javascript" src="<?=$value;?>" ></script> 
        <?php }
        }
        ?> 

    </head>
    <script type="text/javascript">
        
        $(document).ready(function(){
            var socket = io.connect( 'http://'+window.location.hostname+':3000' );
        socket.on('new_notifikasi', function(data) { 
          if("<?php echo $this->session->userdata('user_id');?>" ==data.target){
           PNotify.desktop.permission();
            (new PNotify({
                title: 'New Notification(s)' ,
                type: 'info',
                text: /*data.dari+' : '+*/data.deskripsi+' pada '+data.waktu,
                desktop: {
                    desktop: true,
                    icon: 'assets/images/pnotify/info.png'
                }
            })
            ).get().click(function(e) {
                if ($('.ui-pnotify-closer, .ui-pnotify-sticker, .ui-pnotify-closer *, .ui-pnotify-sticker *').is(e.target)) return;
                alert('Hey! You clicked the desktop notification!');
            });

            $("#get_notifikasi").prepend('<li class="media">'+
                '<a href="'+data.link+'">'+
                '<div class="media-body">'+
                  '<a href="'+data.link+'" class="media-heading">'+
                    
                    '<span class="media-annotation pull-right">'+data.waktu+'</span>'+
                    '<span class="text-muted"><p><b>'+data.deskripsi+'</b></p></span>'+
                  '</a> '+                 
                '</div>'+
              '</a>'+
              '</li>');
          }
          
        });
         socket.on('update_count_notifikasi', function(data) { 
          if("<?php echo $this->session->userdata('user_id');?>" ==data.target){
            var jml_notifikasi = parseInt($("#notifikasi").text());
            jml_notifikasi =  jml_notifikasi+data.update_count_notifikasi;
            $("#notifikasi").text(jml_notifikasi);
          }
         });

         $.post("<?php echo site_url('messages/getUnreadMessages');?>",{receiver_id:"<?=$this->session->userdata('user_id');?>"},function(resp){
            var res = JSON.parse(resp);
            $("#unread_messages").text(res.total);
             
        });



        });

    </script>
    <body>

        <div class="layout layout-nav-side">
            <div class="navbar navbar-expand-lg bg-dark navbar-dark sticky-top">
                <a class="navbar-brand" href="<?=site_url();?>" align="center">
                    <img alt="AMS" src="<?=base_url();?>assets/img/ams-320px.png"width="50%" >
                </a>
                <div class="d-flex align-items-center">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fa fa-list"></span>
                    </button>
                   
                </div>
                <div class="collapse navbar-collapse flex-column" id="navbar-collapse">
                    <ul class="navbar-nav d-lg-block">

                        <li class="nav-item">

                            <a class="nav-link" href="<?=site_url('app/dashboard');?>">Dashboard</a>

                        </li>

                       
                        <?php if($this->session->userdata('role_id')==1){ ?>
                        <li class="nav-item">

                            <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-master" aria-controls="submenu-2">Masters</a>
                            <div id="submenu-master" class="collapse">
                                <ul class="nav nav-small flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?=site_url('roles');?>">Roles</a>
                                    </li>
                                    

                                    <li class="nav-item">
                                        <a class="nav-link" href="<?=site_url('categories');?>"> Categories</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="<?=site_url('courses');?>">Courses</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="<?=site_url('group');?>">Groups</a>
                                    </li>

                                </ul>
                            </div>

                        </li>

                     
                         <li class="nav-item">
                                        <a class="nav-link" href="<?=site_url('users');?>">Users</a>
                                    </li>
                        <?php } ?>
                        <li class="nav-item">

                            <a class="nav-link" href="<?=site_url('assignment');?>">Assignments</a>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link" href="<?=site_url('schedule');?>">Schedules</a>

                        </li>

                       <!--  <li class="nav-item">

                            <a class="nav-link" href="documentation/changelog.html">User's Log</a>

                        </li> -->

                    </ul>
                    <hr>
                  <!--   <div class="d-none d-lg-block w-100">
                        <span class="text-small text-muted">Quick Links</span>
                        <ul class="nav nav-small flex-column mt-2">
                            <li class="nav-item">
                                <a href="<?=site_url('assignment');?>" class="nav-link">Add Assignment</a>
                            </li>
                           
                        </ul>
                        <hr>
                    </div> -->
                   
                </div>
               
            </div>
            <div class="main-container">

                <div class="navbar bg-white breadcrumb-bar">
                        <form >
                            <div class="input-group input-group-dark input-group-round"  >
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="background:gray;">
                                        <i class="fa fa-search"></i>
                                    </span>
                                </div>
                                <input type="search" style="background:gray;"class="form-control form-control-dark" placeholder="Search" aria-label="Search app" aria-describedby="search-app">
                            </div>
                        </form>
                     <div class="dropdown">
                        <button class="btn  btn-light" role="button" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-bell"></i> Notifications <span class="badge badge-success" id="notifikasi">0</span>
                        </button>
                         <div class="dropdown-menu dropdown-menu-right">
                            
                          
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="<?=site_url('notifications');?>">See All Notifications</a>
                        </div>
                    </div>
                     <div class="dropdown">
                         <button class="btn btn-light" role="button" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-envelope"></i> Messages <span class="badge badge-success">0</span>
                        </button>
                         <div class="dropdown-menu dropdown-menu-right">
                            
                          
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="<?=site_url('messages');?>">See All Messages</a>
                        </div>
                    </div>
                   
                    <div class="dropdown">
                        <img src="<?=base_url();?>assets/img/user.jpg" class="avatar">
                        <button class="btn btn-light" role="button" data-toggle="dropdown" aria-expanded="false">
                             <span><?php if($this->session->userdata('username')!=NULL) {echo $this->session->userdata('username');}else{ echo "Username";}?></span> <i class="fa fa-gear"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#project-edit-modal">Change Password</a>
                            <a class="dropdown-item" href="#">My Profile</a>
                           
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="<?=site_url('app/logout');?>"><i class="fa fa-logout"></i> Logout</a>




                        </div>
                    </div>

                </div>


                <div class="container"> 



