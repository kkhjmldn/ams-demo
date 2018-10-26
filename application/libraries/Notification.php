<?php

	/* ================================ 

			NOTIFICATION LIBRARY V1.0
			
	==================================

	Note:
	a. ...


	*/

defined('BASEPATH') OR exit('No direct script access allowed');

Class Notification {

	protected $CI;
	protected $js;
	protected $css;

	public function __construct()
    {
    	
    	$this->CI =& get_instance();
    	$this->db = $this->CI->load->database();
        $this->CI->load->library(array("session"));
    	

    }

    public function save_notification($user_id,$time,$notification,$link)
    {
    	$data = array(
    		"notification_id" => "NOT".date("YmdHis"),
    		"user_id"			=> $user_id,
    		"time"				=> $time,
    		'notification' 	=> $notification,
    		'link'		=> $link,
    		"is_read"	=> 0,
    	);

    	//$this->db->insert("ams_notifications a",$data);
        if($this->CI->db->insert("ams_notifications",$data)):
            $this->CI->load->library(array("session"));
            ?>
            <script src="<?php echo base_url('node_modules/socket.io/node_modules/socket.io-client/socket.io.js');?>"></script>
            <script type="text/javascript">
                var socket = io.connect( 'http://'+window.location.hostname+':3000' );
                socket.emit('new_notifikasi', { 
                  deskripsi: "<?php echo $data['notification'];?>",
                  waktu: "<?php echo $data['time'];?>",
                  link: "<?php echo $data['link'];?>",
                  dari: "<?php echo $this->CI->session->userdata('username');?>",
                  target : "<?php echo $data['user_id'];?>"
                 });
                socket.emit('update_count_notifikasi', { 
                  update_count_notifikasi: 1,
                  target : "<?php echo $data['user_id'];?>"
              });
                
            </script>

        <?php
       
        endif;
    }

    public function read_notification($notification_id)
    {
        return $this->CI->db->where("a.notification_id",$notification_id)->update("ams_notifications a",array("a.is_read"=>1));
    }

    public function getAllNotifications()
    {
        $sql = $this->CI->db->where("a.user_id",$this->CI->session->userdata("user_id"))->order_by("a.time","DESC")->get("ams_notifications a");
        $result = array(
            'data' => $sql->result(),
            'total' => $sql->num_rows()
        );
        return $result;
    }

    public function getAllUnreadNotifications()
    {
        $sql = $this->CI->db->where(array("a.user_id"=>$this->CI->session->userdata("user_id"),"a.is_read"=>0))->order_by("a.time","DESC")->get("ams_notifications a");
        $result = array(
            'data' => $sql->result(),
            'total' => $sql->num_rows()
        );
        return $result;
    }


    public function  notification_success($type)
    { ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css');?>">
        <script type="text/javascript" src="<?php echo base_url('assets/jquery/jquery.min.js');?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.js');?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/notify/bootstrap-notify.js');?>"></script>
        <script type="text/javascript">
            $(function(){      
                if("<?php echo $type;?>" == "success"){
                 $.notify({
                    icon: 'fa fa-check',
                    message: "Process succeed"
                    },{
                        type: 'success'
                    });
                }else{
                    $.notify({
                        icon: 'fa fa-close',
                        message: "Process Failed"
                    },{
                        type: 'danger'
                    });
                }
            });

        </script>

    <?php }
}

?>