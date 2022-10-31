<?php 
require_once('DBConnection.php');

Class Actions extends DBConnection{
    function __construct(){
        parent::__construct();
    }
    function __destruct(){
        parent::__destruct();
    }
    function login(){
        extract($_POST);
        $sql = "SELECT * FROM admin_list where username = '{$username}' and `password` = '".md5($password)."' ";
        @$qry = $this->query($sql)->fetchArray();
        if(!$qry){
            $resp['status'] = "failed";
            $resp['msg'] = "Invalid username or password.";
        }else{
            $resp['status'] = "success";
            $resp['msg'] = "Login successfully.";
            foreach($qry as $k => $v){
                if(!is_numeric($k))
                $_SESSION[$k] = $v;
            }
        }
        return json_encode($resp);
    }
    function logout(){
        session_destroy();
        header("location:./admin");
    }
    function update_credentials(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id','old_password')) && !empty($v)){
                if(!empty($data)) $data .= ",";
                if($k == 'password') $v = md5($v);
                $data .= " `{$k}` = '{$v}' ";
            }
        }
        if(!empty($password) && md5($old_password) != $_SESSION['password']){
            $resp['status'] = 'failed';
            $resp['msg'] = "Old password is incorrect.";
        }else{
            $sql = "UPDATE `admin_list` set {$data} where admin_id = '{$_SESSION['admin_id']}'";
            @$save = $this->query($sql);
            if($save){
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Credential successfully updated.';
                foreach($_POST as $k => $v){
                    if(!in_array($k,array('id','old_password')) && !empty($v)){
                        if(!empty($data)) $data .= ",";
                        if($k == 'password') $v = md5($v);
                        $_SESSION[$k] = $v;
                    }
                }
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'Updating Credentials Failed. Error: '.$this->lastErrorMsg();
                $resp['sql'] =$sql;
            }
        }
        return json_encode($resp);
    }
    function save_user(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
        if(!in_array($k,array('id','type'))){
            if(!empty($id)){
                if(!empty($data)) $data .= ",";
                $data .= " `{$k}` = '{$v}' ";
                }else{
                    $cols[] = $k;
                    $values[] = "'{$v}'";
                }
            }
        }
        if(empty($id)){
            $cols[] = 'password';
            $values[] = "'".md5($username)."'";
        }
        if(isset($cols) && isset($values)){
            $data = "(".implode(',',$cols).") VALUES (".implode(',',$values).")";
        }
        

       
        @$check= $this->query("SELECT count(admin_id) as `count` FROM admin_list where `username` = '{$username}' ".($id > 0 ? " and admin_id != '{$id}' " : ""))->fetchArray()['count'];
        if(@$check> 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "Username already exists.";
        }else{
            if(empty($id)){
                $sql = "INSERT INTO `admin_list` {$data}";
            }else{
                $sql = "UPDATE `admin_list` set {$data} where admin_id = '{$id}'";
            }
            @$save = $this->query($sql);
            if($save){
                $resp['status'] = 'success';
                if(empty($id))
                $resp['msg'] = 'New Admin User successfully saved.';
                else
                $resp['msg'] = 'Admin User Details successfully updated.';
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'Saving Admin User Details Failed. Error: '.$this->lastErrorMsg();
                $resp['sql'] =$sql;
            }
        }
        return json_encode($resp);
    }
    function delete_user(){
        extract($_POST);

        @$delete = $this->query("DELETE FROM `admin_list` where rowid = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Admin User successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function save_system(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!empty($data)) $data.=", ";
            if(!is_numeric($v))
            $v = $this->escapeString($v);
            $data .="('{$k}','{$v}')";
        }
        if(!empty($data)){
            $sql = "INSERT INTO `system_info` (`meta_field`,`meta_value`) VALUES {$data}";
            $this->query("DELETE FROM `system_info` where meta_field not in ('logo_path','website_cover') ");
            $save = $this->query($sql);
            if($save){
                $resp['status'] = 'success';
                $resp['msg'] = 'System Information successfully updated.';
                if(isset($_FILES['logo']) && !empty($_FILES['logo']['tmp_name'])){
                    $logo_file = $_FILES['logo']['tmp_name'];
                    $logo_fname = 'system-logo.png';
                    $file_type = mime_content_type($logo_file);
                    list($width, $height) = getimagesize($logo_file);
                    $t_image = imagecreatetruecolor('200', '200');
                    if(in_array($file_type,array('image/png','image/jpeg','image/jpg'))){
                        $gdImg = ($file_type =='image/png') ? imagecreatefrompng($logo_file) : imagecreatefromjpeg($logo_file);
                        imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, '200', '200', $width, $height);
                        if($t_image){
                            if(is_file(__DIR__.'/uploads/'.$file_type))
                                unlink(__DIR__.'/uploads/'.$file_type);
                                $uploaded = imagepng($t_image,__DIR__.'/uploads/'.$logo_fname);
                                imagedestroy($t_image);
                                if($uploaded){
                                $this->query("DELETE FROM `system_info` where meta_field = 'logo_path' ");
                                $logo_path = "/uploads/{$logo_fname}?v=".time();
                                $this->query("INSERT INTO `system_info` (`meta_field`,`meta_value`) VALUES ('logo_path','$logo_path')");
                                }
                        }else{
                            $resp['msg'] = 'System Information successfully updated but logo image failed to upload.';
                        }
                    }else{
                            $resp['msg'] = 'System Information successfully updated but logo image failed to upload due to invalid file type.';
                    }
                }
                if(isset($_FILES['website_cover']) && !empty($_FILES['website_cover']['tmp_name'])){
                    $cover_file = $_FILES['website_cover']['tmp_name'];
                    $cover_fname = 'system-cover.png';
                    $file_type = mime_content_type($cover_file);
                    list($width, $height) = getimagesize($cover_file);
                    $t_image = imagecreatetruecolor('1200', '600');
                    if(in_array($file_type,array('image/png','image/jpeg','image/jpg'))){
                        $gdImg = ($file_type =='image/png') ? imagecreatefrompng($cover_file) : imagecreatefromjpeg($cover_file);
                        imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, '1200', '600', $width, $height);
                        if($t_image){
                            if(is_file(__DIR__.'/uploads/'.$file_type))
                                unlink(__DIR__.'/uploads/'.$file_type);
                                $uploaded = imagepng($t_image,__DIR__.'/uploads/'.$cover_fname);
                                imagedestroy($t_image);
                                if($uploaded){
                                $this->query("DELETE FROM `system_info` where meta_field = 'website_cover' ");
                                $cover_path = "/uploads/{$cover_fname}?v=".time();
                                $this->query("INSERT INTO `system_info` (`meta_field`,`meta_value`) VALUES ('website_cover','$cover_path')");
                                }
                        }else{
                            $resp['msg'] = 'System Information successfully updated but cover image failed to upload.';
                        }
                    }else{
                            $resp['msg'] = 'System Information successfully updated but cover image failed to upload due to invalid file type.';
                    }
                }
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'System Information has failed to update. Error: '.$this->lastErrorMsg();
            }
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'No POST Data submitted.';
        }
        return json_encode($resp);
    }
    function save_about(){
        extract($_POST);
        $save = file_put_contents('./about.html',$about);
        if($save){
            $resp['status']='success';
            $resp['msg']='About Us Content Successfully Updated.';
        }else{
            $resp['status']='failed';
            $resp['msg']='About Us Content has failed to update.';
        }
        return json_encode($resp);
    }
    function save_plan(){
        $_POST['description'] = htmlentities($_POST['description']);
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id'))){
                if(!is_numeric($v))
                $v = $this->escapeString($v);
                if(empty($id)){
                    $cols[] = $k;
                    $vals[] = $v;
                }else{
                    if(!empty($data)) $data .=", ";
                    $data .=" `{$k}` = '{$v}'";
                }
            }
        }

        if(isset($cols) && isset($vals)){
            $data = "(`".(implode('`,`',$cols))."`) VALUES ('".(implode("','",$vals))."')";
        }
        if(empty($id)){
            $sql = "INSERT INTO `plan_list` {$data}";
        }else{
            $sql = "UPDATE `plan_list` set {$data} where plan_id ='{$id}' ";
        }

        $check = $this->query("SELECT count(plan_id) as `count` FROM `plan_list` where `title` = '{$title}' ".($id > 0? " and plan_id != '{$id}'" : ''))->fetchArray()['count'];
        if($check > 0){
            $resp['status'] = 'failed';
            $resp['msg'] = 'Plan Title already exists.';
        }else{
            @$save = $this->query($sql);
            if($save){
                $resp['status'] = 'success';
                $resp['msg'] = 'Plan successfully saved.';
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'Plan has failed to saved. Error: '.$this->lastErrorMsg();
                $resp['sql'] = $sql;
            }
        }
        return json_encode($resp);
    }
    function delete_plan(){
        extract($_POST);
        @$delete = $this->query("DELETE FROM `plan_list` where plan_id = '{$id}'");
        if($delete){
            $resp['status'] = 'success';
            $resp['msg'] = 'Plan successfully deleted.';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = $resp['msg'];
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'Plan has failed to delete. Error: '.$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function save_featured(){
        extract($_POST);
        $data ="";
        foreach($plan_id as $id){
            if(!empty($data)) $data .= ", ";
            $data .= "('{$id}')";
        }
        $sql = "INSERT INTO `featured_list` (`plan_id`) VALUES {$data} ";
        if(!empty($data)){
            $this->query("DELETE FROM `featured_list`");
            $save = $this->query($sql);
            if($save){
                $resp['status'] = 'success';
                $resp['msg'] = 'Plan successfully saved.';
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'Plan has failed to saved. Error: '.$this->lastErrorMsg();
                $resp['sql'] = $sql;
            }
        }
        return json_encode($resp);
    }
    function save_application(){
        $_POST['id'] = isset($_POST['id']) ? $_POST['id'] : '';
        $_POST['fullname'] = ucwords($_POST['lastname'].', '.$_POST['firstname'].' '.$_POST['middlename']);
        if(empty($_POST['id'])){
            $code = time();
            while(true){
                $check =  $this->query("SELECT count(application_id) as `count` FROM `application_list` where application_code = '$code'")->fetchArray()['count'];
                if($check>0){
                    $code = $code + 1;
                }else{
                    break;
                }
            }
        $_POST['application_code'] = $code;
        }
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(in_array($k,array('plan_id','application_code','fullname','status'))){
                if(!is_numeric($v))
                $v = $this->escapeString($v);
                if(empty($id)){
                    $cols[] = $k;
                    $vals[] = $v;
                }else{
                    if(!empty($data)) $data .=", ";
                    $data .=" `{$k}` = '{$v}'";
                }
            }
        }

        if(isset($cols) && isset($vals)){
            $data = "(`".(implode('`,`',$cols))."`) VALUES ('".(implode("','",$vals))."')";
        }
        if(empty($id)){
            $sql = "INSERT INTO `application_list` {$data}";
        }else{
            $sql = "UPDATE `application_list` set {$data} where application_id ='{$id}' ";
        }
        @$save =$this->query($sql);
        if($save){
            if(empty($id))
            $id= $this->query("SELECT last_insert_rowid()")->fetchArray()[0];
            $data = "";
            foreach($_POST as $k => $v){
                if(!in_array($k,array('id','plan_id','application_code','fullname','status'))){
                    if(!is_numeric($v))
                    $v = $this->escapeString($v);
                    if(!empty($data)) $data .=", ";
                    $data .="('{$id}', '{$k}','{$v}')";
                }
            }
            $resp['status'] = 'success';
            $resp['msg'] = 'Your application was successfully submitted. The management will reach you as soon they sees your application for the next step. Thank you!';
            if(!empty($data)){
                $this->query("DELETE FROM application_meta where application_id = '{$id}'");
                @$save_meta = $this->query("INSERT INTO `application_meta` (`application_id`,`meta_field`,`meta_value`) VALUES {$data}");
                if(!$save_meta){
                    $this->query("DELETE FROM application_list where application_id = '{$id}'");
                    $resp['status'] = 'failed';
                    $resp['msg'] = 'An error occured while saving the data. Error:'.$this->lastErrorMsg();
                }
            }
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'An error occured while saving the data. Error:'.$this->lastErrorMsg();
            $resp['sql'] = $sql;
        }

        if($resp['status'] == 'success'){
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = $resp['msg'];
        }
        return json_encode($resp);
    }
    function delete_application(){
        extract($_POST);
        @$delete = $this->query("DELETE FROM `application_list` where application_id = '{$id}'");
        if($delete){
            $resp['status'] = 'success';
            $resp['msg'] = 'Application successfully deleted.';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = $resp['msg'];
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'Application has failed to delete. Error: '.$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function update_application_status(){
        extract($_POST);
        @$update = $this->query("UPDATE `application_list` set `status` = '{$status}' where application_id = '{$application_id}'");
        if($update){
            $resp['status'] = 'success';
            $resp['msg'] = 'Application status successfully updated.';
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'Application status has failed to update. Error: '.$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
}
$a = isset($_GET['a']) ?$_GET['a'] : '';
$action = new Actions();
switch($a){
    case 'login':
        echo $action->login();
    break;
    case 'logout':
        echo $action->logout();
    break;
    case 'update_credentials':
        echo $action->update_credentials();
    break;
    case 'save_user':
        echo $action->save_user();
    break;
    case 'delete_user':
        echo $action->delete_user();
    break;
    case 'save_system':
        echo $action->save_system();
    break;
    case 'save_plan':
        echo $action->save_plan();
    break;
    case 'delete_plan':
        echo $action->delete_plan();
    break;
    case 'save_featured':
        echo $action->save_featured();
    break;
    case 'save_application':
        echo $action->save_application();
    break;
    case 'save_about':
        echo $action->save_about();
    break;
    case 'delete_application':
        echo $action->delete_application();
    break;
    case 'update_application_status':
        echo $action->update_application_status();
    break;
    default:
    // default action here
    break;
}