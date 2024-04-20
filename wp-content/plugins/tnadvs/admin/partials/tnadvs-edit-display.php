<?php

ob_start();

$id = $_GET["id"];

if(isset($_POST['saveinfo'])) {

    $code = $_POST['code'];

    $name = $_POST['name'];

    $link= $_POST['link'];

    $order= $_POST['order'];

    $lan= $_POST['lang'];

    $img = getimgpath($_POST['hdimg']);

    $desc= $_POST['description'];

    $content = str_replace('\"','"',$_POST['content']);

    $status = $_POST['status'];
    $prd_sl = $_POST['prd_sl'];

    $user = wp_get_current_user();

    $author=$user->display_name;

    if ($name == "" || $code == "") {

    } else if (isset($id)) {

        updatedata_adv($id,$lan, $code,$name,$img,$desc,$link,$content,$order,$status,$prd_sl);

        $urlall = admin_url('admin.php?page=' . TNADVS_PLUGIN_SLUG);

        wp_redirect($urlall);

        exit();

    } else {

        insertdata_adv($lan,$code, $name,$img,$desc,$link,$content,$author,$order,$status,$prd_sl,'','' );

        $urlall = admin_url('admin.php?page=' . TNADVS_PLUGIN_SLUG);

        wp_redirect($urlall);

        exit();

    }

}

//detail

if(isset($id))

    $data=getdata_adv($id);

$data=(array)$data;

$editor_settings = array( 'textarea_name' => 'content' );

$urlactiontrash =  add_query_arg( 'action', 'trash', admin_url( 'admin.php?page='.TNADVS_PLUGIN_SLUG ) );

?>

<div class="wrap">

    <h1 class="wp-heading-inline">Thông tin hình ảnh quảng cáo</h1>

    <form id="form-inde" method="post">

    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

            <div id="post-body-content" style="position: relative;">

                <input type="hidden" name="id" value="<?php echo $id ?>" >

                <div id="titlediv">

                    <div id="titlewrap">

                        <label class="screen-reader-text" id="title-prompt-text" for="title">Nhập tiêu đề</label>

                        <input class="ippr" type="text" name="name" size="30" value="<?php echo $data["vname"] ?>" required placeholder="Nhập tiêu đề" id="name" spellcheck="true">

                    </div>

                    <div id="titlewrap">

                        <label class="screen-reader-text" id="title-prompt-text" for="title">Nhập link tại đây</label>

                        <input  class="ippr" type="text" name="link" size="30" value="<?php echo $data["vlink"] ?>" required placeholder="Nhập link" id="link" spellcheck="true">

                    </div>



                    <div id="titlewrap">

                        <label class="screen-reader-text" id="title-prompt-text" for="title">Nhập mô tả tại đây</label>

                        <textarea  class="ippr" name="description"  rows="5"  placeholder="Nhập mô tả" id="description" ><?php echo $data["vdescription"] ?></textarea>

                    </div>

                <div id="postdivrich" class="postarea wp-editor-expand">

                    <label class="screen-reader-text" id="title-prompt-text" for="title">Nhập nội dung</label>

                    <?php wp_editor(  $data["vcontent"], 'content', $editor_settings ); ?>





                </div>

            </div>

            </div>

                <!-- /post-body-content -->



            <div id="postbox-container-1" class="postbox-container">

                <div id="side-sortables" class="meta-box-sortables ui-sortable" >

                    <div id="submitdiv" class="postbox ">

                        <h2 class="hndle ui-sortable-handle">Trạng thái</h2>

                        <div class="inside">

                            <div class="submitbox" id="submitpost">

                                <div id="minor-publishing">

                                    <div id="misc-publishing-actions">



                                        <div class="misc-pub-section misc-pub-post-status">

                                            Trạng thái: <span id="post-status-display"><select name="status" id="status">

                                                    <option <?php if( $data["vstatus"]=="publish") echo  'selected="selected"';?>  value="publish">Đã xuất bản</option>

                                                    <option value="draft" <?php if( $data["vstatus"]=="draft") echo  'selected="selected"';?>>Bản nháp</option>

                                                </select></span>

                                        </div><!-- .misc-pub-section -->

                                       </div>

                                    <div class="clear"></div>

                                <div id="major-publishing-actions">

                                    <div id="delete-action">

                                        <a class="submitdelete deletion" href="<?php echo $urlactiontrash.'&id='. $id ?>" title="Bỏ vào thùng rác">Bỏ vào thùng rác</a></div>

                                    <div id="publishing-action">

                                        <span class="spinner"></span>

                                        <input name="original_publish" type="hidden" id="original_publish" value="Cập nhật">

                                        <input name="saveinfo" type="submit" class="button button-primary button-large" id="saveinfo" value="Cập nhật">

                                    </div>

                                    <div class="clear"></div>

                                </div>

                            </div>



                            </div></div>

                    </div>

                    <div class="postbox hidden">

                        <h2 class="hndle ui-sortable-handle">Ngôn ngữ</h2>

                        <div class="inside">

                            <?php // echo getlang("lang", $data["vlan"]) ?>

                        </div>

                    </div>

                    <div class="postbox">

                        <h2 class="hndle ui-sortable-handle">Thứ tự</h2>

                        <div class="inside">

                            <input type="text"  name="order" size="30" value="<?php echo $data["iorder"] ?>" number required id="order" spellcheck="true">

                        </div>

                    </div>

                    <div class="postbox">

                        <h2 class="hndle ui-sortable-handle">Mã code</h2>

                        <div class="inside">

                            <input type="text"  name="code" size="30" value="<?php echo $data["vcode"] ?>" required id="code" spellcheck="true">

                        </div>

                    </div>

                    <div class="postbox">

                        <h2 class="hndle ui-sortable-handle">Shortcode</h2>

                        <div class="inside">

                            <input type="text" name="shortcode"  size="30" readonly="readonly" value="[adv code='<?php echo $data["vcode"] ?>' id='<?php echo $data["vpr1"] ?>'][/adv]" id="shortcode" spellcheck="true">

                        </div>

                    </div>

                    <div class="postbox">

                        <h2 class="hndle ui-sortable-handle">Thuộc sản phẩm</h2>

                        <div class="inside">
                            <select id="prd_sl" name="prd_sl">
                                <?php
                                $args = array(
                                    'post_type' => 'product',
                                    'post_status' => 'publish',
                                    'posts_per_page' => 10,
                                    'orderby' => 'post_date',
                                    'order' => 'desc'
                                );
                                $loop = new WP_Query($args);
                                while ($loop->have_posts()) : $loop->the_post();
                                    {
                                        $alias = $data["vpr1"];
                                        echo "<option value='" . get_the_ID(). "'";

                                        if(get_the_ID()==$alias)
                                            echo " selected ";
                                        echo ">". get_the_title() ."</option>";
                                    }
                                endwhile;

                                ?>
                            </select>
                        </div>

                    </div>

                    <div id="postimagediv" class="postbox ">

                       <h2 class="hndle ui-sortable-handle"><span>Ảnh quảng cáo</span></h2>

                        <div class="inside">

                            <p class="hide-if-no-js"><img width="100%"  src="<?php echo $data["vimg"] ?>" class="attachment-post-thumbnail size-post-thumbnail" srcset="" sizes="(max-width: 499px) 100vw, 499px"></p>

                            <p class="hide-if-no-js">

                                <a href="javascript(void:0)" id="setimgadv">Chọn hình ảnh</a></p>

                            <input type="hidden" id="hdimg" name="hdimg" value="<?php echo $data["vimg"] ?>">

                        </div>

                    </div>

                </div></div>

            <div class="clear"></div>

            </div>

    </div>

    </form>

    <hr>

<p>Create by: Tâm Nghĩa from <a href="http://tamnghia.com">tamnghia.com</a></p>

</div>

<style>

    .ippr{width: 100%;margin-bottom: 10px;padding: 5px }

</style>

<?php ob_end_flush(); ?>

