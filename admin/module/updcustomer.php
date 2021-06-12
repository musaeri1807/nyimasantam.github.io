<?php
// // ini_set('display_errors', 0);
date_default_timezone_set('Asia/Jakarta');
require_once("../config/connection.php");
require_once("../php/function.php");

if(!isset($_SESSION['userlogin'])) {
    header("location: ../index.php");
}


if(isset($_REQUEST['id']))
{
	try
	{
		$id = $_REQUEST['id']; //get "update_id" from index.php page through anchor tag operation and store in "$id" variable
		$select_stmt = $db->prepare("SELECT * FROM  tblcustomer n JOIN tbluserlogin u ON n.field_member_id=u.field_member_id  WHERE field_customer_id =:id "); //sql select query		
		$select_stmt->bindParam(':id',$id);
		$select_stmt->execute(); 
		$row = $select_stmt->fetch(PDO::FETCH_ASSOC);
		//extract($row);
		$idcabang=substr($row["field_member_id"], 0,10);

	}
	catch(PDOException $e)
	{
		$e->getMessage();
	}
	
}

//echo $idcabang;
$select_stmt = $db->prepare("SELECT * FROM  tblbranch WHERE field_branch_id =:id "); //sql select query
$select_stmt->bindParam(':id',$idcabang);
$select_stmt->execute(); 
$rows = $select_stmt->fetch(PDO::FETCH_ASSOC);


$Sql ="SELECT * FROM tblbranch";
$Stmt = $db->prepare($Sql);
$Stmt->execute();
$result = $Stmt->fetchAll();
//extract($row);                

if(isset($_REQUEST['btn_update']))
{
	$id;	
	$Nama		= strip_tags($_REQUEST['txt_firstname']);	
	$nama   	= ucwords($Nama);	
	$email		= strip_tags($_REQUEST['txt_email']);	
	//$password 	= password_generate(8);
	$angka		= strip_tags($_REQUEST['txt_angka']);	
	$date		= date('Y-m-d');
	$time		= date('H:i:s');
	$random 	= (rand(10000,100));
	$tokenn		= hash('sha256', md5(date('Y-m-d h:i:s'))) ;
	$cabang		= $_REQUEST['txt_cabang'];	
	$member_id	= $cabang.$angka;
	$ipaddress 	= $_SERVER['REMOTE_ADDR'];
	$status 	= $_REQUEST['txt_status'];

		
	

	if(empty($nama)){
		$errorMsg="Silakan Memasukan Nama Anda";	 
	}
	else if(empty($email)){
		$errorMsg="Silakan Memasukan Email Anda";	
	}
	else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$errorMsg="Silakan Memasukan Alamat Email yang Valid";	 
	}
	
	else if(strlen(is_numeric($angka))==0){
		$errorMsg = "Silakan Memasukan Angka";			
	}	
	else if(strlen($angka)< 10){
		$errorMsg = "Nomor Hp Tidak Sesuai";	
	}
	else if (strlen($angka) > 12) {
		$errorMsg = "Nomor Hp Terlalu Panjang";		

	}elseif ($cabang=="Pilih") {
		$errorMsg="Silakan Pilih Kantor Cabang";
	}
	else
	{
		try
		{
			$select_stmt=$db->prepare("SELECT field_email,Field_handphone  FROM tbluserlogin 
										WHERE field_email=:uemail OR Field_handphone=:only" ); // sql select query			
			$select_stmt->execute(array(':uemail'	=>$email,
										':only'		=>$angka
										)); //execute query 
			$row=$select_stmt->fetch(PDO::FETCH_ASSOC);	
			
			if($row["field_email"]!==$email){
				$errorMsg="Maaf Email Sudah Ada";	//check condition email already exists 
			}
			else if($row["Field_handphone"]!==$angka){
				$errorMsg="Maaf Nomor Hp Sudah Ada";	//check condition email already exists 
			}			

			elseif(!isset($errorMsg))
			{
				
				$update_stmt=$db->prepare('UPDATE tblcustomer SET field_document=:statuse WHERE field_customer_id=:id');
				$update_stmt->bindParam(':id',$id);
				// $update_stmt->bindParam(':idemployee',$idemployee);
				// $update_stmt->bindParam(':namaemployee',$name); 
				// $update_stmt->bindParam(':username',$username);  
				// $update_stmt->bindParam(':urole',$role);   
				// $update_stmt->bindParam(':udate',$date); 
				// $update_stmt->bindParam(':cabang',$cabang);
				// $update_stmt->bindParam(':umail',$email);
				// $update_stmt->bindParam(':upassword',$password);
				// $update_stmt->bindParam(':passwordnew',$new_password);
				//$update_stmt->bindParam(':token',$tokenn); 
				$update_stmt->bindParam(':statuse',$status);  
					  
					
				if($update_stmt->execute())
				{
					$insertMsg="Update Successfully"; //execute query success message
					echo '<META HTTP-EQUIV="Refresh" Content="1;">';
					
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}


if (isset($_REQUEST['btn-ektp'])) {
  # code...
  $username= "admin";
  $password= "M4Potl0ZZCET2I5AsGrt6w==";
  $CURLOPT_URL="172.24.33.162:8089/gmkservice/ktpreader/services/bacaChip";
  $curl = curl_init();
    curl_setopt_array($curl, array(	
    CURLOPT_URL => "$CURLOPT_URL",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "username=$username&password=$password&format=json",
    CURLOPT_HTTPHEADER => array(
       "content-type: application/x-www-form-urlencoded"
     )
  ));
  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);
  
  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
  
    $data=json_decode($response,true);
  }
}//end if btn-ektp

?>    
    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="../uploads/1.png" alt="User profile picture">

              <h3 class="profile-username text-center"><?php echo $row["field_nama_customer"]; ?></h3>

              <p class="text-muted text-center">Customer JATI</p>   
 
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <!-- <div class="box-header with-border">
              <h3 class="box-title">About Me</h3>
            </div> -->

            <!-- /.box-header -->
            <div class="box-body">
              <hr>
              <strong><i class="fa fa-envelope  margin-r-5"></i> <?php echo $row["field_email"]; ?></strong>
              <hr>
              <strong><i class="fa fa-mobile margin-r-5"></i> <?php echo $row["field_handphone"]; ?></strong>
              <hr>
              <strong><i class="fa fa-file-text-o margin-r-5"></i> <?php echo $row["field_member_id"]; ?></strong>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">Data Customer</a></li>
              <li><a href="#timeline" data-toggle="tab">Timeline</a></li>
              <li><a href="#settings" data-toggle="tab">Ahli Waris</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                    <!-- Post -->
                <div class="post clearfix"> 
                  <div>
                      <form class="from-horizontal" method="post">
                      <!-- <button type="submit" name="btn-ektp" class="btn btn-danger">Scan EKTP</button> -->
                      <input type="submit"  name="btn-ektp" class="btn btn-success " value="Scan EKTP">
                      </form>
                    </div>                     

                  <form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">NIK</label>

                    <div class="col-sm-6">
                      <input type="email" class="form-control" value="<?php echo $data['nik'];?>"id="inputName" placeholder="Name">
                     
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Date of birth</label>

                    <div class="col-sm-6">
                      <input type="text" class="form-control" id="inputEmail" placeholder="Email">
                      <input type="date" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Gender</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Address</label>

                    <div class="col-sm-10">
                      <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                    </div>
                  </div>
                 

                  <div class="form-group">
				            <label for="inputSkills" class="col-sm-2 control-label">Provinsi</label>
				            <div class="col-sm-6">							
				              <select class="form-control" type="text" name="txt_provinsi" id="provinsi"></select>
				            </div>
				          </div>
				          <div class="form-group">
				            <label class="col-sm-2 control-label">Kabupaten</label>
				            <div class="col-sm-6">							
				              <select class="form-control" type="text" name="txt_kabupaten" id="kabupaten">
				              </select>
				            </div>
				          </div>
				          <div class="form-group">
				            <label class="col-sm-2 control-label">Kecamatan</label>
				            <div class="col-sm-6">							
				              <select class="form-control" type="text" name="txt_kecamatan" id="kecamatan">
				              </select>
				            </div>
				          </div>
				          <div class="form-group">
				            <label class="col-sm-2 control-label">Kelurahan</label>
				            <div class="col-sm-6">							
				              <select class="form-control" type="text" name="txt_kelurahan" id="kelurahan">
				              </select>
				            </div>
				          </div>  

                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Agama</label>
                    <div class="col-sm-6">
                    <select class="form-control" type="text" name="" id="">
                    <option value="">Pilih</option>
				            <option value="">Islam</option>
                    <option value="">Protestan</option>
                    <option value="">Katolik</option>
                    <option value="">Hindu</option>
                    <option value="">Buddha</option>
                    <option value="">Khonghucu</option>
                    </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Status Perkawinan</label>
                    <div class="col-sm-6">
                    <select class="form-control" type="text" name="" id="">
				            <option value="">Pilih</option>
                    <option value="">Kawin</option>
                    <option value="">Belum Kawin</option>
                    <option value="">Duda</option>
                    <option value="">Janda</option>
                    
                    </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                  </div>
                </form>

                
                </div>
                <!-- /.post -->

           
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-red">
                          10 Feb. 2014
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-envelope bg-blue"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                      <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                      <div class="timeline-body">
                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                        weebly ning heekya handango imeem plugg dopplr jibjab, movity
                        jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                        quora plaxo ideeli hulu weebly balihoo...
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-primary btn-xs">Read more</a>
                        <a class="btn btn-danger btn-xs">Delete</a>
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-user bg-aqua"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                      <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request
                      </h3>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-comments bg-yellow"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                      <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                      <div class="timeline-body">
                        Take me to your leader!
                        Switzerland is small and neutral!
                        We are more like Germany, ambitious and misunderstood!
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-green">
                          3 Jan. 2014
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-camera bg-purple"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                      <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                      <div class="timeline-body">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                  </li>
                </ul>
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="settings">
              <form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">NIK</label>
                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Date of birth</label>

                    <div class="col-sm-6">
                      <input type="text" class="form-control" id="inputEmail" placeholder="Email">
                      <input type="date" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Gender</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Address</label>

                    <div class="col-sm-10">
                      <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                    </div>
                  </div>
                 

                  <div class="form-group">
				            <label for="inputSkills" class="col-sm-2 control-label">Provinsi</label>
				            <div class="col-sm-6">							
				              <select class="form-control" type="text" name="txt_provinsi" id="provinsi2"></select>
				            </div>
				          </div>
				          <div class="form-group">
				            <label class="col-sm-2 control-label">Kabupaten</label>
				            <div class="col-sm-6">							
				              <select class="form-control" type="text" name="txt_kabupaten" id="kabupaten2">
				              </select>
				            </div>
				          </div>
				          <div class="form-group">
				            <label class="col-sm-2 control-label">Kecamatan</label>
				            <div class="col-sm-6">							
				              <select class="form-control" type="text" name="txt_kecamatan" id="kecamatan2">
				              </select>
				            </div>
				          </div>
				          <div class="form-group">
				            <label class="col-sm-2 control-label">Kelurahan</label>
				            <div class="col-sm-6">							
				              <select class="form-control" type="text" name="txt_kelurahan" id="kelurahan2">
				              </select>
				            </div>
				          </div>  

                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Agama</label>
                    <div class="col-sm-6">
                    <select class="form-control" type="text" name="" id="">
                    <option value="">Pilih</option>
				            <option value="">Islam</option>
                    <option value="">Protestan</option>
                    <option value="">Katolik</option>
                    <option value="">Hindu</option>
                    <option value="">Buddha</option>
                    <option value="">Khonghucu</option>
                    </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Status Perkawinan</label>
                    <div class="col-sm-6">
                    <select class="form-control" type="text" name="" id="">
				            <option value="">Pilih</option>
                    <option value="">Kawin</option>
                    <option value="">Belum Kawin</option>
                    <option value="">Duda</option>
                    <option value="">Janda</option>
                    
                    </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
 