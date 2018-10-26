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
        <?php if(isset($js)){
            foreach ($js as $key => $value) {?>
               <script type="text/javascript" src="<?=$value;?>" ></script> 
        <?php }
        }
        ?> 

    </head>
    <body>

        <div class="layout layout-nav-side" >
            
            <div class="main-container" style="min-width:100%;">
                
                


                <div class="container"> 
                </div>
            </div>
        </div>
    </body>
    </html>



