<?php
    /**
     * Element Helper
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _element_helper.tpl.php, v1.00 2022-01-08 10:12:05 gewa Exp $
     */
    
    use Wojo\File\File;
    
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    $images = File::findFiles(UPLOADS, array('fileTypes' => array('jpg', 'png', 'svg'), 'exclude' => array('/thumbs/', '/avatars/', '/memberships/'), 'returnType' => 'fullPath'));
?>
<div id="element-helper" class="hide-all is_draggable">
    <div class="header">
        <i class="icon white pencil"></i>
        <h3 class="handle"> Element Insert</h3>
        <a class="close-styler"><i class="icon white x"></i></a>
    </div>
    <div class="sub-header">
        <div class="row">
            <div class="columns">
                <a data-tab="el_button" data-type="button" class="wojo simple fluid button tbutton active"> Button</a>
            </div>
            <div class="columns">
                <a data-tab="el_icon" data-type="icon" class="wojo simple fluid button tbutton">Icon</a>
            </div>
            <div class="columns">
                <a data-tab="el_image" data-type="image" class="wojo simple fluid button tbutton"> Image</a>
            </div>
            <div class="columns">
                <a data-tab="el_text" data-type="text" class="wojo simple fluid button tbutton"> Text</a>
            </div>
        </div>
    </div>
    <div class="max-height400 scrollbox">
        <div id="el_button" class="wojo tab active">
            <div class="wojo small form padding-small" id="buttons">
                <div class="wojo small fields">
                    <div class="field">
                        <a class="wojo button"><span>Button Text</span></a>
                    </div>
                    <div class="field">
                        <label> Background Color </label>
                        <input type="text" placeholder="Background Color" data-color="bg" class="docolors">
                    </div>
                </div>
                <div class="wojo small fields">
                    <div class="field">
                        <a class="wojo rounded button"><span>Button Text</span></a>
                    </div>
                    <div class="field">
                        <label> Text Color </label>
                        <input type="text" placeholder="Text Color" data-color="text" class="docolors">
                    </div>
                </div>
                <div class="wojo small fields">
                    <div class="field">
                        <a class="wojo button"><i class="icon check"></i><span>Button Text</span></a>
                    </div>
                    <div class="field">
                        <label> Icon Color </label>
                        <input type="text" placeholder="Icon Color" data-color="icon" class="docolors">
                    </div>
                </div>
                <div class="wojo small fields">
                    <div class="field">
                        <a class="wojo circular icon button"><i class="icon check"></i></a>
                    </div>
                    <div class="field">
                        <label> Button Text </label>
                        <input type="text" placeholder="Button Text" name="btext">
                    </div>
                </div>
                <div class="wojo small fields">
                    <div class="field">
                        <a class="wojo labeled button">
                            <i class="icon check"></i>
                            <span>Button Text</span>
                        </a>
                    </div>
                    <div class="field">
                        <label> Button Link </label>
                        <input type="text" placeholder="https://" name="burl">
                    </div>
                </div>
            </div>
        </div>
        <div id="el_icon" class="wojo tab">
            <div class="padding-small">
                <?php include(BASEPATH . 'view/admin/snippets/icons.tpl.php'); ?>
            </div>
        </div>
        <div id="el_image" class="wojo tab">
            <div class="padding-small">
                <div class="mason">
                    <?php foreach ($images as $rows): ?>
                        <?php $file = str_replace(UPLOADS, UPLOADURL, $rows); ?>
                        <?php if (substr(strrchr($rows, '.'), 1) == 'svg'): ?>
                            <a class="items item thumb" data-type="svg"
                                data-src="<?php echo str_replace(BASEPATH, '', $rows); ?>"><img src="<?php echo $file; ?>" alt=""></a>
                        <?php else: ?>
                            <?php if (File::is_File(UPLOADS . 'thumbs/' . basename($file))): ?>
                                <a class="columns item thumb" data-type="img"
                                    data-src="<?php echo str_replace(BASEPATH, '', $rows); ?>"><img
                                        src="<?php echo UPLOADURL . 'thumbs/' . basename($file); ?>" alt=""></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div id="el_text" class="wojo tab">
            <div class="padding-small center-align">
                <div class="item">
                    <h1>Welcome to Our Company</h1>
                </div>
                <div class="item">
                    <h2>Welcome to Our Company</h2>
                </div>
                <div class="item">
                    <h3>Welcome to Our Company</h3>
                </div>
                <div class="item">
                    <h4>Welcome to Our Company</h4>
                </div>
                <div class="item">
                    <p>Demonstrate relevant and engaging content but maximise share of voice. Target key demographics so that we
                        make users into advocates.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="padding-small">
        <div class="actions center-align">
            <button class="wojo insert small primary button">insert</button>
        </div>
    </div>
</div>