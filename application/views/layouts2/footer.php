  </div>
                <button class="btn btn-primary btn-round btn-floating btn-lg" type="button" data-toggle="collapse" data-target="#floating-chat" aria-expanded="false" aria-controls="sidebar-floating-chat">
                    <i class="fa fa-comment"></i>
                    <i class="fa fa-close"></i>
                </button>
                <div class="collapse sidebar-floating" id="floating-chat">
                    <div class="sidebar-content">
                        <div class="chat-module" data-filter-list="chat-module-body">
                            <div class="chat-module-top">
                                <form>
                                    <div class="input-group input-group-round">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-search"></i>
                                            </span>
                                        </div>
                                        <input type="search" class="form-control filter-list-input" placeholder="Search User" aria-label="Search User" aria-describedby="search-chat">
                                    </div>
                                </form>
                                <div class="chat-module-body filter-list-1540358989503">
                                    <?php 
                                    $this->load->model("general_model");
                                    $data_users= $this->general_model->getdata("ams_users","role_id <> 1");?>
                                    <ul class="list-group">
                                            <?php 
                                            if(isset($data_users)){
                                                foreach ($data_users['data'] as $key => $value) {?>
                                                  
                                                <li class="list-group-item"><a href="<?=site_url('messages');?>#<?=$value->user_id;?>" class="userlist" data-id='<?=$value->user_id;?>'> <img alt="Peggy" src="<?=base_url();?>assets/img/user.jpg" class="avatar">
                                                    <span class="chat-item-author SPAN-filter-by-text" data-filter-by="text"><?=$value->name;?></span>
                                                    </a></li>
                                                <?php
                                            }

                                        }?>
                                            </ul>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>

            </div>
        </div>

        

        <style type="text/css">
            .layout-switcher{ position: fixed; bottom: 0; left: 50%; transform: translateX(-50%) translateY(73px); color: #fff; transition: all .35s ease; background: #343a40; border-radius: .25rem .25rem 0 0; padding: .75rem; z-index: 999; }
            .layout-switcher:not(:hover){ opacity: .95; }
            .layout-switcher:not(:focus){ cursor: pointer; }
            .layout-switcher-head{ font-size: .75rem; font-weight: 600; text-transform: uppercase; }
            .layout-switcher-head i{ font-size: 1.25rem; transition: all .35s ease; }
            .layout-switcher-body{ transition: all .55s ease; opacity: 0; padding-top: .75rem; transform: translateY(24px); text-align: center; }
            .layout-switcher:focus{ opacity: 1; outline: none; transform: translateX(-50%) translateY(0); }
            .layout-switcher:focus .layout-switcher-head i{ transform: rotateZ(180deg); opacity: 0; }
            .layout-switcher:focus .layout-switcher-body{ opacity: 1; transform: translateY(0); }
            .layout-switcher-option{ width: 72px; padding: .25rem; border: 2px solid rgba(255,255,255,0); display: inline-block; border-radius: 4px; transition: all .35s ease; }
            .layout-switcher-option.active{ border-color: #007bff; }
            .layout-switcher-icon{ width: 100%; border-radius: 4px; }
            .layout-switcher-body:hover .layout-switcher-option:not(:hover){ opacity: .5; transform: scale(0.9); }
            @media all and (max-width: 990px){ .layout-switcher{ min-width: 250px; } }
            @media all and (max-width: 767px){ .layout-switcher{ display: none; } }
        </style>
      
    


<div id="draggable-live-region" aria-relevant="additions" aria-atomic="true" aria-live="assertive" role="log" style="position: fixed; width: 1px; height: 1px; top: -1px; overflow: hidden;"></div><input type="file" multiple="multiple" class="dz-hidden-input" style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;">
    </body>
</html>
