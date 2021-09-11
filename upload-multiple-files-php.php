<form action="#" id="submit_all_image" method="post" enctype="multipart/form-data">
    <div class="modal-content ty-post-bg">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title" id="createpost_title">Create Post</h4>
      </div>
      <div class="modal-body ty-post-createpop">
     
	  <div class="ty-post-create">
	  <div class="ty-post-create-user">
	  <img class="online-user" src="https://tynelz.com/assets/upload/images/usersimage/1623829767.png" alt="1623829767.png" title="1623829767.png">
	  </div>
	  <div class="ty-textarea" id="ty-textarea">
	  <select name="post_status" id="post_status" class="setting-search">
<option value="2">Public</option>
<option value="1">Only Me</option> 
</select>
<style>
.setting-search {
    border-radius: 5px;
    padding: 3px 5px;
    border: 1px solid #fff;
    background: #25272c;
    color: #fff;
    outline: none;
    cursor: pointer;
    width: 90px;
}
</style>
	  <textarea rows="1" name="content" class="scrollbar" id="contentbox" onkeyup="get_link_preview1();" placeholder="What is on your mind, Samurai?" style="height: 15px; overflow: auto;"></textarea>
	  <input type="hidden" id="userprofiledata_id" name="userprofiledata_id" value="109">
	  <input type="hidden" id="post_idd" name="post_idd">
	  <input type="hidden" id="post_idd_edit" name="post_idd_edit">
	  <input type="hidden" id="link-previews-data" name="link-previews-data">
	  <div id="fetch_url"></div>
	  </div>
	  
	  <div class="ty-uploaded">
	 <span class="fileinput-button">
            <span class="sprites" style="display:inline-block;"></span>
            <input type="file" name="image" id="show_image" onchange="readAndShowFiles()" multiple="" accept="image/jpeg, image/png, image/gif,">
        </span>
	
	  </div>
	  
	  </div>
	  <div class="row" id="show_image_hare"></div>
	  <div id="Filelist" class="ty-uploadedtext"></div>
	  
	</div>
	
	<div class="modal-footer post-create_bottom">
	<p id="creat_post_msg_error2" style="text-align: left; color: red; font-size: 16px; display: none;">Maximum 10 photos in one post</p>
	<p id="creat_post_msg_error3" style="text-align: left; color: red; font-size: 16px; display: none;">Something write a post</p>
        <button type="submit" name="create_post" id="create_post" class="post_btn">Post</button>
		 <button type="button" name="create_process" id="create_process" class="post_btn" style="display: none;">Please wait...</button>
      </div>
      
    </div>
</form>


// script

<script>
// multi images selection with feed post

$('#creat_post_msg_error2').hide();
$('#creat_post_msg_error3').hide();


$(document).on("click",'.remove_div',function(){

      $(this).closest(".add_class").remove();
});



$(document).on("submit","#submit_all_image",function(){
   event.preventDefault();
     $(".se-pre-con").css("display","block");
 
 var iptCount= $("input[name='upload_Image[]']").length;
    
if(iptCount <='10'){	
$.ajax({
               method:"POST",
               url:"https://tynelz.com/submit_post_feed",
               data:$('#submit_all_image').serializeArray(),
               dataTyep:"json",
               success:function(data){
				   
                  $(".se-pre-con").css("display","none");
                 if(data=='1'){
					 
					  location.reload();
				 }
				 if(data=='2'){
					 
					$('#creat_post_msg_error2').show();
					$('#creat_post_msg_error3').hide();
									$('#create_post').show();
									$('#create_process').hide();
					
				 }
				 if(data=='3'){
					 
					
					$('#creat_post_msg_error2').hide();
					$('#creat_post_msg_error3').show();
									$('#create_post').show();
									$('#create_process').hide();
							
				 }
                   
               },
			   beforeSend:function(d){
									$('#create_post').hide();
									$('#create_process').show();
									$('#creat_post_msg_error2').hide();
									$('#creat_post_msg_error3').hide();
								}
});
}
else{
	$('#creat_post_msg_error2').show();
					$('#creat_post_msg_error3').hide();
									$('#create_post').show();
									$('#create_process').hide();
}

});

// end multi image selection
</script>


<?php 
// controller


// post a feed

function submit_post_feed()
	{
		
		$data = array();
        $userData = array();
        $data = array();
		date_default_timezone_set('Asia/Calcutta');
		$reg_date = date('Y-m-d h:i:sa');
		$cdate = date('Ymdhisa');
        $user = $this->web_model->getRows(array('id'=>$this->session->userdata('userId')));
        $content =  str_replace('\n', '<br>', $this->input->post('content')); 
	    $link_previews_data =  $this->input->post('link-previews-data');
		
		$post_idd =  $this->input->post('post_idd');
		$userprofiledata_id =  $this->input->post('userprofiledata_id');
		$post_idd_edit =  $this->input->post('post_idd_edit');
		$post_status =  $this->input->post('post_status');
	    
        	
		$check_privacy = $this->web_model->check_user_privacy_settings($userprofiledata_id);
		
		 
		$user_details = $this->web_model->get_user_details($userprofiledata_id);
		
		var_dump($_POST["upload_Image"]);
		
		if(count($_POST["upload_Image"]) <=10 ){
			
			if($post_idd ==''){
				
					if ($content =='' && count($_POST["upload_Image"]) ==0)
					{   
						echo '3';
						 
					}
					else
					{	
				
						$postData = array(
									'type' => 'Feed',
									'content' => $content.'<br>'.$link_previews_data,
									'contentimg' => 'logo.svg',
									'uid' => $user_details[0]['id'],
									'game_type' => $user_details[0]['gamecate'],
									'create_date' => $reg_date,
									'post_status' => $post_status,
									'cdate' => $cdate,
								);
				   
						$pid = $this->web_model->save_post($postData);
						
						if(count($check_privacy)>0) 
							{ 
								if($check_privacy[0]['post_on_my_page'] ==2) { 
									
									$privacypostData = array( 
												'uid' => $user['id'],
												'uid_mypage' => $userprofiledata_id,
												'post_id' => $pid,
												'cdate' => $reg_date,
											);
				   
								$this->web_model->save_privacypostData($privacypostData);
						
								}
								else{
									$post_on_mypage = ' style="display:none";';
								}
						
						}
					
							for($i=0; $i<count($_POST["upload_Image"]); $i++){
							
							
							$image_parts = explode(";base64,", $_POST["upload_Image"][$i]);
							$image_type_aux = explode("image/", $image_parts[0]);
							$image_type = $image_type_aux[1];
							$image_base64 = base64_decode($image_parts[1]);
							$filename = uniqid().'.'. $image_type;
							$filepath = './assets/upload/images/post-gallery/'.$filename;
							file_put_contents($filepath, $image_base64);	
										
									 $uploadImgData[$i]['image'] = $filename;
									 $uploadImgData[$i]['pid'] = $pid;
									 $uploadImgData[$i]['uid'] = $user_details[0]['id'];
							if($image_type !='gif'){
								$this->_create_thumbs_product($filename);
							}	
								else{
									$filepath = './assets/upload/images/post-gallery/large/'.$filename;
									file_put_contents($filepath, $image_base64);
									$filepath1 = './assets/upload/images/post-gallery/small/'.$filename;
									file_put_contents($filepath1, $image_base64);
								}
								
							   }
							if(!empty($uploadImgData)){
								
								$uploadimg =	$this->web_model->add_post_gallery($uploadImgData);     
								 
								
							} 
							  echo '1';
					}
				}
				else{
				
						$postData = array( 
									'content' => $content.'<br>'.$link_previews_data, 
									'create_date' => $reg_date,
									'cdate' => $cdate,
								);
				   
				   $pid = $this->web_model->edit_post($postData,$post_idd);
				   
				   $pid_details = $this->feed_model->get_feed_detail_byid($post_idd);
				   
				   if($post_idd_edit == 'edit'){
					
					$postData_edit = array( 
								'u_id' => $user['id'],
								'post_id' => $post_idd,
								'e_date' => $pid_details[0]['create_date'], 
							);
			   
					$pid = $this->web_model->save_post_edit($postData_edit);
					
				}
					
					
					for($i=0; $i<count($_POST["upload_Image"]); $i++){
							
							
							$image_parts = explode(";base64,", $_POST["upload_Image"][$i]);
							$image_type_aux = explode("image/", $image_parts[0]);
							$image_type = $image_type_aux[1];
							$image_base64 = base64_decode($image_parts[1]);
							$filename = uniqid().'.'. $image_type;
							$filepath = './assets/upload/images/post-gallery/'.$filename;
							file_put_contents($filepath, $image_base64);	
										
									 $uploadImgData[$i]['image'] = $filename;
									 $uploadImgData[$i]['pid'] = $post_idd;
									 $uploadImgData[$i]['uid'] = $user['id'];
							if($image_type !='gif'){
								$this->_create_thumbs_product($filename);
							}	
								else{
									$filepath = './assets/upload/images/post-gallery/large/'.$filename;
									file_put_contents($filepath, $image_base64);
									$filepath1 = './assets/upload/images/post-gallery/small/'.$filename;
									file_put_contents($filepath1, $image_base64);
								}
								
							   }
							if(!empty($uploadImgData)){
								
								$uploadimg =	$this->web_model->add_post_gallery($uploadImgData);     
								 
								
							} 
					
				  echo '1';
				}
            

			}	
			else{
				echo '2';
				/* $validator['success'] = false;
				$validator['messages'] = 'max 10 photos in one post'; */
				
			}
				
           
        
		//echo json_encode($validator);   
		 
		
	}
// end post feed


function _create_thumbs_product($file_name){
         
        $config = array( 
		 
            array(
                'image_library' => 'GD2',
                'source_image'  => './assets/upload/images/post-gallery/'.$file_name,
                'maintain_ratio'=> false, 
                'width'         => 250,
                'height'        => 250,
                'new_image'     => './assets/upload/images/post-gallery/small/'.$file_name
            ),
			
			array(
                'image_library' => 'GD2',
                'source_image'  => './assets/upload/images/post-gallery/'.$file_name,
                'maintain_ratio'=> true,
                'width'         => 590,/* 
                'height'        => 210, */
                'new_image'     => './assets/upload/images/post-gallery/large/'.$file_name
            ));

        $this->load->library('image_lib', $config[0]);
        foreach ($config as $item){
            $this->image_lib->initialize($item);
            if(!$this->image_lib->resize()){
                return false;
            }
            $this->image_lib->clear();
        }
}
  
  ?>