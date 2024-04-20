<?php

function GetAdByCode($att,$arg)
{
    $str='';
    $datas = getlistdata_adv_bycode_sp($att["code"],$att["id"],' iorder asc ');
    foreach ($datas as $data)
    {

        $data = (array)$data;

        $str .= '<div class="item">';

        $str .= '<a href="' .  $data["vlink"]. '" title="'.  $data["vname"]. '">' . "<img alt='" . $data["vname"]. "' src='".$data["vimg"]. "' /></a>" ;

        $str .= '</div>';

    }
    return $str;
}
add_shortcode('adv', 'GetAdByCode');

function GetAdByCodedt($att,$arg)

{

    $str = '';

    $datas = getlistdata_adv_bycode($att["code"], ' iorder asc ');
    $i=0;
    $have = false;
    foreach ($datas as $data) {
        $i++;
        $data = (array)$data;
        if ($i % 3 == 1) {
            $str .= '<div class="itemnws">';
            $have = true;
        }
        $str .= '<a href="' . $data["vlink"] . '" title="' . $data["vname"] . '">' . "<img alt='" . $data["vname"] . "' src='". $data["vimg"]."' /></a>";
        if ($i % 3 == 0) {
            $str .= '</div>';
            $have = false;
        }

    }
    if ($have == true)
        $str .= '</div>';

    return $str;

}

add_shortcode('advdt', 'GetAdByCodedt');

function GetAdByCodeslider($att,$arg)

{

    $str='';

    $datas = getlistdata_adv_bycode($att["code"],' iorder asc ');

    foreach ($datas as $data)

    {

        $data = (array)$data;

            $str .= '<div class="item" style="background-image: url('.$data["vimg"].');">';

            $str .= '</div>';

    }

    return $str;

}

add_shortcode('advslider', 'GetAdByCodeslider');

function currentlangadv()

{

    $lan = '';

    if(isset($_GET["lan"]))

        $lan = $_GET["lan"];

    if($lan=='')

    {

        $alan=explode('-',get_bloginfo("language"));

        if(count($alan)==2)

            $lan= $alan[0];

        else

            $lan=get_bloginfo("language");

    }

    return $lan;

}
function getimgpath($path)
{
    $dm = get_site_url();
    return str_replace($dm,'',$path);
}
function getlangadv($drpid,$selected)

{

    $str = "<select name='". $drpid . "' id='". $drpid . "' >";

    $langlist = get_option( 'tnlanguages');

    $arlang = explode('|',$langlist);

    foreach ($arlang as $langl) {

        $current = '';

        $arl = explode('-', $langl);

        if ($arl[1] == $selected)

            $current = 'selected="selected"';

        $str .="<option value='" .$arl[1] . "' ".$current. ">" . $arl[0]. "</option>";

    }

    $str .= "</select>";

    return $str;

}

function getdata_adv($id)

{

    global $wpdb;

    $data = $wpdb->get_row( $wpdb->prepare("SELECT * FROM " . $wpdb->prefix. TNADVS_TABLE_NAME.  " WHERE iid = %d ",$id));

    return $data;

}
function currentlang()
{
    $lan = '';
    if(isset($_GET["lan"]))
        $lan = $_GET["lan"];
    if($lan=='')
    {
        $alan=explode('-',get_bloginfo("language"));
        if(count($alan)==2)
            $lan= $alan[0];
        else
            $lan=get_bloginfo("language");
    }
    return $lan;
}
function getlistdata_adv_bycode($code,$orderby)
{
    if(Strlen($orderby) < 1)
        $orderby='  iid desc ';

    global $wpdb;
    if($code !="")
        $data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM " . $wpdb->prefix. TNADVS_TABLE_NAME.  " WHERE vcode = %s and vstatus ='publish'  order by $orderby ",$code));
    else
    {
        $data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM " . $wpdb->prefix. TNADVS_TABLE_NAME.  " WHERE vstatus ='publish'   order by $orderby" ));
    }
    return $data;
}
function getlistdata_adv_bycode_sp($code,$sp,$orderby)

{

    if(!isset($orderby))
        $orderby='  iid desc ';
    //$lan =currentlang();
    $lan ='';
    global $wpdb;

    if($code !="")
       // $data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM " . $wpdb->prefix. TNADVS_TABLE_NAME.  " WHERE vcode = %s and vlan= %s  and vpr1= %s and vstatus ='publish'  order by $orderby ",array($code,$lan,$sp)));
        $data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM " . $wpdb->prefix. TNADVS_TABLE_NAME.  " WHERE vcode = %s  and vpr1= %s and vstatus ='publish'  order by $orderby ",array($code,$sp)));
    else
    {
        $data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM " . $wpdb->prefix. TNADVS_TABLE_NAME.  " WHERE  vpr1= %s and vstatus ='publish'   order by $orderby" ,$sp));
    }
    return $data;
}
function getlistdata_adv($status,$orderby)

{

    if(!isset($orderby))

        $orderby='  iid desc ';

    global $wpdb;

    if($status !="")

        $data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM " . $wpdb->prefix. TNADVS_TABLE_NAME.  " WHERE vstatus = %s order by $orderby"  ,$status));

    else

    {

        $data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM " . $wpdb->prefix. TNADVS_TABLE_NAME.  " WHERE vstatus != %s order by $orderby" ,'trash'));

    }



    return $data;

}

function deletedata_adv($id)

{

    global $wpdb;

    $wpdb->delete( $wpdb->prefix. TNADVS_TABLE_NAME,  array( 'iid' => $id ));

}

function getimgpath_adv($path)

{

    $dm = get_site_url();

    return str_replace($dm,'',$path);

}

function tncount_adv($status)

{

    global $wpdb;

    if($status !="")

        $data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM " . $wpdb->prefix. TNADVS_TABLE_NAME.  " WHERE vstatus = %s",$status));

    else

        $data = $wpdb->get_results( $wpdb->prepare("SELECT * FROM " . $wpdb->prefix. TNADVS_TABLE_NAME.  " WHERE vstatus != %s",'trash'));

    return count($data) ;



}

function insertdata_adv($lan,$code,$name,$img,$desc,$link,$content,$author,$order,$status,$vpr1,$vpr2,$vpr3)

{

    global $wpdb;

    $wpdb->insert(

        $wpdb->prefix. TNADVS_TABLE_NAME,

        array(

            'vlan' =>$lan,

            'vcode' => $code,

            'vname' => $name,



            'vimg' => $img,

            'vdescription' => $desc,

            'vlink' => $link,

            'vcontent' => $content,

            'dcreate' =>  date('Y-m-d h:i:s', time()),

            'dmodified' =>  date('Y-m-d h:i:s', time()),

            'vauthor' => $author,

            'iorder' => $order,

            'vstatus' => $status,

            'vpr1' => $vpr1,

            'vpr2' => $vpr2,

            'vpr3' => $vpr3

        ),

        array(

            '%s',

            '%s',

            '%s',

            '%s',



            '%s',

            '%s',

            '%s',

            '%s',

            '%s',

            '%s',



            '%d',

            '%s',

            '%s',

            '%s',

            '%s'



        ));

}

function updatedata_adv($id,$lan,$code,$name,$img,$desc,$link,$content,$order,$status,$vpr1)

{

    global $wpdb;

    $wpdb->update(

        $wpdb->prefix. TNADVS_TABLE_NAME,

        array(

            'vlan' => $lan,

            'vcode' => $code,

            'vname' => $name,



            'vimg' => $img,

            'vdescription' => $desc,

            'vlink' => $link,

            'vcontent' => $content,

            'dmodified' =>  date('Y-m-d h:i:s', time()),

            'iorder' => $order,

            'vstatus' => $status,
            'vpr1' => $vpr1

        ),

        array( 'iid' => $id ),

        array(



            '%s',

            '%s',

            '%s',

            '%s',

            '%s',

            '%s',

            '%s',

            '%s',



            '%d',

            '%s',
            '%s'

        ),

        array( '%d' )

    );

}

function updatestatus_adv($id, $status)

{

    global $wpdb;

    $wpdb->update(

        $wpdb->prefix. TNADVS_TABLE_NAME,

        array(

            'vstatus' => $status,	// string

        ),

        array( 'iid' => $id ),

        array(

            '%s'

        ),

        array( '%d' )

    );

}