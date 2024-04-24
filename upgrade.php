<?php
    /**
     * upgrade
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2024
     * @var object $services
     * @version 6.20: upgrade.php, v1.00 1/6/2024 7:06 PM Gewa Exp $
     *
     */
    
    use Wojo\Core\Core;
    use Wojo\Validator\Validator;
    
    const _WOJO = true;
    require_once 'init.php';
    
    if (isset($_POST['submit'])) {
        $services->database->rawQuery()->run('
        ALTER TABLE `cart`
          RENAME COLUMN `uid` TO `user_id`,
          RENAME COLUMN `cid` TO `user_m_id`,
          RENAME COLUMN `mid` TO `membership_id`,
          RENAME COLUMN `cid` TO `coupon_id`,
          DROP KEY `idx_membership`, ADD KEY `idx_membership`(`membership_id`),
          DROP KEY `idx_user`, ADD KEY `idx_user`(`user_id`),
          DROP KEY `PRIMARY`, ADD PRIMARY KEY(`user_id`)
        ');
        
        $services->database->rawQuery()->run('
        ALTER TABLE `menus`
          RENAME COLUMN `position` TO `sorting`
        ');
        
        $services->database->rawQuery()->run('
        ALTER TABLE `mod_timeline`
          DROP COLUMN `limiter` ,
          DROP COLUMN `readmore` ,
          DROP COLUMN `fbid` ,
          DROP COLUMN `fbpage` ,
          DROP COLUMN `fbtoken`
        ');
        
        $services->database->rawQuery()->run('
        ALTER TABLE `mod_timeline_data`
          RENAME COLUMN `tid` TO `timeline_id`,
          DROP KEY `tid`, ADD KEY `tid`(`timeline_id`)
        ');
        
        $services->database->rawQuery()->run('
        ALTER TABLE `pages`
          ADD COLUMN `is_builder` tinyint(1) unsigned NOT NULL DEFAULT 1 after `is_system`,
          DROP KEY `idx_search`
        ');
        
        $services->database->rawQuery()->run('
        ALTER TABLE `plug_carousel`
          ADD COLUMN `autoloop` tinyint(1) unsigned NOT NULL DEFAULT 0 after `margin`,
          ADD COLUMN `center` tinyint(1) unsigned NOT NULL DEFAULT 0 after `nav`,
          DROP KEY `loop`
        ');
        
        $services->database->rawQuery()->run('
        ALTER TABLE `settings`
          ADD COLUMN `tax_rate` decimal(6,2) unsigned NOT NULL DEFAULT 0.00 after `enable_tax`
        ');
        
        $services->database->rawQuery()->run('
        ALTER TABLE `user_memberships`
          RENAME COLUMN `tid` TO `transaction_id`,
          RENAME COLUMN `uid` TO `user_id`,
          RENAME COLUMN `mid` TO `membership_id`
        ');
        
        $services->database->rawQuery()->run('
        ALTER TABLE `users`
          DROP KEY `salt`
        ');
        
        $services->database->update(Core::sTable, array('wojov' => '6.20'))->where('id', 1, '=')->run();
    }
?>
<!doctype html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>CMS PRO Upgrade</title>
   <style>
      @import url(https://fonts.googleapis.com/css?family=Raleway:400,100,300,600,700);
      body {
         font-family: Raleway, Arial, Helvetica, sans-serif;
         font-size: 14px;
         line-height: 1.3em;
         color: #FFF;
         background-color: #222;
         font-weight: 300;
         margin: 0;
         padding: 0
      }
      #wrap {
         width: 800px;
         margin-top: 150px;
         margin-right: auto;
         margin-left: auto;
         background-color: #208ed3;
         box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.1);
         border: 2px solid #111;
         border-radius: 3px
      }
      header {
         background-color: #145983;
         font-size: 26px;
         font-weight: 500;
         padding: 35px
      }
      .line {
         height: 2px;
         background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 47%, rgba(255, 255, 255, 0) 100%)
      }
      .line2 {
         position: absolute;
         left: 200px;
         height: 360px;
         width: 2px;
         background: linear-gradient(to bottom, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 47%, rgba(255, 255, 255, 0) 100%);
         display: block
      }
      #content {
         position: relative;
         padding: 45px 20px
      }
      #content .left {
         float: left;
         width: 200px;
         height: 400px;
         background-image: url(assets/images/installer.png);
         background-repeat: no-repeat;
         background-position: 10px center
      }
      #content .right {
         margin-left: 200px
      }
      h4 {
         font-size: 18px;
         font-weight: 300;
         margin: 0 0 40px;
         padding: 0
      }
      p.info {
         background-color: #383838;
         border-radius: 3px;
         box-shadow: 1px 1px 1px 1px rgba(0, 0, 0, 0.1);
         padding: 10px
      }
      p.info span {
         display: block;
         float: left;
         padding: 10px;
         background: rgba(255, 255, 255, 0.1);
         margin-left: -10px;
         margin-top: -10px;
         border-radius: 3px 0 0 3px;
         margin-right: 5px;
         border-right: 1px solid rgba(255, 255, 255, 0.05)
      }
      footer {
         background-color: #383838;
         padding: 20px
      }
      form {
         display: inline-block;
         float: right;
         margin: 0;
         padding: 0
      }
      .button {
         border: 2px solid #222;
         font-family: Raleway, Arial, Helvetica, sans-serif;
         font-size: 14px;
         color: #FFF;
         background-color: #208ED3;
         text-align: center;
         cursor: pointer;
         font-weight: 600;
         transition: all .35s ease;
         outline: none;
         margin: 0;
         padding: 5px 20px
      }
      .button:hover {
         background-color: #222;
         transition: all .55s ease;
         outline: none
      }
      .clear {
         font-size: 0;
         line-height: 0;
         clear: both;
         height: 0
      }
      .clearfix:after {
         content: ".";
         display: block;
         height: 0;
         clear: both;
         visibility: hidden;
      }
      a {
         text-decoration: none;
         float: right
      }
   </style>
</head>
<body>
<div id="wrap">
   <header>Welcome to CMS pro Upgrade Wizard</header>
   <div class="line"></div>
   <div id="content">
      <div class="left">
         <div class="line2"></div>
      </div>
      <div class="right">
         <h4>CMS pro Upgrade</h4>
          <?php if (Validator::compareNumbers($services->core->wojov, 5.80, '!=')): ?>
             <p class="info"><span>Warning!</span>You need at least CMS pro v5.80 in order to continue.</p>
          <?php else: ?>
              <?php if (isset($_GET['update']) && $_GET['update'] == 'done'): ?>
                <p class="info"><span>Success!</span>Installation Completed. Please delete upgrade.php</p>
              <?php else: ?>
                <p class="info"><span>Warning!</span>Please make sure you have performed full backup, including database!!!</p>
                <p style="margin-top:60px">When ready click Install button.</p>
                <p><span>Please be patient, and<strong> DO NOT</strong> Refresh your browser.<br>
        This process might take a while</span>.</p>
              <?php endif; ?>
          <?php endif; ?>
      </div>
   </div>
   <div class="clear"></div>
   <footer class="clearfix"><small>current <b>cms v.<?php echo $services->core->wojov; ?></b></small>
       <?php if (isset($_GET['update']) && $_GET['update'] == 'done'): ?>
          <a href="admin/" class="button">Back to admin panel</a>
       <?php else: ?>
          <form method="post" name="upgrade_form">
              <?php if (Validator::compareNumbers($services->core->wojov, 5.80)): ?>
                 <input name="submit" type="submit" class="button" value="Upgrade DDP" id="submit"/>
              <?php endif; ?>
          </form>
       <?php endif; ?>
   </footer>
</div>
</body>
</html>