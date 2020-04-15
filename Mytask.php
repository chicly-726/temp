<?php
require_once "Collection.php";
require_once "Mydata.php";

$tempss=new MainInfo();
$state = array("current_date"=> "03/18/2020", "current_page" =>"1137", "current_user" => "13");
$primary_data = array("Userinfo"=>(new UserInfo(2, "vignesh","admin",new User_Memberships("junior_physics","12/03/2020"))), "Memberships"=>array((new Memberships("junior_physics","1 Year",array("1"=>"Introduction To Physics","2"=>"Newtonian Physics","3"=>"Laws of Motion")))),"Pages" => array(new Pages("category_id","1212")));

$flag=$tempss->checkStatus($state,$primary_data);
if($flag)
{
echo "<br>True";
}else
{
	echo "<br>False";
}

class MainInfo
{
function checkStatus($state,$primary_data)
{
	$response="";
	//echo $state['current_user']; 
$userDetails=getValues_fromUser($state['current_user']);
$usrInfo=new \stdClass();
$usrInfo=$userDetails;
//echo $usrInfo->getUserRole();
switch($usrInfo->getUserRole())
{
	case "admin":
	return true;
	break;
	case "editor":
	return true;
	break;
	case "subscriber":	
		 $usrInfo->getMember_Catg();
		 $usrInfo->getMember_activation();
		$meObj=new \stdClass();
		$meObj=Memberships_values($usrInfo->getMember_Catg());
		$dur=$meObj->getDuration();
		$allowed_categories=$meObj->getAllowed_categories();
		
		if($this->calculateDuration($dur,$state['current_date']))
		{		
				$pageValue=new \stdClass();
				$pageValue=page_values("1137");
				$pageCateg=$pageValue->getPage_categ()."<br>";
				$keyFlag=false;
				foreach($allowed_categories as $key=>$valus)
				{
					if($key==$pageCateg)
					{
						$keyFlag=true;
						break;
					}
					
				}
if(!$keyFlag)	{
			echo "<br>Memberships categorid Not avalibale<br>";
			return false;
}					
		}else{
			echo "<br>Please activate Memberships<br>";
			return false;
		}
	
	break;
}
	return false;
}
 function calculateDuration($getdurtn,$compDuration)
{	
		$dateget=date("m/d/Y");
		$date1=date_create($dateget);
		$date2=date_create($compDuration);
		$diff=date_diff($date1,$date2);		
	if(strpos($getdurtn,"months")!= null)
	{
		
		$getvalues=(($diff->format("%a"))/30);
		$arr=explode(" ",$getdurtn);	
		if($arr[0]>=$getvalues){
			return true;
		}else
			return false;
	}
	else if(strpos($getdurtn,"year")!= null)
	{	
	$getvalues=(($diff->format("%a"))/365);
			$arr=explode(" ",$getdurtn);		
		if($arr[0]>=$getvalues){
			return true;
		}else
			return false;
	}
	else if(strpos($getdurtn,"days")!= null)
	{			
	$getvalues=(($diff->format("%a")));
			$arr=explode(" ",$getdurtn);		
		if($arr[0]>=$getvalues){
			return true;
		}else
			return false;
	}
	return false;
}
}
?>